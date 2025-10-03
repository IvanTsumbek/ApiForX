<?php

namespace App\Http\Controllers\Post;

use Illuminate\Http\Request;
use App\Services\XTokenValid;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;

class ShowController extends Controller
{
    public function __construct(readonly XTokenValid $service)
    {}

    public function show($id)
    {
        $token = $this->service->getAccessToken(auth()->user());

        $response = Http::withToken($token->access_token)
            ->get("https://api.x.com/2/tweets/{$id}");

        $tweetResponse = $response->json();
        $tweet = $tweetResponse['data'] ?? [];
            
        return view('post.show', compact('tweet'));
    }
}
