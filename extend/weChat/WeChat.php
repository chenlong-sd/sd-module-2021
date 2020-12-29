<?php
/**
 * Date: 2020/12/18 9:53
 * User: chenlong <vip_chenlong@163.com>
 */

namespace weChat;


use sdModule\common\StaticCallGetInstance;
use weChat\h5\{JsApi, OAuth2};
use weChat\appLet\SmallProgramORC;
use weChat\pay\WeChatPay;

/**
 * Class WeChat
 * @method static OAuth2           oAuth2()
 * @method static JsApi            jsApi()
 * @method static SmallProgramORC  SmallProgramORC(string $orc_type = SmallProgramORC::OCR_ID_CARD)
 * @method static WeChatPay        pay(string $trade_type = WeChatPay::JS_API)
 * @package weChat
 */
class WeChat extends StaticCallGetInstance
{
    /**
     * @return array|string|\string[][]
     */
    protected function getNamespace()
    {
        return [
            'oAuth2'          => OAuth2::class,
            'jsApi'           => JsApi::class,
            'SmallProgramORC' => SmallProgramORC::class,
            'pay'             => WeChatPay::class
        ];
    }

}
