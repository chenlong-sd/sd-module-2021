<?php
/**
 * datetime: 2021/9/8 10:00
 * user    : chenlong<vip_chenlong@163.com>
 **/


namespace weChat\apiv3\pay\place_param;


use weChat\apiv3\BaseParams;

/**
 * 单品商品信息
 * Class GoodsDetail
 * @property string $merchant_goods_id      商户侧商品编码
 * @property string $wechatpay_goods_id     微信侧商品编码
 * @property string $goods_name             商品名称
 * @property int    $quantity               商品数量
 * @property int    $unit_price             商品单价
 * @package weChat\apiv3\pay\place_param
 * @author chenlong<vip_chenlong@163.com>
 * @date 2021/9/8
 */
class GoodsDetail extends BaseParams
{
    /**
     * GoodsDetail constructor.
     * @param string $goods_name
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/9/8
     */
    public function __construct(string $goods_name = '')
    {
        $goods_name and $this->goods_name = $goods_name;
    }
}

