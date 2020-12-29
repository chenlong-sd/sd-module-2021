<?php


namespace weChat\h5;
use weChat\common\Config;

/**
 * 公众号网页授权
 * Class OAuth2
 * @package app\common\wechat
 */
class OAuth2
{
    /** @var string  获取openid*/
    const SNS_API_BASE = 'snsapi_base';

    /** @var string 获取用户信息 */
    const SNS_API_USER_INFO = 'snsapi_userinfo';

    const AUTH_URL = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid=%s&redirect_uri=%s&response_type=code&scope=%s&state=%s#wechat_redirect';

    /**
     * 用户同意授权页面，获取code的地址获取
     * @param string $url   跳转的路径
     * @param string $type  snsapi_base (获取openid) | snsapi_userinfo （获取用户信息）
     * @param string $param 参数 （支持数字和字母）
     * @return string
     */
    public static function getAuthUrl($url, $type = self::SNS_API_BASE, $param = 'STATE')
    {
        return sprintf(self::AUTH_URL, Config::get('appId'), urlencode($url), $type, $param);
    }

    /**
     * @param $url
     * @param string $param
     * @param string $type
     * @return string
     */
    public function getOAuth2Url($url, $param = 'STATE', $type = self::SNS_API_USER_INFO)
    {
        return sprintf(self::AUTH_URL, Config::get('appId'), urlencode($url), $type, $param);
    }
}

