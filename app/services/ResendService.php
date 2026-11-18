<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use RuntimeException;

class ResendService
{
    public function sendEmail(
        string $to,
        string $subject,
        string $html
    ): void {
        $apiKey = config('services.resend.key');
        $fromEmail = config('services.resend.from_email');
        $fromName = config('services.resend.from_name');

        if (!$apiKey || !$fromEmail) {
            throw new RuntimeException(
                'Resend chưa được cấu hình đầy đủ.'
            );
        }

        $response = Http::withToken($apiKey)
            ->acceptJson()
            ->post('https://api.resend.com/emails', [
                'from' => $fromName
                    ? $fromName . ' <' . $fromEmail . '>'
                    : $fromEmail,
                'to' => [$to],
                'subject' => $subject,
                'html' => $html,
            ]);

        if (!$response->successful()) {
            throw new RuntimeException(
                'Resend gửi email thất bại: ' . $response->body()
            );
        }
    }
}
