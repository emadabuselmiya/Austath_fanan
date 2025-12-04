<?php

namespace App\CentralLogics;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Throwable;

class NotificationLogic
{
    public static function send_push_notif_to_topic($data, $topic): bool|string|null
    {
        $postData = [
            'message' => [
                'topic' => $topic,
                'data' => [
                    'title' => (string)$data['title'],
                    'body' => (string)$data['description'],
                    'image' => (string)$data['image_url'],
                    'url' => (string)$data['url'],
                ],
                'notification' => [
                    'title' => (string)$data['title'],
                    'body' => (string)$data['description'],
                    'image' => (string)$data['image_url'],
                ]
            ]
        ];

        return self::sendNotificationToHttp($postData);
    }

    public static function send_push_notif_to_device($fcm_token, $data): bool|string|null
    {
        if (!$fcm_token) {
            return false;
        }

        $postData = [
            "message" => [
                "token" => $fcm_token,
                "data" => [
                    'title' => (string)$data['title'],
                    'body' => (string)$data['description'],
                    'image' => (string)$data['image_url'],
                    'url' => (string)$data['url'],
                ],
                "notification" => [
                    "title" => (string)$data['title'],
                    "body" => (string)$data['description'],
                    "image" => (string)$data['image'],
                ]
            ]
        ];

        return self::sendNotificationToHttp($postData);
    }

    protected static function sendNotificationToHttp(array|null $data): bool|string|null
    {
        try {
            if (empty(config('firebase.project_id'))) {
                Log::error('Firebase project_id missing in push_notification_key settings.');
                return false;
            }

            $accessToken = self::getAccessToken();
            $url = 'https://fcm.googleapis.com/v1/projects/' . config('firebase.project_id') . '/messages:send';

            $headers = [
                'Authorization' => 'Bearer ' . $accessToken,
                'Content-Type'  => 'application/json',
            ];

            $response = Http::withHeaders($headers)->post($url, $data);

            if (!$response->successful()) {
                Log::error('FCM send failed', [
                    'status' => $response->status(),
                    'body'   => $response->body(),
                ]);
            }

            return $response->body();
        } catch (Throwable $e) {
            Log::error('Firebase send exception: ' . $e->getMessage());
            return false;
        }
    }

    protected static function getAccessToken(): string
    {
        $jwtToken = [
            'iss' => config('firebase.client_email'),
            'scope' => 'https://www.googleapis.com/auth/firebase.messaging',
            'aud' => 'https://oauth2.googleapis.com/token',
            'exp' => time() + 3600,
            'iat' => time(),
        ];
        $jwtHeader = base64_encode(json_encode(['alg' => 'RS256', 'typ' => 'JWT']));
        $jwtPayload = base64_encode(json_encode($jwtToken));
        $unsignedJwt = $jwtHeader . '.' . $jwtPayload;
        openssl_sign($unsignedJwt, $signature, config('firebase.private_key'), OPENSSL_ALGO_SHA256);
        $jwt = $unsignedJwt . '.' . base64_encode($signature);

        $response = Http::asForm()->post('https://oauth2.googleapis.com/token', [
            'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
            'assertion' => $jwt,
        ]);
        return $response->json('access_token');
    }

}
