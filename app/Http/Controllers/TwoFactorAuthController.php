<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\TwoFactorAuth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TwoFactorAuthController extends Controller
{
    public function showSetupForm()
    {
        $user = Auth::user();
        
        // Ensure user has a 2FA record
        if (!$user->twoFactorAuth) {
            $twoFactorAuth = new TwoFactorAuth();
            $twoFactorAuth->user_id = $user->id;
            $twoFactorAuth->save();
            $user->setRelation('twoFactorAuth', $twoFactorAuth);
        }
        
        // Generate secret if not already exists
        if (!$user->twoFactorAuth->secret) {
            $user->twoFactorAuth->generateSecret();
            $user->twoFactorAuth->save();
        }
        
        // Generate recovery codes if not already exists
        if (!$user->twoFactorAuth->recovery_codes) {
            $user->twoFactorAuth->generateRecoveryCodes();
            $user->twoFactorAuth->save();
        }
        
        $qrCodeUrl = $user->twoFactorAuth->getQrCodeUrl();
        
        return view('auth.2fa.setup', compact('user', 'qrCodeUrl'));
    }
    
    public function enable2FA(Request $request)
    {
        $user = Auth::user();
        $code = $request->input('code');
        
        // Verify the code
        if ($user->twoFactorAuth->verifyCode($code)) {
            $user->twoFactorAuth->enable();
            $user->update(['two_factor_enabled' => true]);
            
            return redirect()->route('dashboard')
                ->with('success', 'L\'authentification à deux facteurs a été activée avec succès.');
        }
        
        return redirect()->back()
            ->withErrors(['code' => 'Le code est invalide. Veuillez réessayer.']);
    }
    
    public function disable2FA(Request $request)
    {
        $user = Auth::user();
        
        $user->twoFactorAuth->disable();
        $user->update(['two_factor_enabled' => false]);
        
        return redirect()->route('dashboard')
            ->with('success', 'L\'authentification à deux facteurs a été désactivée.');
    }
    
    public function showRecoveryCodes()
    {
        $user = Auth::user();
        $recoveryCodes = $user->twoFactorAuth->recovery_codes;
        
        return view('auth.2fa.recovery', compact('user', 'recoveryCodes'));
    }
    
    public function regenerateRecoveryCodes()
    {
        $user = Auth::user();
        $user->twoFactorAuth->generateRecoveryCodes();
        $user->twoFactorAuth->save();
        
        return redirect()->back()
            ->with('success', 'Les codes de récupération ont été régénérés avec succès.');
    }
}