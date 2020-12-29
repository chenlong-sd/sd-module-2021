<?php
/**
 *
 * Auth.php
 * User: ChenLong <vip_chenlong@163.com>
 * DateTime: 2020/7/14 11:51
 */


namespace zfb\app;


use Alipay\EasySDK\Kernel\Factory;
use think\facade\Log;
use zfb\AccountParam;
use zfb\Config;

class Auth
{
    /**
     * @param string $code
     * @return array|bool|mixed|\SimpleXMLElement
     * @throws \Exception
     */
    public static function login($code = '')
    {
        $access_token = Factory::setOptions(AccountParam::normal())::base()->oauth()->getToken($code);

        if ($access_token->code == 10000) {
            return self::getUserInfo($access_token->accessToken);
        }
        Log::write($access_token->msg);
        return [];
    }

    /**
     * @param $access_token
     * @return array|bool|mixed|\SimpleXMLElement
     * @throws \Exception
     */
    public static function getUserInfo($access_token)
    {
        include './../../aop/AopClient.php';

        $aop = new \AopClient();
        $aop->gatewayUrl = 'https://openapi.alipay.com/gateway.do';
        $aop->appId = Config::get('APPID');
        $aop->rsaPrivateKey = Config::get('private_key');
        $aop->alipayrsaPublicKey = Config::get('public_key');
        $aop->apiVersion = '1.0';
        $aop->signType = 'RSA2';
        $aop->postCharset='utf-8';
        $aop->format='json';
        $request = new \AlipayUserInfoShareRequest ();
        $result = $aop->execute( $request , $access_token);

        $responseNode = str_replace(".", "_", $request->getApiMethodName()) . "_response";
        $resultCode = $result->$responseNode->code;
        if (!empty($resultCode) && $resultCode == 10000) {
            return $result;
        } else {
            Log::write(json_encode($result, JSON_UNESCAPED_UNICODE));
            return [];
        }
    }
}

