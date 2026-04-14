<?php
namespace App\Http\Utils;
use Illuminate\Support\Facades\Redis;

class Lock
{
    // 注册
    public const REG = 'user_reg:';
    // 快熟进入
    public const DEVICE_ID = 'device_id:';
    // 登录
    public const LG = 'user_login:';
    // 重置密码
    public const RP = 'user_reset_password:';
    // 存储token随机值
    public const AUTHORIZATION_SSO = 'user:token';
    // token过期时间 7天
    public  const AUTHORIZATION_EXPIRE = 604800;
    // token 续期时间/3天
    public const AUTHORIZATION_RENEW = 259200;

    protected $redis;

    public function __construct()
    {
        $this->redis = Redis::connection();
    }

    /**
     * @param string $prefix
     * @param string $key
     * @param int $ttl
     * @return bool
     */
    public function get(string $prefix, string $key, int $ttl = 5): bool
    {
        $key = $prefix . ':' . trim($key);

        $result = $this->redis->set($key, 1, 'EX', $ttl, 'NX');
        return $result === true || $result === 'OK' || $result === 1;
    }

}
