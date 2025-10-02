<?php

namespace App\Http\Controllers\Post;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;

class DeleteController extends Controller
{
    public function delete($id)
    {
        $user = auth()->user()->xTokens()->latest()->first();


        $response = Http::withToken($user->access_token)
            ->delete("https://api.x.com/2/tweets/{$id}");

        if($response->successful()) {
            session()->flash('status', 'Post deleted successfully');
        } else {
            session()->flash('error', 'Post couldn\'t delete');
        }

        return redirect()->route('index');
    }
}
