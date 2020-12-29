<?php
/**
 *
 * Page.php
 * User: ChenLong <vip_chenlong@163.com>
 * DateTime: 2020/7/21 9:49
 */


namespace zfb\pay;


use zfb\AccountParam;
use zfb\Helper;

/**
 * 电脑网站支付
 * Class Page
 * @package zfb\pay
 */
class Page
{
    use CommonPay;


    /**
     * Page constructor.
     * @param null $uid
     */
    public function __construct($uid = null)
    {
        self::$commonOptions = AccountParam::getOption();
        self::$commonOptions->method = 'alipay.trade.page.pay';
        $this->product_code = 'FAST_INSTANT_TRADE_PAY';
        $this->out_trade_no = Helper::outTradeNoGenerate($uid ?: 'P');
    }

}

