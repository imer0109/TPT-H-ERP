<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Services\TwoFactorAuthService;

class TwoFactorAuth extends Model
{
    use HasFactory;

    protected $table = 'two_factor_auth';

    protected $fillable = [
        'user_id',
        'secret',
        'recovery_codes',
        'enabled_at',
        'is_enabled'
    ];

    protected $casts = [
        'recovery_codes' => 'array',
        'enabled_at' => 'datetime',
        'is_enabled' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    protected $hidden = [
        'secret',
        'recovery_codes'
    ];

    /**
     * Get the user that owns this 2FA setup
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Generate a new secret for the user
     */
    public function generateSecret()
    {
        $service = new TwoFactorAuthService();
        $this->secret = $service->generateSecretKey();
        return $this->secret;
    }

    /**
     * Generate recovery codes
     */
    public function generateRecoveryCodes()
    {
        $codes = [];
        for ($i = 0; $i < 8; $i++) {
            $codes[] = strtoupper(substr(md5(uniqid(rand(), true)), 0, 8));
        }
        
        $this->recovery_codes = $codes;
        return $codes;
    }

    /**
     * Verify a TOTP code
     */
    public function verifyCode($code)
    {
        if (!$this->is_enabled || !$this->secret) {
            return false;
        }

        $service = new TwoFactorAuthService();
        return $service->verifyKey($this->secret, $code);
    }

    /**
     * Use a recovery code
     */
    public function useRecoveryCode($code)
    {
        if (!$this->recovery_codes || !in_array($code, $this->recovery_codes)) {
            return false;
        }

        // Remove the used code
        $codes = $this->recovery_codes;
        $key = array_search($code, $codes);
        unset($codes[$key]);
        
        $this->recovery_codes = array_values($codes);
        $this->save();

        return true;
    }

    /**
     * Enable 2FA for the user
     */
    public function enable()
    {
        $this->update([
            'is_enabled' => true,
            'enabled_at' => now()
        ]);

        // Log the 2FA enablement
        AuditTrail::logEvent('2fa_enabled', $this->user, null, null, [
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent()
        ]);
    }

    /**
     * Disable 2FA for the user
     */
    public function disable()
    {
        $this->update([
            'is_enabled' => false,
            'enabled_at' => null
        ]);

        // Log the 2FA disablement
        AuditTrail::logEvent('2fa_disabled', $this->user, null, null, [
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent()
        ]);
    }

    /**
     * Get QR code URL for Google Authenticator
     */
    public function getQrCodeUrl()
    {
        if (!$this->secret) {
            return null;
        }

        $companyName = config('app.name', 'TPT-H ERP');
        $userEmail = $this->user->email;

        $service = new TwoFactorAuthService();
        return $service->getQRCodeUrl(
            $companyName,
            $userEmail,
            $this->secret
        );
    }

    /**
     * Check if user has recovery codes remaining
     */
    public function hasRecoveryCodes()
    {
        return $this->recovery_codes && count($this->recovery_codes) > 0;
    }
}