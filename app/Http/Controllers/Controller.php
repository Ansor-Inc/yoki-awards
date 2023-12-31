<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests;
    use DispatchesJobs;
    use ValidatesRequests;

    protected function success(array $payload = [])
    {
        $response = array_merge(['success' => true], $payload ? ['data' => $payload] : []);
        return response($response);
    }

    protected function failed(): Response|Application|ResponseFactory
    {
        return response(['message' => 'Something went wrong!'], 500);
    }
}
