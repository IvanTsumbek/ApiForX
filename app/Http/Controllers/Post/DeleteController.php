<?php

namespace App\Http\Controllers\Post;

use App\Services\XTokenValid;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;

class DeleteController extends Controller
{
    public function __construct(readonly XTokenValid $service) 
    {}

    public function delete($id)
    {
        $token = $this->service->getAccessToken(auth()->user());

        $response = Http::withToken($token->access_token)
            ->delete("https://api.x.com/2/tweets/{$id}");

        if ($response->successful()) {
            session()->flash('status', 'Post deleted successfully');
        } else {
            session()->flash('error', 'Post couldn\'t delete');
        }

        return redirect()->route('index');
    }
}
