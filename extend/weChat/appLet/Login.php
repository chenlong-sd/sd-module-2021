<?php
/**
 * Date: 2021/3/18 10:40
 * User: chenlong <vip_chenlong@163.com>
 */

namespace weChat\appLet;


use weChat\common\Config;
use weChat\common\Helper;

class Login
{
    /**
     * 获取session_key的路径
     */
    private const URL = 'https://api.weixin.qq.com/sns/jscode2session?appid={APPID}&secret={SECRET}&js_code={JSCODE}&grant_type=authorization_code';

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
     * 获取openid
     * @param string $code
     * @return array|mixed|string
     */
    public function getUserOpenid(string $code)
    {
        $sessionKeyData = $this->getSessionKey($code);
        if (!$sessionKeyData) {
            return [];
        }
        return $sessionKeyData['openid'];
    }

    /**
     * 获取用户信息
     * @param string $code 小程序登录获取的code
     * @param string $rawData 小程序getUserInfo获取的数据
     * @param string $signature 小程序getUserInfo获取的数据
     * @param string $iv 小程序getUserInfo获取的数据
     * @param string $encryptedData 小程序getUserInfo获取的数据
     * @return array|false|string
     */
    public function getUserinfo(string $code, string $rawData, string $signature, string $iv, string $encryptedData)
    {
        $sessionKeyData = $this->getSessionKey($code);
        if (!$sessionKeyData) {
            return [];
        }
        if (sha1($rawData . $sessionKeyData['session_key']) != $signature) {
            Helper::log("小程序登录错误信息：签名错误！");
            return [];
        }
        $sessionKey     = base64_decode($sessionKeyData['session_key']);
        $iv             = base64_decode($iv);
        $encryptedData  = base64_decode($encryptedData);

        $decryptData = openssl_decrypt($encryptedData, 'AES-128-CBC', $sessionKey, 1, $iv);

        if (empty($decryptData)) {
            Helper::log("小程序登录错误信息：解密出错！");
            return [];
        }
        $data = json_decode($decryptData, JSON_UNESCAPED_UNICODE);
        return array_merge($data, $sessionKeyData);
    }

    /**
     * 获取 session_key 小程序登录获取的code
     * @param string $code
     * @return array|bool|mixed|string
     */
    private function getSessionKey(string $code)
    {
        $url = strtr(self::URL, [
            '{APPID}' => Config::get("{$this->config_tag}appId"),
            '{SECRET}' => Config::get("{$this->config_tag}appSecret"),
            '{JSCODE}' => $code,
        ]);
        $result = Helper::getRequest($url);

        if (!empty($result['errcode'])) {
            Helper::log("小程序登录错误信息：{$result['errmsg']}");
            return [];
        }
        return $result;
    }
}
