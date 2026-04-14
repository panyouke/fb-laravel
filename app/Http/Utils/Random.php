<?php

namespace App\Http\Utils;

class Random
{
    /**
     * 生成六位随机码
     *
     * @return string
     */
    public static function generatorCode6(): string
    {
        mt_srand();
        return (string)mt_rand(100000, 999999);
    }

    /**
     * 获取16随机字符串.
     *
     * @return false|string
     */
    public static function generator14Character()
    {
        mt_srand();
        $letters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
        $shuffled = str_shuffle($letters);
        return substr($shuffled, 0, 14);
    }


    /**
     * 生成两位随机码
     *
     * @return string
     */
    public static function generatorCode2(): string
    {
        mt_srand();

        return (string)mt_rand(1, 99);
    }

    /**
     * 生成两位随机码
     *
     * @return string
     */
    public static function generatorCode4(): string
    {
        mt_srand();

        return (string)mt_rand(1000, 9999);
    }

    /**
     * 生成雪花id
     *
     * @return int
     */
    public static function generatorSnowFlakeId(): int
    {
        return di(SnowflakeIdGenerator::class)->generate();
    }

    /**
     * 邀请码生成
     *
     * @param int $userId
     * @param int $length
     * @return string
     */
    public static function createReferralCode(int $userId, int $length): string
    {
        $sourceString = env('INVITE_CODE_SOURCE');
        $addString = env('INVITE_CODE_FILL');
        $num = $userId;
        $code = '';
        while ($num * 37 >= 37) {
            $mod = ($num % 37);
            $code = "{$sourceString[$mod]}{$code}";
            $num = $num / 37;
        }
        $codeLen = mb_strlen($code);
        $str = '';
        if ($codeLen < $length) {
            $num = $userId + 100000000;
            for ($i = 0; $i < $length - $codeLen; $i++) {
                $mod = $num % 20 - 1;
                $num = $num / 20;
                $str = "{$addString[$mod]}{$str}";
            }
        }

        return $str . $code;
    }

    /**
     * 解密邀请码
     */
    public static function decryptReferralCode(string $code)
    {
        $sourceString = env('INVITE_CODE_SOURCE');
        $addString = env('INVITE_CODE_FILL');
        $codes = '';
        for ($i = 0; $i < mb_strlen($code); $i++) {
            //如果在这个字符串中，说明是垃圾数据
            if (!strstr($addString, $code[$i])) {
                $codes .= $code[$i];
            }
        }
        $num = 0;
        for ($i = 0; $i < mb_strlen($codes); $i++) {
            $num += (strpos($sourceString, $codes[$i])) * pow(37, mb_strlen($codes) - 1 - $i);
        }
        return $num;
    }

    /**
     * 抽奖算法
     *
     * @param array $proArr
     * @return int
     */
    public static function luckDraw(array $proArr): int
    {
        $result = 0;

        //概率数组的总概率精度
        $proSum = array_sum($proArr);
        mt_srand();
        //概率数组循环
        foreach ($proArr as $key => $proCur) {
            // 获取随机数
            $randNum = mt_rand(1, (int)$proSum);
            if ($randNum <= $proCur) {
                $result = (int)$key;
                break;
            }

            // 减掉当前中奖的概率
            $proSum -= $proCur;
        }
        return $result;
    }
}
