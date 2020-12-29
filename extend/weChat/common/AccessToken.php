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
     * @return bool|mixed
     */
    public static function getAccessToken()
    {
        //离过期时间大于五分钟，不进行新的 access_token 获取,直接返回
        if ($token = Helper::getValue(Config::get('tokenKey'))) {
            return $token;
        }

        $requestUrl = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=%s&secret=%s';
        $requestUrl = sprintf($requestUrl, Config::get('appId'), Config::get('appSecret'));

        $data = Helper::getRequest($requestUrl);  // 发起获取 access_token 请求

        if (!empty($data['access_token'])) {
            Helper::setValue(Config::get('tokenKey'), $data['access_token'], $data['expires_in'] - 1200); // 保存 access_token
            return Helper::getValue(Config::get('tokenKey'));
        } else {
            Helper::log(json_encode($data)); // 写入错误原因到日志
            return false;
        }
    }
}

