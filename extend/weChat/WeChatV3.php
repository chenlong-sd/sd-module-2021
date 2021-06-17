<?php
/**
 * Date: 2021/5/17 13:07
 * User: chenlong <vip_chenlong@163.com>
 */

namespace weChat;


use sdModule\common\StaticCallGetInstance;
use weChat\apiv3\pay\{Refund};

/**
 * Class WeChatV3
 * @package weChat
 * @method static Refund refund() 微信支付退款
 */
class WeChatV3 extends StaticCallGetInstance
{
    /**
     * @return string[]
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/6/17
     */
    protected function getNamespace()
    {
        return [
            'refund' => Refund::class
        ];
    }
}
