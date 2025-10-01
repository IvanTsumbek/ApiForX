<?php

namespace App\Http\Controllers\Post;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class IndexController extends Controller
{
    public function index()
    {
        return view('post.index');
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


