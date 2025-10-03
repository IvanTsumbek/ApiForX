<?php

namespace App\Http\Controllers\Post;

use App\Http\Controllers\TweeterAbstractClass;
use Illuminate\Support\Facades\Http;

class IndexController extends TweeterAbstractClass
{

    public function index()
    {
        if (!$this->token) {
            return redirect()->route('redirect')->with('error', 'Авторизуйтесь через X, чтобы получить доступ.');
        }

        $response = Http::withToken( $this->token->access_token)
            ->get("https://api.x.com/2/users/{$this->token->x_user_id}/tweets", [
                'max_results' => 10,
                'tweet.fields' => 'id,text,created_at',
            ]);

        $tweetsResponse = $response->json();
        $tweets = $tweetsResponse['data'] ?? [];
            
        return view('post.index', compact('tweets'));
    }
}
