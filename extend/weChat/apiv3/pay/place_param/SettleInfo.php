<?php
/**
 * datetime: 2021/9/8 10:29
 * user    : chenlong<vip_chenlong@163.com>
 **/


namespace weChat\apiv3\pay\place_param;


use weChat\apiv3\BaseParams;

/**
 * 结算信息
 * Class SettleInfo
 * @property bool $profit_sharing 是否指定分账
 * @package weChat\apiv3\pay\place_param
 * @author chenlong<vip_chenlong@163.com>
 * @date 2021/9/8
 */
class SettleInfo extends BaseParams
{
    /**
     * SettleInfo constructor.
     * @param bool $profit_sharing
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/9/8
     */
    public function __construct(bool $profit_sharing)
    {
        $this->profit_sharing = $profit_sharing;
    }
}

