<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
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
        dump($request);
    }
}
