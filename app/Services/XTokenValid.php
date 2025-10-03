<?php

namespace App\Services;

use App\Models\XToken;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class XTokenValid
{
    protected $clientId;
    protected $clientSecret;

    public function __construct()
    {
        $this->clientId = config('services.x.client_id');
        $this->clientSecret = config('services.x.client_secret');
    }

    /**
     * Undocumented function
     *
     * @param [type] $user
     * @return object|null
     */
    public function getAccessToken($user): object|null
    {
        $token = $user->xTokens()->latest()->first();

        if (!$token) {
            return null;
        }

        if (!$token->expires_at || now()->greaterThan($token->expires_at)) {
            $token = $this->refreshToken($token);
        }
        
        return $token;
    }

    protected function refreshToken(XToken $token)
    {
        $basicAuth = base64_encode("{$this->clientId}:{$this->clientSecret}");

        $response = Http::asForm()
            ->withHeaders([
                'Authorization' => "Basic {$basicAuth}",
            ])
            ->post('https://api.twitter.com/2/oauth2/token', [
                'grant_type'    => 'refresh_token',
                'refresh_token' => $token->refresh_token,
            ]);

        $data = $response->json();

        if (!$response->ok() || isset($data['error'])) {
            Log::error('Twitter refresh failed', [
                'status' => $response->status(),
                'body'   => $data,
            ]);

            throw new \Exception('Failed to refresh X token: ' . ($data['error_description'] ?? $data['error'] ?? 'Unknown error'));
        }

        $token->update([
            'access_token'  => $data['access_token'],
            'refresh_token' => $data['refresh_token'] ?? $token->refresh_token, // если вернули новый
            'expires_at'    => now()->addSeconds($data['expires_in']),
        ]);

        return $token;
    }
}
