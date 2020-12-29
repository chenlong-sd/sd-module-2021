<?php
/**
 *
 * AccountParam.php
 * User: ChenLong <vip_chenlong@163.com>
 * DateTime: 2020/7/14 11:40
 */


namespace zfb;


use Alipay\EasySDK\Kernel\Config;

class AccountParam
{

    /**
     * @return CommonOptions
     */
    public static function getOption()
    {
        $options = new CommonOptions();

        $options->app_id = \zfb\Config::get('APPID');
        $options->version = '1.0';
        $options->format = 'JSON';
        $options->sign_type = 'RSA2';
        $options->timestamp = date('Y-m-d H:i:s');
        return $options;
    }


    /**
     * @return Config
     */
    public static function normal()
    {
        $options = new Config();
        $options->protocol = 'https';
        $options->gatewayHost = 'openapi.alipay.com';
        $options->appId = \zfb\Config::get('APPID');
        $options->signType = 'RSA2';
        $options->alipayPublicKey = \zfb\Config::get('public_key');
        $options->merchantPrivateKey = self::getPrivateKey();
        return $options;
    }

    /**
     * @return Config
     */
    public static function cart()
    {
        $options = new Config();
        $options->protocol = 'https';
        $options->gatewayHost = 'openapi.alipay.com';
        $options->appId = \zfb\Config::get('APPID');
        $options->signType = 'RSA2';
        $options->alipayCertPath = \zfb\Config::get('alipayCertPublicKey_RSA');
        $options->alipayRootCertPath = \zfb\Config::get('alipayRootCert');
        $options->merchantCertPath = \zfb\Config::get('appCertPublicKey');
        $options->merchantPrivateKey = self::getPrivateKey();
        return $options;
    }

    /**
     * 获取私钥
     * @param $appId
     * @return mixed
     */
    public static function getPrivateKey()
    {
        return \zfb\Config::get('private_key');
    }
}

