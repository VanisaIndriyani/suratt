<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Throwable;

class WhatsappService
{
    public function send(string $toPhone, string $message): bool
    {
        $url = (string) config('services.whatsapp.url', '');
        $token = (string) config('services.whatsapp.token', '');

        if ($url === '' || $token === '' || $toPhone === '' || $message === '') {
            return false;
        }

        try {
            $isFonnte = str_contains(strtolower($url), 'fonnte.com');

            $http = Http::timeout(15);
            if ($isFonnte) {
                $http = $http->withHeaders([
                    'Authorization' => $token,
                ]);
            } else {
                $http = $http->withToken($token);
            }

            $payload = $isFonnte
                ? ['target' => $toPhone, 'message' => $message]
                : ['to' => $toPhone, 'message' => $message];

            $response = $http->post($url, $payload);

            return $response->successful();
        } catch (Throwable) {
            return false;
        }
    }
}
