<?php

namespace App\Http\Service;

use App\Exceptions\ResponseException;
use App\Models\User;
use Illuminate\Redis\Connections\Connection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redis;
class BaseService
{
    protected function redis(): Connection
    {
        return Redis::connection();
    }

    /**
     * @throws ResponseException
     */
    protected function error(
        int $code = 400,
        string $msg = '',
    ): never {
        if ($code === 0) {
            $code = 400;
        }
        throw new ResponseException($code, $msg);
    }

    public function getUser(): ?User
    {
        $user = Auth::guard('api')->user();

        return $user instanceof User ? $user : null;
    }

    public function getUserId(): ?int
    {
        return Auth::guard('api')->id();
    }
}
