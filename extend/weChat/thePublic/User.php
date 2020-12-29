<?php


namespace weChat\thePublic;
use weChat\common\Helper;
use weChat\common\Config;
use weChat\common\AccessToken;

/**
 * 获取用户信息
 * Class User
 * @package app\common\wechat
 */
class User
{
    /**
     * 获取用户信息
     * @param $openid   string  用户的openid
     * @return array|bool|mixed|string
     */
    public function getInfo($openid = '')
    {
        $requestUrl = 'https://api.weixin.qq.com/cgi-bin/user/info?access_token=%s&openid=%s&lang=zh_CN';
        $requestUrl = sprintf($requestUrl, AccessToken::getAccessToken(), $openid);

        $info = Helper::getRequest($requestUrl);

        return empty($info['errcoed']) ? $info : [];
    }
}

