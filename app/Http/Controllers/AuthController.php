<?php

namespace App\Http\Controllers;

use App\Constants\Constants;
use App\Http\Service\AuthService;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    protected AuthService $service;
    public function login(Request $request)
    {
        $mobile = trim($request->input('mobile'));
        $code = trim($request->input('code'));
        $res = $this->service->login($mobile, $code);

        return response()->json([
            'code' => 200,
            'message' => __('auth.login_success'),
            'data' => [
                'token' => $res['token'],
                'token_type' => 'bearer',
                'expires_in' => auth('api')->factory()->getTTL() * 60,
                'user' => $res['user'],
            ],
        ]);
    }

    public function refresh()
    {
        $token = $this->service->refresh();

        return response()->json([
            'code' => 200,
            'message' => __('auth.refresh_success'),
            'data' => [
                'token' => $token,
                'token_type' => 'bearer',
                'expires_in' => auth('api')->factory()->getTTL() * 60,
            ],
        ]);
    }

    public function logout()
    {
        $this->service->logout();
        return response()->json([
            'code' => 200,
            'message' => __('auth.logout'),
            'data' => [],
        ]);
    }

}
