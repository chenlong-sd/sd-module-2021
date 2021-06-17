<?php


namespace weChat\h5;
use weChat\common\Helper;
use weChat\common\Config;
use weChat\common\AccessToken;

/**
 * js_api的配置处理
 * Class JsApi
 * @package app\common\wechat
 */
class JsApi
{
    /**
     * 获取JS_API 配置选项
     * @param $url      string 页面地址
     * @param $api_list array   接口列表
     * @return array
     */
    public function getJsApiConfig(string $url, array $api_list): array
    {
        $str = '1234567890abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $strLen = strlen($str) - 1;
        $data = [
            'noncestr' => '',
            'jsapi_ticket' => $this->getJsApiTicket(),
            'timestamp' => $_SERVER['REQUEST_TIME'],
            'url' => $url
        ];
        for ($i = 1; $i <= 8; $i++) {
            $data['noncestr'] .= $str[mt_rand(0, $strLen)];
        }

        ksort($data);


        $signStr = '';
        foreach ($data as $key => $value) {
            $signStr .= $key . '=' . $value . '&';
        }

        $sign = sha1(substr($signStr, 0,  -1));

        return [
            'debug' => false,
            'appId' => Config::get('appId'),
            'timestamp' => $_SERVER['REQUEST_TIME'],
            'nonceStr' => $data['noncestr'],
            'signature' => $sign,
            'jsApiList' => $api_list    // ['chooseWXPay']
        ];
    }

    /**
     * 获取并缓存jsapi_ticket的值
     * @return bool|mixed
     */
    protected function getJsApiTicket()
    {
        $url = sprintf('https://api.weixin.qq.com/cgi-bin/ticket/getticket?access_token=%s&type=jsapi', AccessToken::getAccessToken());

        if (Helper::getValue(Config::get('jsApiTicketKey'))) {
            return Helper::getValue(Config::get('jsApiTicketKey'));
        }

        $ticket = Helper::getRequest($url);

        if (!empty($ticket['ticket'])) {
            Helper::setValue(Config::get('jsApiTicketKey'), $ticket['ticket'], $ticket['expires_in'] - 300);
            return $ticket['ticket'];
        } else {
            Helper::log(json_encode($ticket)); // 写入错误原因到日志
            return '';
        }
    }
}

