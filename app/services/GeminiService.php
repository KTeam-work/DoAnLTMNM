<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Exception;

class GeminiService
{
    public function chat(string $message, string $systemPrompt): string
    {
        $apiKey = config('services.gemini.key');
        $configuredModel = config('services.gemini.model') ?: 'gemini-flash-lite-latest';

        if (!$apiKey) {
            throw new Exception('Chưa cấu hình Gemini API Key. Vui lòng kiểm tra file .env (GEMINI_API_KEY).');
        }

        // Danh sách các model thử nghiệm (tự động fallback nếu model chính bị 404 không khả dụng hoặc 503 quá tải)
        $modelsToTry = array_values(array_unique(array_filter([
            $configuredModel,
            'gemini-flash-lite-latest',
            'gemini-3.6-flash',
            'gemini-3-flash-preview',
            'gemini-3.8-flash',
        ])));

        $lastError = null;

        foreach ($modelsToTry as $model) {
            try {
                $response = Http::timeout(30)
                    ->withHeaders([
                        'x-goog-api-key' => $apiKey,
                        'Content-Type' => 'application/json',
                    ])
                    ->post(
                        "https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent",
                        [
                            'systemInstruction' => [
                                'parts' => [
                                    ['text' => $systemPrompt]
                                ]
                            ],
                            'contents' => [
                                [
                                    'role' => 'user',
                                    'parts' => [
                                        ['text' => $message]
                                    ]
                                ]
                            ],
                            'generationConfig' => [
                                'temperature' => 0.5,
                                'maxOutputTokens' => 1000,
                            ],
                        ]
                    );

                if ($response->successful()) {
                    $result = $response->json('candidates.0.content.parts.0.text');
                    if (!empty($result)) {
                        return $result;
                    }
                }

                $errorBody = $response->body();
                $lastError = "Model [{$model}] lỗi (HTTP {$response->status()}): {$errorBody}";
                Log::warning("GeminiService: {$lastError}. Đang tự động chuyển sang model dự phòng tiếp theo...");

            } catch (Exception $e) {
                $lastError = "Model [{$model}] gặp ngoại lệ: " . $e->getMessage();
                Log::warning("GeminiService: {$lastError}. Đang tự động chuyển sang model dự phòng tiếp theo...");
            }
        }

        throw new Exception($lastError ?: 'Gemini không trả về nội dung.');
    }
}
