<?php
/**
 * datetime: 2021/9/8 10:21
 * user    : chenlong<vip_chenlong@163.com>
 **/


namespace weChat\apiv3\pay\place_param;


use weChat\apiv3\BaseParams;

/**
 * 场景信息
 * Class SceneInfo
 * @property string    $payer_client_ip  用户终端IP
 * @property string    $device_id        商户端设备号
 * @property StoreInfo $store_info       商户门店信息
 * @package weChat\apiv3\pay\place_param
 * @author chenlong<vip_chenlong@163.com>
 * @date 2021/9/8
 */
class SceneInfo extends BaseParams
{
    /**
     * SceneInfo constructor.
     * @param string $payer_client_ip
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/9/8
     */
    public function __construct(string $payer_client_ip)
    {
        $this->payer_client_ip = $payer_client_ip;
    }
}
