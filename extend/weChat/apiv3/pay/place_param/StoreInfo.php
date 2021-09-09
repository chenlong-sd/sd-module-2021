<?php
/**
 * datetime: 2021/9/8 10:22
 * user    : chenlong<vip_chenlong@163.com>
 **/


namespace weChat\apiv3\pay\place_param;


use weChat\apiv3\BaseParams;

/**
 * 商户门店信息
 * Class StoreInfo
 * @property string $id         门店编号
 * @property string $name       门店名称
 * @property string $area_code  地区编码
 * @property string $address    详细地址
 * @package weChat\apiv3\pay\place_param
 * @author chenlong<vip_chenlong@163.com>
 * @date 2021/9/8
 */
class StoreInfo extends BaseParams
{
    /**
     * StoreInfo constructor.
     * @param string $id
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/9/8
     */
    public function __construct(string $id)
    {
        $this->id = $id;
    }
}

