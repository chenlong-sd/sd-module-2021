<?php
/**
 *
 * AppLogin.php
 * User: ChenLong <vip_chenlong@163.com>
 * DateTime: 2020/7/14 10:08
 */


namespace weChat\common;


class Login
{
    private const REQUEST_URL = 'https://api.weixin.qq.com/sns/userinfo?access_token={ACCESS_TOKEN}&openid={OPENID}';

    /**
     * 获取用户信息
     * @param string $code
     * @return array|bool|mixed|string
     */
    public static function getUserInfo(string $code = '')
    {
        $access_token = InfoAccessToken::get($code);
        if (!$access_token) return [];

        $user_info = Helper::getRequest(strtr(self::REQUEST_URL, [
            '{ACCESS_TOKEN}' => $access_token['access_token'],
            '{OPENID}' => $access_token['openid']
        ]));

        if ($user_info) return $user_info;
        Helper::log(json_encode($user_info, JSON_UNESCAPED_UNICODE));
        return [];
    }
}

