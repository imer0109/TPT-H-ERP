<?php

namespace App\Services;

use OTPHP\TOTP;
use BaconQrCode\Renderer\Image\SvgImageBackEnd;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;

class TwoFactorAuthService
{
    /**
     * Generate a new secret key for 2FA
     */
    public function generateSecretKey()
    {
        return TOTP::create()->getSecret();
    }

    /**
     * Verify a 2FA code
     */
    public function verifyKey($secret, $code)
    {
        $totp = TOTP::create($secret);
        return $totp->verify($code);
    }

    /**
     * Get QR code URL for Google Authenticator
     */
    public function getQRCodeUrl($companyName, $userEmail, $secret)
    {
        $totp = TOTP::create($secret);
        $totp->setLabel("{$companyName}:{$userEmail}");
        $totp->setIssuer($companyName);
        
        return $totp->getProvisioningUri();
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
        return $codes;
    }
}