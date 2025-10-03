<?php

namespace App\Http\Controllers\Post;

use App\Http\Controllers\Controller;
use App\Services\XTokenValid;
use Illuminate\Support\Facades\Http;

class IndexController extends Controller
{
    public function __construct(readonly XTokenValid $service)
    {}

    public function index()
    {
        $token = $this->service->getAccessToken(auth()->user());

        if (!$token) {
            return redirect()->route('redirect')->with('error', 'Авторизуйтесь через X, чтобы получить доступ.');
        }

        $response = Http::withToken( $token->access_token)
            ->get("https://api.x.com/2/users/{$token->x_user_id}/tweets", [
                'max_results' => 10,
                'tweet.fields' => 'id,text,created_at',
            ]);

        $tweetsResponse = $response->json();
        $tweets = $tweetsResponse['data'] ?? [];
            
        return view('post.index', compact('tweets'));
    }
}
