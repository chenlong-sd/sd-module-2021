<?php
/**
 * Date: 2020/8/6 10:02
 * User: chenlong <vip_chenlong@163.com>
 */

namespace weChat\pay;

/**
 * 微信提现的属性值
 * Trait PutForwardProperty
 * @package weChat\pay
 */
trait PutForwardProperty
{
    /**
     * 商户账号appid
     * @var string  32    必须
     */
    private $mch_appid;        //------------------------------------------------必须

    /**
     * 商户号
     * @var string   32   必须
     */
    private $mchid;        //------------------------------------------------必须

    /**
     * 设备号
     * @var  string  32
     */
    private $device_info;

    /**
     * 随机字符串
     * @var String(32)
     */
    private $nonce_str;        //------------------------------------------------必须

    /**
     * 签名
     * @var String(32)
     */
    private $sign;        //------------------------------------------------必须

    /**
     * 商户订单号
     * 商户订单号，需保持唯一性(只能是字母或者数字，不能包含有符号)
     * @var String
     */
    private $partner_trade_no;        //------------------------------------------------必须

    /**
     * 用户openid
     * @var String
     */
    public $openid;        //------------------------------------------------必须

    /**
     *
     * 校验用户姓名选项
     * NO_CHECK：不校验真实姓名
     * FORCE_CHECK：强校验真实姓名
     * @var String
     */
    private $check_name;        //------------------------------------------------必须

    /**
     * 收款用户姓名
     * 收款用户真实姓名。
     * 如果check_name设置为FORCE_CHECK，则必填用户真实姓名
     * @var String
     */
    public $re_user_name;

    /**
     * 金额
     * 企业付款金额，单位为分
     * @var int
     */
    public $amount;        //------------------------------------------------必须

    /**
     * 企业付款描述信息
     * 企业付款操作说明信息。必填。
     * @var String
     */
    public $desc;        //------------------------------------------------必须

    /**
     * Ip地址
     * 该IP同在商户平台设置的IP白名单中的IP没有关联，该IP可传用户端或者服务端的IP。
     * @var String(32)
     */
    private $spbill_create_ip;        //------------------------------------------------必须

    /**
     * 用户秘钥key
     * @var string
     */
    private $key;
}
