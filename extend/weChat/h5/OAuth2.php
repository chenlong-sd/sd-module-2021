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
    public static function getAuthUrl(string $url, string $type = self::SNS_API_BASE, string $param = 'STATE'): string
    {
        return sprintf(self::AUTH_URL, Config::get('appId'), urlencode($url), $type, $param);
    }

    /**
     * @param string $url
     * @param string $param
     * @param string $type
     * @return string
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/6/17
     */
    public function getOAuth2Url(string $url, string $param = 'STATE', string $type = self::SNS_API_USER_INFO): string
    {
        return sprintf(self::AUTH_URL, Config::get('appId'), urlencode($url), $type, $param);
    }
}

