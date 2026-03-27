<?php

namespace App\Services\Crm;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SmsService
{
    private string $sid;
    private string $token;
    private string $from;

    public function __construct()
    {
        $this->sid = config('services.twilio.sid', '');
        $this->token = config('services.twilio.token', '');
        $this->from = config('services.twilio.phone', '');
    }

    public function send(string $to, string $message): array
    {
        if (empty($this->sid) || empty($this->token)) {
            Log::info("[SMS] Sandbox: To={$to}, Msg={$message}");
            return ['success' => true, 'simulated' => true, 'sid' => 'sim-' . uniqid()];
        }

        $response = Http::withBasicAuth($this->sid, $this->token)
            ->asForm()
            ->post("https://api.twilio.com/2010-04-01/Accounts/{$this->sid}/Messages.json", [
                'To'   => $to,
                'From' => $this->from,
                'Body' => $message,
            ]);

        if ($response->successful()) {
            return ['success' => true, 'sid' => $response->json('sid')];
        }

        Log::error("[SMS] Failed: " . $response->body());
        return ['success' => false, 'error' => $response->json('message', 'Unknown error')];
    }

    public function sendBulk(array $recipients, string $message): array
    {
        $results = [];
        foreach ($recipients as $to) {
            $results[] = $this->send($to, $message);
        }
        return $results;
    }
}
