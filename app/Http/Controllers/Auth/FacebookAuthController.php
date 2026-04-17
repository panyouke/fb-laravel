<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class FacebookAuthController extends Controller
{
    public function redirect()
    {
        return Socialite::driver('facebook')
            ->stateless()
            ->setScopes([
                'public_profile',
                'email',
                'pages_show_list',
                'pages_read_engagement',
                'business_management',
                'ads_read',
                'ads_management'
            ])
            ->with(['auth_type' => 'rerequest'])
            ->redirect();
    }

    public function callback()
    {
        $facebookUser = Socialite::driver('facebook')
            ->stateless()
            ->setScopes([])
            ->fields(['id', 'name'])
            ->user();

        $user = User::query()
            ->where('facebook_id', $facebookUser->getId())
            ->first();

        if (! $user) {
            $user = User::create([
                'name' => $facebookUser->getName() ?: 'Facebook User',
                // 不拿 email，就给一个占位邮箱，避免数据库 not null / unique 问题
                'email' => 'fb_' . $facebookUser->getId() . '@malapan.local',
                'facebook_id' => $facebookUser->getId(),
                'password' => bcrypt(Str::random(32)),
            ]);
        } else {
            if (! $user->facebook_id) {
                $user->facebook_id = $facebookUser->getId();
                $user->save();
            }
        }

        Auth::login($user);

        // 1. 获取用户的访问令牌 (Access Token)
        $accessToken = $facebookUser->token;

        // 2. 请求 Graph API 获取该用户绑定的广告账户列表
        // 注意：替换为你使用的对应 API 版本，比如 v19.0
        $response = $this->guzzle->get('https://graph.facebook.com/v19.0/me/adaccounts', [
            'query' => [
                'access_token' => $accessToken,
                'fields'       => 'account_id,name,account_status'
            ]
        ]);

        $adAccounts = $response->json('data') ?? [];

        if (count($adAccounts) > 0) {
            // 3. 如果有广告账户，取出第一个（或者根据你的业务逻辑选一个）
            // 注意：Graph API 返回的 id 通常带有 'act_' 前缀 (例如 'act_123456789')
            // account_id 字段通常是纯数字，可以直接用
            $adAccountId = $adAccounts[0]['account_id'];

            return redirect()->away("https://adsmanager.facebook.com/adsmanager/manage/campaigns?act={$adAccountId}");
        }

        // 4. 如果该用户没有广告账户，重定向到你的系统首页或提示页
        return redirect('/')->with('error', '未找到关联的广告账户');

//        return redirect('/');
//        return redirect()->away("https://adsmanager.facebook.com/adsmanager/manage/campaigns?act={$adAccountId}");
    }
}
