<?php

namespace App\Http\Controllers\Post;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;

class IndexController extends Controller
{
    public function index()
    {
        $user = auth()->user()->xTokens()->latest()->first();


        $response = Http::withToken($user->access_token)
            ->get("https://api.x.com/2/users/{$user->x_user_id}/tweets", [
                'max_results' => 10,
                'tweet.fields' => 'id,text,created_at',
            ]);

        $tweetsResponse = $response->json();
        $tweets = $tweetsResponse['data'] ?? [];
            
        return view('post.index', compact('tweets'));
    }
}
