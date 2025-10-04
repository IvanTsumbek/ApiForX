<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class TokenRefresh extends Command
{

    protected $signature = 'token:refresh';
    protected $description = 'Refresh XToken every 2 hours';
    protected $clientId;
    protected $clientSecret;

    public function __construct()
    { 
        parent::__construct();
        $this->clientId = config('services.x.client_id');
        $this->clientSecret = config('services.x.client_secret');
    }

    public function handle()
    {
        $user = User::find(1);
        $token = $user->xTokens()->latest()->first();
        if (!$user || !$token) {
            Log::error('No user or token found for refresh');
            return Command::FAILURE;
        }
        $basicAuth = base64_encode("{$this->clientId}:{$this->clientSecret}");
        $response = Http::asForm()
            ->withHeaders(['Authorization' => "Basic {$basicAuth}"])
            ->post('https://api.twitter.com/2/oauth2/token', [
                'grant_type'    => 'refresh_token',
                'refresh_token' => $token->refresh_token]);

        $data = $response->json();

        if (!$response->ok() || isset($data['error'])) {
            Log::error('Twitter refresh failed', [
                'status' => $response->status(),
                'body'   => $data,
            ]);

            throw new \Exception('Failed to refresh X token: ' . 
                  ($data['error_description'] ?? $data['error'] ?? 'Unknown error'));
        }

        $token->update([
            'access_token'  => $data['access_token'],
            'refresh_token' => $data['refresh_token'] ?? $token->refresh_token,
            'expires_at'    => now()->addSeconds($data['expires_in']),
        ]);
    }
}
