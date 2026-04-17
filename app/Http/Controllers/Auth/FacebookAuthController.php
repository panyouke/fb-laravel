<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redis;
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
                'ads_management',
//                'pages_show_list',       // 必须：显示用户管理的主页列表
//                'pages_read_engagement', // 读取主页数据（阅读帖子等）
                'pages_manage_posts',    // 核心：允许你的应用帮主页发帖！
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


        // ... 前面 Socialite 获取 $accessToken 的代码 ...

        try {
            // 请求 /me/accounts 来获取公共主页列表和主页的 Token
            $response = $this->guzzle->get('https://graph.facebook.com/v19.0/me/accounts', [
                'query' => [
                    'access_token' => $accessToken, // 这里用的是用户 Token
                    // fields 明确要求返回主页名称、ID、以及最关键的 access_token
                    'fields'       => 'name,id,access_token,category,tasks'
                ],
                'timeout' => 10.0,
            ]);

            $data = json_decode($response->getBody()->getContents(), true);
            $pages = $data['data'] ?? [];

            if (count($pages) > 0) {
                // 拿到了主页列表！
                // 比如取出第一个主页的信息
                $firstPage = $pages[0];
                $pageId = $firstPage['id'];

                // ⚠️ 这是极其宝贵的 Page Token，以后操作这个主页全靠它！
                $pageAccessToken = $firstPage['access_token'];

                // 【建议操作】：把 $pageId 和 $pageAccessToken 存入你的数据库
                // ...
                foreach ($pages as $page) {
                    $redisKey = "fb:page:token:{$page['id']}";
                    Redis::setex($redisKey, 2592000, $page['access_token']);
                }

                // 存完后跳转回发帖页面
//                return redirect('/facebook/page/publish')->with('success', '所有主页授权已更新，可以开始发帖了！');
                dd('成功获取主页 Token！', $pageId, $pageAccessToken);
            } else {
                return redirect('/')->with('error', '该用户没有管理任何公共主页');
            }

        } catch (\Exception $e) {
            // 错误处理逻辑...
        }

        // 2. 请求 Graph API 获取该用户绑定的广告账户列表
        // 注意：替换为你使用的对应 API 版本，比如 v19.0
//        $response = $this->guzzle->get('https://graph.facebook.com/v19.0/me/adaccounts', [
//            'query' => [
//                'access_token' => $accessToken,
//                'fields'       => 'account_id,name,account_status'
//            ]
//        ]);
//
//        $body = $response->getBody()->getContents();
//
//        $data = json_decode($body, true);
//
//        $adAccounts = $data['data'] ?? [];
//
//        if (count($adAccounts) > 0) {
//            // 3. 如果有广告账户，取出第一个（或者根据你的业务逻辑选一个）
//            // 注意：Graph API 返回的 id 通常带有 'act_' 前缀 (例如 'act_123456789')
//            // account_id 字段通常是纯数字，可以直接用
//            $adAccountId = $adAccounts[0]['account_id'];
//
//            return redirect()->away("https://adsmanager.facebook.com/adsmanager/manage/campaigns?act={$adAccountId}");
//        }
//
//        // 4. 如果该用户没有广告账户，重定向到你的系统首页或提示页
//        return redirect('/')->with('error', '未找到关联的广告账户');

//        return redirect('/');
//        return redirect()->away("https://adsmanager.facebook.com/adsmanager/manage/campaigns?act={$adAccountId}");
    }
}
