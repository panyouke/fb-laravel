<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use GuzzleHttp\Client;

abstract class Controller
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected $guzzle;

    // 3. 在基类构造函数中注入
    public function __construct(Client $guzzle)
    {
        $this->guzzle = $guzzle;
    }
}
