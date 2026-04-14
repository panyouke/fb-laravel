<?php

namespace App\Http\Service;


use App\Exceptions\ResponseException;
use App\Http\Utils\Lock;
use App\Http\Utils\Random;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Throwable;

class AuthService extends BaseService
{
    protected JwtService $jwtService;

    /**
     * 登录
     *
     * @param $mobile
     * @param $code
     * @return array
     * @throws ResponseException
     * @throws Throwable
     */
    public function login($mobile,$code): array
    {
        if (!(new Lock)->get(Lock::LG, $mobile, 3)) {
            $this->error(100010);
        }
        $exists = $this->redis()->command(
            'BF.EXISTS',
            ['login:accounts',$mobile]
        );

        if ($exists === 0) {
            $this->error(100013);
        }
        $user = User::query()
            ->where('phone', $mobile)
            ->first();
        if (! $user) {
            $this->error(100013);
        }

        $rand = (string) str()->random(32);
        $token = $this->jwtService->fromUser($user, $rand);
        return [
            'token' => $token,
            'user' => $user->toArray(),
        ];
    }

    /**
     * 刷新token
     *
     * @return string
     * @throws ResponseException
     */
    public function refresh(): string
    {
        return $this->jwtService->refresh();
    }

    /**
     * 登出
     *
     * @return void
     * @throws ResponseException
     */
    public function logout(): void
    {
        $this->jwtService->logout();
    }
}
