<?php
/**
 *
 * AppLogin.php
 * User: ChenLong <vip_chenlong@163.com>
 * DateTime: 2020/7/14 10:08
 */


namespace weChat\h5;


use weChat\common\Helper;
use weChat\common\InfoAccessToken;

class Login
{
    private const REQUEST_URL = 'https://api.weixin.qq.com/sns/userinfo?access_token={ACCESS_TOKEN}&openid={OPENID}';

    /**
     * 配置标签
     * @var string
     */
    private $config_tag = '';

    /**
     * Login constructor.
     * @param string $config_tag
     */
    public function __construct(string $config_tag = 'common')
    {
        $this->config_tag = "base.{$config_tag}.";
    }

    /**
     * 获取用户信息
     * @param string $code
     * @return array|bool|mixed|string
     */
    public function getUserInfo(string $code = '')
    {
        $access_token = InfoAccessToken::get($code, $this->config_tag);
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

