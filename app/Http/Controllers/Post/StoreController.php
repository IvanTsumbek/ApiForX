<?php

namespace App\Http\Controllers\Post;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use App\Http\Requests\Post\StoreRequest;
use App\Http\Resources\Post\StoreResource;
use App\Http\Controllers\TweeterAbstractClass;

class StoreController extends TweeterAbstractClass
{
    public function store(StoreRequest $request)
    {

        try {
            $payload = (new StoreResource($request))->resolve();

            $response = Http::withToken($this->token->access_token)->post('https://api.x.com/2/tweets',  $payload);
            if ($response->failed()) {
                Log::channel('postCreate')->error('Ошибка от X API', [
                    'status' => $response->status(),
                    'body'   => $response->body(),
                ]);

                return redirect()->route('index')->with('error', 'Не удалось опубликовать твит, попробуйте позже.');
            }

            return redirect()->route('index')->with('status', 'Твит опубликован!');
        } catch (\Exception $e) {
            Log::channel('postCreate')->error('Исключение при создании твита', [
                'message' => $e->getMessage(),
                'trace'   => $e->getTraceAsString(),
            ]);

            return redirect()->route('index')->with('error', 'Ошибка при публикации твита: ' . $e->getMessage());
        }
    }
}
