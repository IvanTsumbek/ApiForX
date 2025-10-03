<?php

namespace App\Http\Controllers\Post;

use Illuminate\Support\Facades\Http;
use App\Http\Controllers\TweeterAbstractClass;

class DeleteController extends TweeterAbstractClass
{
    public function delete($id)
    {
        $response = Http::withToken($this->token->access_token)
            ->delete("https://api.x.com/2/tweets/{$id}");

        if ($response->successful()) {
            session()->flash('status', 'Post deleted successfully');
        } else {
            session()->flash('error', 'Post couldn\'t delete');
        }

        return redirect()->route('index');
    }
}
