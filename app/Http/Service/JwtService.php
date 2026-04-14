<?php

namespace App\Http\Service;

use App\Exceptions\ResponseException;
use App\Models\User;
use Illuminate\Support\Facades\Redis;
use PHPOpenSourceSaver\JWTAuth\Exceptions\JWTException;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

class JwtService
{
    public function fromUser(User $user, string $rand = ''): string
    {
        if ($rand !== '') {
            Redis::connection()->hset('authorization_sso', (string) $user->id, $rand);
        }

        return JWTAuth::claims([
            'rand' => $rand,
        ])->fromUser($user);
    }

    public function user(): ?User
    {
        return auth('api')->user();
    }

    public function id(): ?int
    {
        return auth('api')->id();
    }

    /**
     * @throws ResponseException
     */
    public function refresh(): string
    {
        try {
            return auth('api')->refresh();
        } catch (\Throwable $e) {
            throw new ResponseException(20205, 'errors.token_invalid');
        }
    }

    /**
     * @throws ResponseException
     */
    public function logout(): void
    {
        try {
            auth('api')->logout();
        } catch (\Throwable $e) {
            throw new ResponseException(20205, 'errors.token_invalid');
        }
    }

    /**
     * @throws JWTException
     * @throws ResponseException
     */
    public function checkSso(): void
    {
        $payload = JWTAuth::parseToken()->getPayload();

        $userId = (string) $payload->get('sub');
        $rand = (string) $payload->get('rand', '');

        $cacheRand = (string) Redis::connection()->hget('authorization_sso', $userId);

        if ($rand !== '' && $cacheRand !== $rand) {
            throw new ResponseException(20208, 'errors.token_invalid');
        }
    }

    public function currentPayload()
    {
        return JWTAuth::parseToken()->getPayload();
    }
}
