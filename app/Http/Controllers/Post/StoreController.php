<?php

namespace App\Http\Controllers\Post;

use Illuminate\Http\Request;
use App\Services\XTokenValid;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use App\Http\Requests\Post\StoreRequest;
use App\Http\Resources\Post\StoreResource;

class StoreController extends Controller
{
    public function store(StoreRequest $request)
    {
        try {
            $xTokenService = new XTokenValid();
            $accessToken = $xTokenService->getAccessToken(auth()->user());

            $payload = (new StoreResource($request))->resolve();

            $response = Http::withToken($accessToken)->post('https://api.x.com/2/tweets',  $payload);
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
