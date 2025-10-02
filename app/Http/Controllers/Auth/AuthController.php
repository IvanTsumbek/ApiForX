<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
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
            'response_type' => 'code',
            'client_id' => config('services.x.client_id'),
            'redirect_uri' => config('services.x.redirect_uri'),
            'state' => $state,
            'scope' => 'tweet.read tweet.write users.read offline.access',
            'code_challenge' =>  $codeChallenge,
            'code_challenge_method' => 'S256',
        ]);

        return redirect('https://x.com/i/oauth2/authorize?' . $query);
    }

    public function callback(Request $request)
    {
        $state = Session::pull('auth_state');
        $codeVerifier = Session::pull('auth_code_verifier');
        if (!$request->has('code') || $request->state !== $state) {
            abort(403, 'Invalid state or code');
        }

        $basicAuth = base64_encode(
            config('services.x.client_id') . ':' . config('services.x.client_secret')
        );

        $query = [
            'code' => $request->code,
            'grant_type' => 'authorization_code',
            'client_id' => config('services.x.client_id'),
            'redirect_uri' => config('services.x.redirect_uri'),
            'code_verifier' => $codeVerifier,
        ];
        $response = Http::asForm()
            ->withHeaders(['Authorization' => 'Basic ' . $basicAuth])
            ->post('https://api.x.com/2/oauth2/token', $query);

        $data = $response->json();
        $user = auth()->user();
        $response2 = Http::withToken($data['access_token'])
            ->get('https://api.x.com/2/users/me');
        $userData = $response2->json();
        $xUserId = $userData['data']['id'] ?? null;
        $token = $user->xTokens()->updateOrCreate(
            ['user_id' => $user->id],
            [
                'access_token' => $data['access_token'],
                'refresh_token' => $data['refresh_token'],
                'expires_at'   => now()->addSeconds($data['expires_in']),
                'x_user_id'    => $xUserId,
            ]
        );

        return redirect()->route('index');
    }
}
