<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use App\Models\XToken;

class XTokenValid
{
    protected $clientId;
    protected $clientSecret;

    public function __construct()
    {
        $this->clientId = config('services.x.client_id');
        $this->clientSecret = config('services.x.client_secret');
    }

    public function getAccessToken($user)
    {
        $token = $user->xTokens()->latest()->first();

        if (!$token) {
            throw new \Exception('No X token found for user.');
        }

        // проверяем, истёк ли токен
        if (!$token->expires_at || now()->greaterThan($token->expires_at)) {
            // обновляем токен через refresh_token
            $token = $this->refreshToken($token);
        }

        return $token->access_token;
    }

    protected function refreshToken(XToken $token)
    {
        $response = Http::asForm()->post('https://api.x.com/2/oauth2/token', [
            'grant_type' => 'refresh_token',
            'refresh_token' => $token->refresh_token,
            'client_id' => $this->clientId,
        ]);

        $data = $response->json();

        if (isset($data['error'])) {
            throw new \Exception('Failed to refresh X token: ' . $data['error_description'] ?? $data['error']);
        }

        $token->update([
            'access_token' => $data['access_token'],
            'refresh_token' => $data['refresh_token'],
            'expires_at' => now()->addSeconds($data['expires_in']),
        ]);

        return $token;
    }

    public function getXUserId($user)
{
    $token = $user->xTokens()->latest()->first();
    return $token->x_user_id ?? null;
}
}