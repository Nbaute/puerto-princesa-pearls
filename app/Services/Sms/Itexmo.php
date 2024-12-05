<?php

namespace App\Services\Sms;
use App\Models\SmsLog;
use Illuminate\Support\Facades\Http;
use Throwable;

class Itexmo
{
    private final $platform = "itexmo";
    private $apiUrl;
    private $email;
    private $password;
    private $apiCode;
    private $senderId;
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        $this->apiUrl = env('ITEXMO_API_URL', 'https://api.itexmo.com/api');
        $this->email = env('ITEXMO_EMAIL');
        $this->password = env('ITEXMO_PASSWORD');
        $this->apiCode = env('ITEXMO_API_CODE');
        $this->senderId = env('ITEXMO_SENDER_ID');
    }

    public function broadcast(string $message, array $recipients, bool $isInternational = false)
    {
        $path = $this->apiUrl . "/broadcast";
        foreach ($recipients as $k => $r) {
            $r = str_replace("+63", "0", $r);
            $r = str_replace(' ', '', $r);
            $recipients[$k] = $r;
        }

        $payload = [
            "Email" => $this->email,
            "Password" => $this->password,
            "Recipients" => $recipients,
            "Message" => $message,
            "ApiCode" => $this->apiCode,
        ];

        $response = Http::post($path, $payload);
        try {
            $smsLog = new SmsLog();
            $smsLog->recipient = json_encode($recipients);
            $smsLog->message = $message;
            $smsLog->sender_id = $this->senderId;
            $smsLog->is_intl = $isInternational;
            $smsLog->platform = $this->platform;
            $smsLog->request = json_encode([
                'url' => $path,
                'payload' => $payload,
            ]);
            $smsLog->response = json_encode($response->json() ?? []);
            $smsLog->save();
        } catch (Throwable $t) {

        }
        return $response;
    }
}
