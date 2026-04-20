<?php

namespace App\Http\Controllers;

use GuzzleHttp\Exception\ClientException;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis; // ⚠️ 引入 Redis 门面

class TestSendMsg extends Controller
{

    // 1. 显示表单页面
    public function showForm()
    {
        // 模拟数据：现在只需要给前端 id 和 name，不需要暴露 token 了
        $userPages = [
            [
                'id'   => '1121873197667743',
                'name' => '我的测试主页',
            ]
        ];

        // 渲染 blade 模板
        return view('publish', compact('userPages'));
    }

    // 2. 接收表单提交
    public function sendPost(Request $request)
    {
        // 1. 验证数据：现在只接收 page_id 和 message
        $request->validate([
            'page_id' => 'required|string',
            'message' => 'required|string|max:2000',
        ]);

        $pageId = $request->input('page_id');
        $message = $request->input('message');

        // 2. 核心：直接去 Redis 里面捞 Token！
        $redisKey = "fb:page:token:{$pageId}";
        $pageAccessToken = Redis::get($redisKey);
        var_dump($redisKey);
        var_dump($pageAccessToken);
        // 3. 拦截检查：如果在 Redis 没找到，说明没授权或者过期了
        if (!$pageAccessToken) {
            return back()->with('error', '主页授权已过期或未找到，请重新点击 Facebook 登录授权。');
        }

        try {
            // 4. 拿着取出来的 Token 去发帖
            $result = $this->publishPost($pageId, $pageAccessToken, $message);

            // 成功后返回
            return back()->with('success', '帖子发布成功！新帖子的 ID 是: ' . ($result['id'] ?? '未知'));

        } catch (\Exception $e) {
            Log::error('发布主页帖子失败: ' . $e->getMessage());
            return back()->with('error', '发布失败：' . $e->getMessage());
        }
    }

    /**
     * 发布帖子（支持纯文字或图文）
     */
    protected function publishPost($pageId, $pageAccessToken, $message, $imageUrl = null)
    {
        try {
            $endpoint = $imageUrl
                ? "https://graph.facebook.com/v19.0/{$pageId}/photos"
                : "https://graph.facebook.com/v19.0/{$pageId}/feed";
            $params = [
                'access_token' => $pageAccessToken,
            ];
            if ($imageUrl) {
                $params['url'] = $imageUrl;
                $params['caption'] = $message;
            } else {
                $params['message'] = $message;
            }
            $response = $this->guzzle->post($endpoint, [
                'form_params' => $params,
                'timeout'     => 15,
            ]);

            return json_decode($response->getBody()->getContents(), true);

        } catch (ClientException $e) {
            $error = json_decode($e->getResponse()->getBody()->getContents(), true);
            Log::error("FB发布失败", ['error' => $error, 'page_id' => $pageId]);
            return $error;
        } catch (\Exception $e) {
            Log::error("FB系统错误: " . $e->getMessage());
            return ['error' => $e->getMessage()];
        }
    }

}
