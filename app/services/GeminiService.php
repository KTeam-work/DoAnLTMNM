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
            'gemini-2.5-flash',
            'gemini-1.5-flash',
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

    /**
     * Nhận diện chỉ số từ ảnh công tơ điện hoặc đồng hồ nước.
     */
    public function scanMeterReading(string $imagePath, string $mimeType, ?string $type = null): array
    {
        $apiKey = config('services.gemini.key');
        $configuredModel = config('services.gemini.model') ?: 'gemini-flash-lite-latest';

        if (!$apiKey) {
            throw new Exception('Chưa cấu hình API Key cho chức năng nhận diện.');
        }

        if (!file_exists($imagePath)) {
            throw new Exception('File ảnh không tồn tại.');
        }

        $imageData = base64_encode(file_get_contents($imagePath));

        $typeHint = match ($type) {
            'electricity' => 'Đây là ảnh đồng hồ/công tơ điện (kWh).',
            'water' => 'Đây là ảnh đồng hồ nước (m3).',
            default => 'Hãy tự động xác định xem đây là đồng hồ điện hay đồng hồ nước.',
        };

        $prompt = "Bạn là bộ xử lý OCR chuyên nhận diện chỉ số hiển thị trên mặt đồng hồ công tơ điện và đồng hồ đo nước dân dụng.\n"
            . "{$typeHint}\n"
            . "Nhiệm vụ của bạn: Hãy phân tích kỹ các chữ số cơ học hoặc màn hình điện tử trên mặt đồng hồ đo này và trích xuất chỉ số hiện tại (ví dụ: 1250, 01543.2, 35,...).\n"
            . "- Nếu có phần số màu đỏ (thường là phần thập phân lẻ của đồng hồ), chỉ lấy phần số nguyên hoặc ghi đúng dạng số thực.\n"
            . "- Trả về DUY NHẤT một chuỗi JSON chuẩn có cấu trúc như sau, không kèm bất kỳ giải thích, markdown hay text thừa nào:\n"
            . "{\n"
            . "  \"success\": true,\n"
            . "  \"reading_value\": 1500.5,\n"
            . "  \"meter_type\": \"electricity\",\n"
            . "  \"confidence\": \"high\",\n"
            . "  \"note\": \"Đã nhận diện chỉ số từ công tơ\"\n"
            . "}\n"
            . "- Nếu ảnh không phải là đồng hồ đo hoặc quá mờ không đọc được số, trả về:\n"
            . "{\n"
            . "  \"success\": false,\n"
            . "  \"reading_value\": null,\n"
            . "  \"meter_type\": \"unknown\",\n"
            . "  \"message\": \"Ảnh quá mờ hoặc không nhận diện được mặt số đồng hồ. Vui lòng chụp rõ hơn hoặc nhập tay.\"\n"
            . "}";

        $modelsToTry = array_values(array_unique(array_filter([
            $configuredModel,
            'gemini-flash-lite-latest',
            'gemini-2.5-flash',
            'gemini-1.5-flash',
        ])));

        $lastError = null;

        foreach ($modelsToTry as $model) {
            try {
                $response = Http::timeout(25)
                    ->withHeaders([
                        'x-goog-api-key' => $apiKey,
                        'Content-Type' => 'application/json',
                    ])
                    ->post(
                        "https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent",
                        [
                            'contents' => [
                                [
                                    'role' => 'user',
                                    'parts' => [
                                        [
                                            'inline_data' => [
                                                'mime_type' => $mimeType,
                                                'data' => $imageData,
                                            ],
                                        ],
                                        ['text' => $prompt],
                                    ],
                                ],
                            ],
                            'generationConfig' => [
                                'temperature' => 0.1,
                                'maxOutputTokens' => 200,
                            ],
                        ]
                    );

                if ($response->successful()) {
                    $rawText = $response->json('candidates.0.content.parts.0.text') ?? '';
                    $cleanJson = trim(preg_replace('/^```(?:json)?\s*|\s*```$/i', '', trim($rawText)));
                    $parsed = json_decode($cleanJson, true);

                    if (is_array($parsed)) {
                        if (!empty($parsed['success']) && isset($parsed['reading_value']) && is_numeric($parsed['reading_value'])) {
                            return [
                                'success' => true,
                                'reading_value' => (float) $parsed['reading_value'],
                                'meter_type' => $parsed['meter_type'] ?? ($type ?: 'electricity'),
                                'confidence' => $parsed['confidence'] ?? 'high',
                                'message' => $parsed['note'] ?? ('Đã nhận diện thành công: ' . $parsed['reading_value']),
                            ];
                        }

                        if (isset($parsed['success']) && !$parsed['success']) {
                            return [
                                'success' => false,
                                'message' => $parsed['message'] ?? 'Ảnh không rõ hoặc không nhận diện được mặt số đồng hồ.',
                            ];
                        }
                    }

                    if (preg_match('/(\d+(?:\.\d+)?)/', $cleanJson, $matches)) {
                        return [
                            'success' => true,
                            'reading_value' => (float) $matches[1],
                            'meter_type' => $type ?: 'electricity',
                            'confidence' => 'medium',
                            'message' => 'Đã nhận diện được chỉ số: ' . $matches[1],
                        ];
                    }

                    return [
                        'success' => false,
                        'message' => 'Không nhận diện được số trên mặt đồng hồ đo. Vui lòng thử lại với ảnh rõ hơn.',
                    ];
                }

                $errorBody = $response->body();
                $lastError = "Lỗi nhận diện ({$response->status()}): {$errorBody}";
                Log::warning("GeminiService@scanMeterReading: {$lastError}");
            } catch (Exception $e) {
                $lastError = $e->getMessage();
                Log::warning("GeminiService@scanMeterReading: {$lastError}");
            }
        }

        throw new Exception($lastError ?: 'Không thể kết nối dịch vụ nhận diện ảnh.');
    }
}
