<?php


namespace weChat\thePublic;
use weChat\common\Helper;
use weChat\common\Config;
use weChat\common\AccessToken;

/**
 * 二维码生成
 * Class QRCode
 * @package app\common\wechat
 */
class QRCode
{
    const PARAM_INT = 1;
    const PARAM_STR = 2;

    /**
     * 获取二维码,ticket获取
     * @param $ticket   string  微信获取的ticket值
     * @return bool|mixed|string
     */
    public function getCode($ticket)
    {
        $QRUrl = 'https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket=' . urlencode($ticket);

        return Helper::getRequest($QRUrl, true);
    }


    /**
     * 永久带参数二维码ticket生成,可配合
     * @param mixed $param      参数值参数，为数字时取值为1 - 100000，字符串类型，长度限制为1到64
     * @param int $paramType    参数类型取值，1 2
     * @return mixed
     * @uses WeChartOfficialAccount::getCode() 获取二维码
     */
    public function neverTicket($param, $paramType = self::PARAM_INT)
    {
        $data = [
            'action_name' => $paramType == self::PARAM_STR  ? 'QR_LIMIT_STR_SCENE' : 'QR_LIMIT_SCENE',
            'action_info' => [
                'scene' => [($paramType == self::PARAM_STR ? 'scene_str': 'scene_id') => $param],
            ]
        ];

        $url = 'https://api.weixin.qq.com/cgi-bin/qrcode/create?access_token=' . AccessToken::getAccessToken();
        return Helper::postRequest($url, $data);
    }

    /**
     * 临时二维码的Ticket创建
     * @param int|string $param     参数，为数字时取值为1 - 100000，字符串类型，长度限制为1到64
     * @param int        $expireTime    过期时间，单位秒，最大不超过2592000（即30天
     * @param int        $paramType     参数类型取值，1 2
     * @return mixed
     */
    public function temporaryTicket($param, $expireTime, $paramType = self::PARAM_INT)
    {
        $data = [
            'expire_seconds' => $expireTime,
            'action_name' => $paramType == self::PARAM_STR ? 'QR_STR_SCENE' : 'QR_SCENE',
            'action_info' => [
                'scene' => [($paramType == self::PARAM_STR ? 'scene_str': 'scene_id') => $param],
            ]
        ];

        $url = 'https://api.weixin.qq.com/cgi-bin/qrcode/create?access_token=' . AccessToken::getAccessToken();
        return Helper::postRequest($url, $data);
    }
}

