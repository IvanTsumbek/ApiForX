<?php

namespace App\Http\Controllers\Post;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class StoreController extends Controller
{
    public function store(Request $request)
    {
        dd($request->all());
    }
}

// public function postTweet(XTokenService $xService)
// {
//     $accessToken = $xService->getAccessToken(auth()->user());

//     $response = Http::withToken($accessToken)->post('https://api.x.com/2/tweets', [
//         'text' => 'Привет, мир!',
//     ]);

//     dd($response->json());
// }