<?php
/**
 *
 * AccessToken.php
 * User: ChenLong <vip_chenlong@163.com>
 * DateTime: 2020/7/14 10:09
 */


namespace weChat\common;

/**
 * 获取用户信息的 access_token
 * Class InfoAccessToken
 * @package weChat\common
 */
class InfoAccessToken
{
    private const REQUEST_URL = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid={APPID}&secret={SECRET}&code={CODE}&grant_type=authorization_code';

    /**
     * 获取 access_token
     * @param string $code
     * @param string $config_tag
     * @return bool|mixed|string
     */
    public static function get(string $code, string $config_tag)
    {
        $access_token = Helper::getRequest(strtr(self::REQUEST_URL, [
            '{APPID}' => Config::get("{$config_tag}appId"),
            '{SECRET}' => Config::get("{$config_tag}appSecret"),
            '{CODE}' => $code
        ]));

        if (empty($access_token['errcode'])) return $access_token;
        Helper::log(json_encode($access_token, JSON_UNESCAPED_UNICODE));
        return false;
    }

}

