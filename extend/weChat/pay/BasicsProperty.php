<?php
/**
 * Date: 2020/8/5 17:32
 * User: chenlong <vip_chenlong@163.com>
 */

namespace weChat\pay;

/**
 * 支付基础属性值
 * Trait BasicsProperty
 * @package weChat\pay
 */
trait BasicsProperty
{

    /** @var string 微信开放平台审核通过的应用APPID 《 必须 》|| */
    private $appid;

    /** @var string 微信支付分配的商户号《 必须 》 */
    private $mch_id;

    /** @var string 设备号  《 必须 》*/
    public $device_info;

    /** @var string 随机字符串  《 必须 》*/
    private $nonce_str;

    /** @var string 签名  《 必须 》*/
    private $sign;

    /** @var string 签名类型  《 必须 》*/
    public $sign_type;

    /** @var string 商品描述  《 必须 》*/
    public $body;

    /** @var string 商品详情  《 必须 》*/
    public $detail;

    /** @var string 附加数据 */
    public $attach;

    /** @var string 订单号   《 必须 》*/
    private $out_trade_no;

    /** @var string 货币类型 */
    public $fee_type;

    /** @var string 总金额   《 必须 》*/
    public $total_fee;

    /** @var string 终端ip  《 必须 》*/
    private $spbill_create_ip;

    /** @var false|string 交易起始时间  yyyyMMddHHmmss */
    private $time_start;

    /** @var false|string 交易结束时间  yyyyMMddHHmmss */
    private $time_expire;

    /** @var string 订单优惠标记 */
    public $goods_tag;

    /** @var string 通知地址 《 必须 》*/
    public $notify_url;

    /** @var string 交易类型 */
    public $trade_type;

    /** @var string 商品id */
    public $product_id;

    /** @var string 指定支付方式 */
    public $limit_pay;

    /** @var string 用户标识(JSAPI 模式必须） */
    public $openid;

    /** @var string 电子发票入口开放标识 */
    public $receipt;
    /**
     * 场景信息
     * {
     *  "store_id": "", //门店唯一标识，选填，String(32)
     *  "store_name":"”//门店名称，选填，String(64)
     *}
     * {"h5_info": //h5支付固定传"h5_info"
     * {"type": "",  //场景类型
     * "wap_url": "",//WAP网站URL地址
     * "wap_name": ""  //WAP 网站名
     * }
     * }
     * @var string (json) 《H5支付时必须》
     */
    public $scene_info;

    /** @var string 商户秘钥 《 必须 》*/
    private $key;

}
