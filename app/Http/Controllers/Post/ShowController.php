<?php

namespace App\Http\Controllers\Post;

use App\Http\Controllers\TweeterAbstractClass;
use Illuminate\Support\Facades\Http;

class ShowController extends TweeterAbstractClass
{
    public function show($id)
    {

        $response = Http::withToken($this->token->access_token)
            ->get("https://api.x.com/2/tweets/{$id}");

        $tweet = $response->json()['data'] ?? [];
            
        return view('post.show', compact('tweet'));
    }
}
