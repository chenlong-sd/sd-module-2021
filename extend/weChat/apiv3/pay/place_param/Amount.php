<?php
/**
 * datetime: 2021/9/7 21:50
 * user    : chenlong<vip_chenlong@163.com>
 **/


namespace weChat\apiv3\pay\place_param;

use weChat\apiv3\BaseParams;

/**
 * 下单金额参数
 * Class Amount
 * @property int $total
 * @property string $currency
 * @package weChat\apiv3\pay\place_param
 * @author chenlong<vip_chenlong@163.com>
 * @date 2021/9/7
 */
class Amount extends BaseParams
{
    /**
     * Amount constructor.
     * @param int $total
     * @param string $currency
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/9/7
     */
    public function __construct(int $total, string $currency = 'CNY')
    {
        $this->total = $total;
        $this->currency = $currency;
    }
}
