<?php

namespace App\Jobs;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class TweetCreateJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private string $accessToken;
    private array $payload;

    public function __construct(string $access_token, array $payload)
    {
        $this->accessToken = $access_token;
        $this->payload = $payload;
    }

    public function handle(): void
    {
        try {

            $response = Http::withToken( $this->accessToken)->post('https://api.x.com/2/tweets',  $this->payload);
            if ($response->failed()) {
                Log::channel('postCreate')->error('Ошибка от X API', [
                    'status' => $response->status(),
                    'body'   => $response->body(),
                ]);
            }
        } catch (\Exception $e) {
            Log::channel('postCreate')->error('Исключение при создании твита', [
                'message' => $e->getMessage(),
                'trace'   => $e->getTraceAsString(),
            ]);
        }
    }
}
