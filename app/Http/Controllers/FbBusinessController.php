<?php

namespace App\Http\Controllers;

use App\Models\FbBms;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\ServerException;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis; // ⚠️ 引入 Redis 门面

class FbBusinessController extends Controller
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
        $version = config('services.facebook.api_version');
        try {
            $endpoint = $imageUrl
                ? "https://graph.facebook.com/{$version}/{$pageId}/photos"
                : "https://graph.facebook.com/{$version}/{$pageId}/feed";
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

    public function showInviteForm()
    {
        // 从数据库捞出你之前创建的 fb_bms 表里的所有记录
        $bms = FbBms::whereNull('deleted_at')->get();

        return view('invite', compact('bms'));
    }

    public function processInvite(Request $request)
    {
        $request->validate([
            'bm_internal_id' => 'required|exists:fb_bms,id',
            'email' => 'required|email',
            'role' => 'required|in:ADMIN,EMPLOYEE',
        ]);
        $bm = FbBms::findOrFail((int)$request->bm_internal_id);
        if (!$bm->manager_token) {
            return back()->with('error', '该 BM 记录缺少管理 Token，请先在后台配置。');
        }

        $result = $this->inviteUserToBm(
            $bm->manager_token,
            $request->email,
            $request->role
        );

        if (isset($result['id']) || isset($result['data'])) {
            return back()->with('success', "邀请已成功发送至：{$request->email}");
        }

        $errorMsg = $result['error']['message'] ?? '未知错误，请检查日志';
        return back()->with('error', '发送失败：' . $errorMsg);
    }

    public function inviteUserToBm($token, $email, $role = 'EMPLOYEE')
    {
        $version = config('services.facebook.api_version');
        $businessId = config('services.facebook.business_id');

        $uri = "https://graph.facebook.com/{$version}/{$businessId}/business_users";
        try {
            $response = $this->guzzle->post($uri, [
                'form_params' => [
                    'access_token' => $token,
                    'email'        => $email,
                    'role'         => $role,
                ],
                'timeout' => 15,
            ]);

            $data = json_decode($response->getBody()->getContents(), true);
            dd([
                'success' => true,
                'status'  => $response->getStatusCode(),
                'body'    => $response->getBody()->getContents(),
            ]);
            // Facebook 成功返回通常包含 invite ID
            if (isset($data['data']) || isset($data['id'])) {
                return $data;
            }

            return false;

        } catch (ClientException $e) {
            // 捕获 400 级别的错误（如 Token 过期、参数错误、邮箱格式不对）
            $response = $e->getResponse();
            $errorBody = json_decode($response->getBody()->getContents(), true);

            Log::error('FB邀请接口客户端错误 (4xx):', [
                'status' => $response->getStatusCode(),
                'error'  => $errorBody,
                'email'  => $email
            ]);
            return $errorBody; // 返回错误信息给调用方展示

        } catch (ServerException $e) {
            // 捕获 500 级别的错误（Facebook 服务器出问题了）
            Log::error('FB服务器故障 (5xx): ' . $e->getMessage());
            return ['error' => 'Facebook 服务器暂时不可用'];

        } catch (ConnectException $e) {
            // 捕获网络连接错误（超时、DNS解析失败等）
            Log::error('FB连接超时/网络异常: ' . $e->getMessage());
            return ['error' => '连接 Facebook 超时，请稍后再试'];

        } catch (\Exception $e) {
            // 捕获其他未知代码异常
            Log::error('FB邀请系统异常: ' . $e->getMessage());
            return ['error' => '系统错误，请检查日志'];
        }
    }

}
