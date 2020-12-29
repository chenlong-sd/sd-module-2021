<?php
/**
 *
 * Wap.php
 * User: ChenLong <vip_chenlong@163.com>
 * DateTime: 2020/7/21 14:48
 */


namespace zfb\pay;


use zfb\AccountParam;
use zfb\Helper;

/**
 * 手机网站支付
 * Class Wap
 * @package zfb\pay
 */
class Wap
{
    use CommonPay;

    public function __construct($uid = null)
    {
        self::$commonOptions = AccountParam::getOption();
        self::$commonOptions->method = 'alipay.trade.wap.pay';
        $this->product_code = 'QUICK_WAP_WAY';
        $this->out_trade_no = Helper::outTradeNoGenerate($uid ?: 'W');
    }
}

