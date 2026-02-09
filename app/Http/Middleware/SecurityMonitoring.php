<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\UserSession;
use App\Models\AuditTrail;

class SecurityMonitoring
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        $user = auth()->user();

        if ($user) {
            // Update last activity for session tracking
            $this->updateUserActivity($request, $user);

            // Check for suspicious activity
            $this->checkSuspiciousActivity($request, $user);

            // Validate session integrity
            $this->validateSession($request, $user);
        }

        return $next($request);
    }

    /**
     * Update user's last activity
     */
    protected function updateUserActivity(Request $request, $user)
    {
        $sessionId = session()->getId();
        
        UserSession::where('user_id', $user->id)
                  ->where('session_id', $sessionId)
                  ->where('is_active', true)
                  ->update(['last_activity' => now()]);
    }

    /**
     * Check for suspicious activity patterns
     */
    protected function checkSuspiciousActivity(Request $request, $user)
    {
        $currentIp = $request->ip();
        $userAgent = $request->userAgent();
        
        // Get current session
        $currentSession = UserSession::where('user_id', $user->id)
                                   ->where('session_id', session()->getId())
                                   ->first();

        if ($currentSession) {
            // Check for IP address change within same session
            if ($currentSession->ip_address !== $currentIp) {
                $currentSession->markAsSuspicious('IP address changed during session');
                
                // Force logout for security
                auth()->logout();
                
                return redirect()->route('login')->with('error', 'Session terminée pour des raisons de sécurité. IP address modifiée.');
            }

            // Check for user agent change (could indicate session hijacking)
            if ($currentSession->user_agent !== $userAgent) {
                $currentSession->markAsSuspicious('User agent changed during session');
                
                // Log the suspicious activity
                AuditTrail::logEvent('suspicious_user_agent_change', $user, null, null, [
                    'original_user_agent' => $currentSession->user_agent,
                    'new_user_agent' => $userAgent,
                    'ip_address' => $currentIp
                ]);
            }
        }

        // Check for rapid successive requests (potential bot/attack)
        $recentRequests = cache()->get('user_requests_' . $user->id, []);
        $recentRequests[] = now()->timestamp;
        
        // Keep only requests from last minute
        $recentRequests = array_filter($recentRequests, function($timestamp) {
            return $timestamp > (now()->timestamp - 60);
        });

        if (count($recentRequests) > 100) { // More than 100 requests per minute
            AuditTrail::logEvent('suspicious_request_rate', $user, null, null, [
                'request_count' => count($recentRequests),
                'ip_address' => $currentIp,
                'user_agent' => $userAgent
            ]);

            // Temporarily block user
            cache()->put('blocked_user_' . $user->id, true, now()->addMinutes(15));
        }

        cache()->put('user_requests_' . $user->id, $recentRequests, now()->addMinutes(2));
    }

    /**
     * Validate session security
     */
    protected function validateSession(Request $request, $user)
    {
        // Check if user is blocked
        if (cache()->has('blocked_user_' . $user->id)) {
            auth()->logout();
            return redirect()->route('login')->with('error', 'Compte temporairement bloqué pour activité suspecte.');
        }

        // Check session timeout
        $sessionLifetime = config('session.lifetime', 120); // minutes
        $lastActivity = session('last_activity', now());
        
        if (now()->diffInMinutes($lastActivity) > $sessionLifetime) {
            auth()->logout();
            return redirect()->route('login')->with('error', 'Session expirée. Veuillez vous reconnecter.');
        }

        session(['last_activity' => now()]);

        // Check for expired 2FA sessions
        if ($user->hasTwoFactorEnabled() && !session('2fa_verified')) {
            return redirect()->route('2fa.verify')->with('error', 'Vérification 2FA requise.');
        }
    }
}