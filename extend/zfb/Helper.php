<?php
/**
 *
 * Helper.php
 * User: ChenLong <vip_chenlong@163.com>
 * DateTime: 2020/7/20 15:45
 */


namespace zfb;


class Helper
{

    /**
     * 签名字符串生成
     * @param array $data
     * @return string
     */
    private static function valueConstruct(array $data)
    {
        if ($data['sign']) unset($data['sign']);
        $data = self::filter($data);
        ksort($data);

        $signArr = [];

        foreach ($data as $k => $v) {
            $v = is_array($v) ? json_encode($v, JSON_UNESCAPED_UNICODE) : $v;
            $signArr[] = "{$k}={$v}";
        }
        return implode('&', $signArr);
    }

    /**
     * 空值过滤
     * @param $data
     * @return mixed
     */
    public static function filter($data)
    {
        foreach ($data as $k => $v) {
            is_array($v) ? $v = self::filter($v) : $v = trim($v);
            if (empty($v) && $v !== 0) unset($data[$k]);
            else $data[$k] = self::character($v, $data['charset'] ?? Config::get('utf8'));
        }
        return $data;
    }

    /**
     * 签名
     * @param     $data
     * @param int $algo
     * @return string
     */
    public static function sign($data, $algo = OPENSSL_ALGO_SHA256)
    {
        $signStr = self::valueConstruct($data);

        $private = implode("\n", ['-----BEGIN RSA PRIVATE KEY-----', Config::get('private_key'), '-----END RSA PRIVATE KEY-----']);

        $algo === OPENSSL_ALGO_SHA256
            ? openssl_sign($signStr, $signature, $private, version_compare(PHP_VERSION,'5.4.0', '<') ? SHA256 : OPENSSL_ALGO_SHA256)
            : openssl_sign($signStr, $signature, $private);

        return base64_encode($signature);
    }


    /**
     * 设置编码
     * @param $data
     * @param $targetCharset
     * @return bool|false|string|string[]|null
     */
    public static function character($data, $targetCharset)
    {
        if (!empty($data)) {
            $fileType = Config::get('default_charset');
            if (strcasecmp($fileType, $targetCharset) != 0) {
                $data = mb_convert_encoding($data, $targetCharset, $fileType);
            }
        }
        return $data;
    }

    /**
     * 生成订单号
     * @param string $uid
     * @return string
     */
    public static function outTradeNoGenerate($uid = 'N')
    {
        return $uid . date('YmdHis') . mt_rand(10000, 99999);
    }
}

