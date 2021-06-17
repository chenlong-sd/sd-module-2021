<?php
/**
 * Date: 2020/12/18 9:53
 * User: chenlong <vip_chenlong@163.com>
 */

namespace weChat;


use sdModule\common\StaticCallGetInstance;
use weChat\h5\{JsApi, Login, OAuth2};
use weChat\appLet\Login as AppLetLogin;
use weChat\appLet\QrCode;
use weChat\appLet\SmallProgramORC;
use weChat\appLet\SubscribeMessage;
use weChat\pay\WeChatPay;

/**
 * Class WeChat
 * @method static OAuth2           oAuth2()
 * @method static JsApi            jsApi()
 * @method static SmallProgramORC  appLetORC(string $orc_type = SmallProgramORC::OCR_ID_CARD)
 * @method static WeChatPay        pay(string $trade_type = WeChatPay::JS_API)
 * @method static Login            h5Login(string $config_tag = 'common')
 * @method static AppLetLogin      appLetLogin(string $config_tag = 'common')
 * @method static QrCode           appLetQrCode()
 * @method static SubscribeMessage subscribeMessage(string $config_tag = 'common')
 * @package weChat
 */
class WeChat extends StaticCallGetInstance
{
    /**
     * @return array|string[]|string
     */
    protected function getNamespace()
    {
        return [
            'oAuth2'          => OAuth2::class,
            'jsApi'           => JsApi::class,
            'appLetORC'       => SmallProgramORC::class,
            'pay'             => WeChatPay::class,
            'h5Login'         => Login::class,
            'appLetLogin'     => AppLetLogin::class,
            'appLetQrCode'    => QrCode::class,
        ];
    }


}
