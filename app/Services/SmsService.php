<?php

declare(strict_types=1);

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Exception;

class SmsService
{
    /**
     * Send an SMS notification using a bulk SMS gateway configuration.
     *
     * @param string $phone The recipient phone number.
     * @param string $message The SMS message content.
     * @return bool True on success, false on failure.
     */
    public function send(string $phone, string $message): bool
    {
        $apiUrl = config('services.sms.api_url', 'https://api.smsgateway.bd/sendsms');
        $apiKey = config('services.sms.api_key');
        $senderId = config('services.sms.sender_id');

        try {
            // Check for API credentials in production to avoid quiet failures
            if (app()->environment('production') && empty($apiKey)) {
                Log::error("SMS Service: API key is missing from config in production.");
                return false;
            }

            // Post request payload targeting standard BD SMS Gateways
            $response = Http::timeout(10)
                ->post($apiUrl, [
                    'api_key' => $apiKey,
                    'sender_id' => $senderId,
                    'to' => $phone,
                    'message' => $message,
                ]);

            if ($response->successful()) {
                Log::info("SMS successfully dispatched to '{$phone}'. Gateway Response: " . $response->body());
                return true;
            }

            Log::error("SMS Gateway returned error status '{$response->status()}' for recipient '{$phone}'. Body: " . $response->body());
            return false;

        } catch (Exception $e) {
            Log::error("SMS Service encountered exception sending to '{$phone}'. Error: " . $e->getMessage());
            return false;
        }
    }
}
