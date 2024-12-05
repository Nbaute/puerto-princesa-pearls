<?php

namespace App\Services;
use App\Services\Sms\Itexmo;

class SmsService
{
    private $platform = "itexmo";
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
    }

    public function setPlatform(string $platform)
    {
        $this->platform = $platform;
        return $this;
    }

    public function sendMessage(string $message, array $recipients, $refId = null)
    {
        switch ($this->platform) {

            case 'itexmo':
                $itexmo = new Itexmo();
                return $itexmo->broadcast($message, $recipients, false);
        }
    }
}
