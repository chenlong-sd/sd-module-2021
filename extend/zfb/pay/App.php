<?php
/**
 *
 * App.php
 * User: ChenLong <vip_chenlong@163.com>
 * DateTime: 2020/7/21 15:14
 */


namespace zfb\pay;


use zfb\AccountParam;
use zfb\Helper;

/**
 * APP 支付
 * Class App
 * @package zfb\pay
 */
class App
{
    use CommonPay;


    /**
     * App constructor.
     * @param null $uid
     */
    public function __construct($uid = null)
    {
        self::$commonOptions = AccountParam::getOption();
        self::$commonOptions->method = 'alipay.trade.app.pay';
        $this->product_code = 'QUICK_MSECURITY_PAY';
        $this->out_trade_no = Helper::outTradeNoGenerate($uid ?: 'A');
    }

}

