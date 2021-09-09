<?php
/**
 * datetime: 2021/9/8 0:04
 * user    : chenlong<vip_chenlong@163.com>
 **/


namespace weChat\apiv3\pay\place_param;


use weChat\apiv3\BaseParams;

/**
 * 支付者
 * Class Payer
 * @property string $openid
 * @package weChat\apiv3\pay\place_param
 * @author chenlong<vip_chenlong@163.com>
 * @date 2021/9/8
 */
class Payer extends BaseParams
{
    /**
     * Payer constructor.
     * @param string $openid
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/9/8
     */
    public function __construct(string $openid)
    {
        $this->openid = $openid;
    }
}

