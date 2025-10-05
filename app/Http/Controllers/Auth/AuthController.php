<?php

namespace App\Http\Controllers\Auth;


use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    public function redirect()
    {
        $state = Str::random(40);
        Session::put('auth_state', $state);

        $codeVerifier = Str::random(128);
        Session::put('auth_code_verifier', $codeVerifier);

        $codeChallenge = rtrim(strtr(
            base64_encode(hash('sha256', $codeVerifier, true)),
            '+/',
            '-_'
        ), '=');

        $query = http_build_query([
            'response_type'         => 'code',
            'client_id'             => config('services.x.client_id'),
            'redirect_uri'          => config('services.x.redirect_uri'),
            'state'                 => $state,
            'scope'                 => 'tweet.read tweet.write users.read media.write offline.access',
            'code_challenge'        => $codeChallenge,
            'code_challenge_method' => 'S256',
        ]);

        return redirect('https://twitter.com/i/oauth2/authorize?' . $query);
    }

    public function callback(Request $request)
    {
        $state = Session::pull('auth_state');
        $codeVerifier = Session::pull('auth_code_verifier');

        if (!$request->has('code') || $request->state !== $state) {
            abort(403, 'Invalid state or code');
        }

        $basicAuth = base64_encode(config('services.x.client_id') . ':' . config('services.x.client_secret'));

        $response = Http::asForm()
            ->withHeaders([
                'Authorization' => "Basic {$basicAuth}",
            ])
            ->post('https://api.twitter.com/2/oauth2/token', [
                'code'          => $request->code,
                'grant_type'    => 'authorization_code',
                'redirect_uri'  => config('services.x.redirect_uri'),
                'code_verifier' => $codeVerifier,
            ]);
        $data = $response->json();
        Log::info('Token response', $data);

        if (!$response->ok() || isset($data['error'])) {
            abort(500, 'Twitter OAuth error: ' . ($data['error_description'] ?? 'Unknown error'));
        }

        $getUserResponse = Http::withToken($data['access_token'])
            ->get('https://api.twitter.com/2/users/me');

        $xUserId = $getUserResponse->json['data']['id'] ?? null;
    
        $user = auth()->user();
        $user->xTokens()->create(
            // ['user_id' => $user->id],
            [
                'user_id' => $user->id,
                'access_token'  => $data['access_token'],
                'refresh_token' => $data['refresh_token'] ?? null,
                'expires_at'    => now()->addSeconds($data['expires_in']),
                'x_user_id'     => $xUserId,
            ]
        );

        return redirect()->route('index')->with('success', 'X account connected successfully!');
    }
}
