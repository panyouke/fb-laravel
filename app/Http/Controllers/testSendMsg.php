<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class testSendMsg extends Controller
{
    protected $guzzle;

    public function __construct(Client $guzzle)
    {
        $this->guzzle = $guzzle;
    }

    // 1. 显示表单页面
    public function showForm()
    {
        // 这里模拟你从数据库或 API 拿到主页列表
        // 实际开发中，你应该从数据库查出当前登录用户绑定的主页列表
        $userPages = [
            [
                'id' => '你的真实_PAGE_ID',
                'name' => '我的测试主页',
                'access_token' => '你的真实_PAGE_ACCESS_TOKEN'
            ]
        ];

        // 渲染 blade 模板，并把数据传过去
        return view('publish', compact('userPages'));
    }

    // 2. 接收表单提交
    public function sendPost(Request $request)
    {
        // 验证前端传来的数据
        $request->validate([
            'page_data' => 'required|string',
            'message'   => 'required|string|max:2000',
        ]);

        // 解析前端传过来的 ID 和 Token (通过 | 分割)
        $pageData = explode('|', $request->input('page_data'));

        if (count($pageData) !== 2) {
            return back()->with('error', '主页数据格式不正确');
        }

        $pageId = $pageData[0];
        $pageAccessToken = $pageData[1];
        $message = $request->input('message');

        try {
            // 调用发帖方法
            $result = $this->publishToPage($pageId, $pageAccessToken, $message);

            // ⚠️ 成功后，重定向回之前的页面，并带上成功提示
            return back()->with('success', '帖子发布成功！新帖子的 ID 是: ' . ($result['id'] ?? '未知'));

        } catch (\Exception $e) {
            Log::error('发布主页帖子失败: ' . $e->getMessage());
            // 失败后，重定向回之前的页面，并带上错误提示
            return back()->with('error', '发布失败：' . $e->getMessage());
        }
    }

    // 3. 实际调用 Facebook API (保持不变)
    protected function publishToPage($pageId, $pageAccessToken, $message)
    {
        $response = $this->guzzle->post("https://graph.facebook.com/v19.0/{$pageId}/feed", [
            'form_params' => [
                'message'      => $message,
                'access_token' => $pageAccessToken
            ]
        ]);

        return json_decode($response->getBody()->getContents(), true);
    }
}
