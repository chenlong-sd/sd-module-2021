<?php


namespace weChat\common;

/**
 * access_token 获取并缓存
 * Trait AccessToken
 * @package app\common\wechat
 */
trait AccessToken
{
    /**
     * @param string $config_tag
     * @return bool|mixed
     */
    public static function getAccessToken(string $config_tag = 'common')
    {
        //离过期时间大于五分钟，不进行新的 access_token 获取,直接返回
        if ($token = Helper::getValue($config_tag . Config::get('tokenKey'))) {
            return $token;
        }

        $requestUrl = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=%s&secret=%s';
        $config_tag = "base.{$config_tag}.";
        $requestUrl = sprintf($requestUrl, Config::get("{$config_tag}appId"), Config::get("{$config_tag}appSecret"));
        $data = Helper::getRequest($requestUrl);  // 发起获取 access_token 请求

        if (!empty($data['access_token'])) {
            Helper::setValue($config_tag . Config::get('tokenKey'), $data['access_token'], $data['expires_in'] - 1200); // 保存 access_token
            return Helper::getValue($config_tag . Config::get('tokenKey'));
        } else {
            Helper::log(json_encode($data)); // 写入错误原因到日志
            return false;
        }
    }
}

