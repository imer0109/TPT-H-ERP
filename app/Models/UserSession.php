<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserSession extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'session_id',
        'ip_address',
        'user_agent',
        'device_type',
        'browser',
        'platform',
        'location',
        'timezone',
        'is_suspicious',
        'login_at',
        'last_activity',
        'logout_at',
        'logout_type',
        'is_active'
    ];

    protected $casts = [
        'is_suspicious' => 'boolean',
        'is_active' => 'boolean',
        'login_at' => 'datetime',
        'last_activity' => 'datetime',
        'logout_at' => 'datetime',
        'location' => 'json'
    ];

    /**
     * Get the user that owns this session
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Check if session is expired
     */
    public function isExpired()
    {
        $sessionLifetime = config('session.lifetime', 120); // minutes
        return $this->last_activity->addMinutes($sessionLifetime)->isPast();
    }

    /**
     * Mark session as suspicious
     */
    public function markAsSuspicious($reason = null)
    {
        $this->update([
            'is_suspicious' => true
        ]);

        // Log the suspicious activity
        AuditTrail::logEvent('suspicious_session', $this, null, null, [
            'reason' => $reason,
            'ip_address' => $this->ip_address,
            'user_agent' => $this->user_agent
        ]);
    }

    /**
     * Force logout this session
     */
    public function forceLogout($reason = 'admin_action')
    {
        $this->update([
            'is_active' => false,
            'logout_at' => now(),
            'logout_type' => $reason
        ]);

        // Log the forced logout
        AuditTrail::logEvent('forced_logout', $this, null, null, [
            'reason' => $reason,
            'admin_user_id' => auth()->id()
        ]);
    }

    /**
     * Detect suspicious login patterns
     */
    public static function detectSuspiciousActivity($userId, $ipAddress, $userAgent, $location = null)
    {
        $user = User::find($userId);
        $recentSessions = self::where('user_id', $userId)
                             ->where('created_at', '>', now()->subDays(7))
                             ->orderBy('created_at', 'desc')
                             ->get();

        $isSuspicious = false;
        $reasons = [];

        // Check for different IP addresses
        $recentIps = $recentSessions->pluck('ip_address')->unique();
        if ($recentIps->count() > 1 && !$recentIps->contains($ipAddress)) {
            $isSuspicious = true;
            $reasons[] = 'New IP address';
        }

        // Check for different locations (if available)
        if ($location && $recentSessions->isNotEmpty()) {
            $lastLocation = $recentSessions->first()->location;
            if ($lastLocation && isset($location['country']) && isset($lastLocation['country'])) {
                if ($location['country'] !== $lastLocation['country']) {
                    $isSuspicious = true;
                    $reasons[] = 'Different country';
                }
            }
        }

        // Check for unusual login times
        $currentHour = now()->hour;
        $usualHours = $recentSessions->pluck('login_at')
                                   ->map(fn($date) => $date->hour)
                                   ->unique();
        
        if ($usualHours->isNotEmpty() && !$usualHours->contains($currentHour)) {
            $hourDiff = $usualHours->map(fn($hour) => abs($hour - $currentHour))->min();
            if ($hourDiff > 4) { // More than 4 hours difference
                $isSuspicious = true;
                $reasons[] = 'Unusual login time';
            }
        }

        return [
            'is_suspicious' => $isSuspicious,
            'reasons' => $reasons
        ];
    }

    /**
     * Create new session record
     */
    public static function createSession($userId, $sessionId, $ipAddress, $userAgent, $location = null)
    {
        // Detect device and browser info
        $deviceInfo = self::parseUserAgent($userAgent);
        
        // Check for suspicious activity
        $suspiciousCheck = self::detectSuspiciousActivity($userId, $ipAddress, $userAgent, $location);

        $session = self::create([
            'user_id' => $userId,
            'session_id' => $sessionId,
            'ip_address' => $ipAddress,
            'user_agent' => $userAgent,
            'device_type' => $deviceInfo['device'],
            'browser' => $deviceInfo['browser'],
            'platform' => $deviceInfo['platform'],
            'location' => $location,
            'timezone' => $location['timezone'] ?? null,
            'is_suspicious' => $suspiciousCheck['is_suspicious'],
            'login_at' => now(),
            'last_activity' => now(),
            'is_active' => true
        ]);

        // Log the login
        AuditTrail::logEvent('login', $session, null, null, [
            'ip_address' => $ipAddress,
            'user_agent' => $userAgent,
            'is_suspicious' => $suspiciousCheck['is_suspicious'],
            'suspicious_reasons' => $suspiciousCheck['reasons']
        ]);

        return $session;
    }

    /**
     * Parse user agent to extract device info
     */
    protected static function parseUserAgent($userAgent)
    {
        $device = 'desktop';
        $browser = 'unknown';
        $platform = 'unknown';

        // Simple user agent parsing (you might want to use a library like jenssegers/agent)
        if (preg_match('/(mobile|tablet|ipad|iphone|android)/i', $userAgent)) {
            $device = 'mobile';
        }

        if (preg_match('/Chrome/i', $userAgent)) {
            $browser = 'Chrome';
        } elseif (preg_match('/Firefox/i', $userAgent)) {
            $browser = 'Firefox';
        } elseif (preg_match('/Safari/i', $userAgent)) {
            $browser = 'Safari';
        } elseif (preg_match('/Edge/i', $userAgent)) {
            $browser = 'Edge';
        }

        if (preg_match('/Windows/i', $userAgent)) {
            $platform = 'Windows';
        } elseif (preg_match('/Mac/i', $userAgent)) {
            $platform = 'macOS';
        } elseif (preg_match('/Linux/i', $userAgent)) {
            $platform = 'Linux';
        } elseif (preg_match('/Android/i', $userAgent)) {
            $platform = 'Android';
        } elseif (preg_match('/iOS/i', $userAgent)) {
            $platform = 'iOS';
        }

        return [
            'device' => $device,
            'browser' => $browser,
            'platform' => $platform
        ];
    }

    /**
     * Check if session is from an allowed device/platform
     */
    public function isAllowedDevice($allowedDevices = null)
    {
        if (!$allowedDevices) {
            $allowedDevices = $this->user->getAllowedDevices();
        }
        
        if (empty($allowedDevices)) {
            return true; // No restrictions
        }
        
        // Check device type
        if (in_array('all', $allowedDevices)) {
            return true;
        }
        
        if (in_array($this->device_type, $allowedDevices)) {
            return true;
        }
        
        // Check platform
        if (in_array($this->platform, $allowedDevices)) {
            return true;
        }
        
        // Check browser
        if (in_array($this->browser, $allowedDevices)) {
            return true;
        }
        
        return false;
    }
    
    /**
     * Check if session is from an allowed IP range
     */
    public function isAllowedIP($allowedIPs = null)
    {
        if (!$allowedIPs) {
            $allowedIPs = $this->user->getAllowedIPs();
        }
        
        if (empty($allowedIPs)) {
            return true; // No restrictions
        }
        
        foreach ($allowedIPs as $allowedIP) {
            if ($this->matchesIPRange($this->ip_address, $allowedIP)) {
                return true;
            }
        }
        
        return false;
    }
    
    /**
     * Check if IP matches a range or specific IP
     */
    protected function matchesIPRange($ip, $range)
    {
        if (strpos($range, '/') !== false) {
            // CIDR notation
            list($subnet, $mask) = explode('/', $range);
            if ((ip2long($ip) & ~((1 << (32 - $mask)) - 1)) == ip2long($subnet)) {
                return true;
            }
        } else {
            // Exact match
            if ($ip === $range) {
                return true;
            }
        }
        
        return false;
    }
    
    /**
     * Check if session is within allowed time windows
     */
    public function isWithinAllowedTime($allowedTimes = null)
    {
        if (!$allowedTimes) {
            $allowedTimes = $this->user->getAllowedTimeWindows();
        }
        
        if (empty($allowedTimes)) {
            return true; // No restrictions
        }
        
        $currentTime = now();
        $currentDay = strtolower($currentTime->format('l'));
        $currentHour = (int) $currentTime->format('H');
        
        foreach ($allowedTimes as $timeWindow) {
            // Check if day is allowed
            if (isset($timeWindow['days']) && !in_array($currentDay, $timeWindow['days'])) {
                continue;
            }
            
            // Check if time is within range
            if (isset($timeWindow['start']) && isset($timeWindow['end'])) {
                $startHour = (int) explode(':', $timeWindow['start'])[0];
                $endHour = (int) explode(':', $timeWindow['end'])[0];
                
                if ($currentHour >= $startHour && $currentHour <= $endHour) {
                    return true;
                }
            }
        }
        
        return false;
    }
    
    /**
     * Scope for active sessions
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for suspicious sessions
     */
    public function scopeSuspicious($query)
    {
        return $query->where('is_suspicious', true);
    }

    /**
     * Scope for expired sessions
     */
    public function scopeExpired($query)
    {
        $sessionLifetime = config('session.lifetime', 120);
        return $query->where('last_activity', '<', now()->subMinutes($sessionLifetime));
    }
}