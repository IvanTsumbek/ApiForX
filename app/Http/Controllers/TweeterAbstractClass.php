<?php

namespace App\Http\Controllers;

use App\Services\XTokenValid;
use App\Http\Controllers\Controller;

abstract class TweeterAbstractClass extends Controller
{
    protected $token;

    public function __construct(readonly XTokenValid $service)
    {
        $this->token = $this->service->getAccessToken(auth()->user());
    }
}