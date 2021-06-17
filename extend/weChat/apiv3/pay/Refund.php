<?php
/**
 * Date: 2021/5/17 13:09
 * User: chenlong <vip_chenlong@163.com>
 */

namespace weChat\apiv3\pay;


use weChat\common\Helper;

/**
 * 微信退款
 * Class Refund
 * @package weChat\apiv3\pay
 * @author chenlong<vip_chenlong@163.com>
 * @date 2021/6/17
 */
class Refund
{
    private const URL = "https://api.mch.weixin.qq.com/v3/refund/domestic/refunds";
    /**
     * @var string 原商户订单号
     */
    public $out_trade_no = '';
    /**
     * @var string 原交易订单号
     */
    public $transaction_id = '';
    /**
     * @var string 回调地址
     */
    public $notify_url = '';
    /**
     * @var string 退款订单号
     */
    public $out_refund_no = '';
    /**
     * @var string 退款原因
     */
    public $reason = '';
    /**
     * @var string 退款资金来源
     */
    public $funds_account = '';
    /**
     * @var array 退款金额信息
     * 退款金额	refund
     * 原订单金额	total
     * 退款币种	currency default CNY
     */
    public $amount = [];

    /**
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function request()
    {
        return Helper::PayApiV3Post(self::URL, $this->paramHandle());
    }

    /**
     * 参数处理
     * @return array
     */
    private function paramHandle(): array
    {
        $this->defaultOutRefundNo();
        return array_filter((array)$this);
    }

    /**
     * 生成默认的退款订单
     */
    private function defaultOutRefundNo()
    {
        if (!$this->out_refund_no) {
            $this->out_refund_no = $this->out_trade_no ? "ON{$this->out_trade_no}" : "TI{$this->transaction_id}";
        }
    }

    /**
     * 设置商户订单号
     * @param string $out_trade_no
     * @return Refund
     */
    public function setOutTradeNo(string $out_trade_no): Refund
    {
        $this->out_trade_no = $out_trade_no;
        return $this;
    }

    /**
     * 设置微信交易ID
     * @param string $transaction_id
     * @return Refund
     */
    public function setTransactionId(string $transaction_id): Refund
    {
        $this->transaction_id = $transaction_id;
        return $this;
    }

    /**
     * 设置通知地址
     * @param string $notify_url
     * @return Refund
     */
    public function setNotifyUrl(string $notify_url): Refund
    {
        $this->notify_url = $notify_url;
        return $this;
    }

    /**
     * 设置退款订单号
     * @param string $out_refund_no
     * @return Refund
     */
    public function setOutRefundNo(string $out_refund_no): Refund
    {
        $this->out_refund_no = $out_refund_no;
        return $this;
    }

    /**
     * 设置退款原因
     * @param string $reason
     * @return Refund
     */
    public function setReason(string $reason): Refund
    {
        $this->reason = $reason;
        return $this;
    }

    /**
     * 设置退款账户
     * @param string $funds_account
     * @return Refund
     */
    public function setFundsAccount(string $funds_account = "AVAILABLE"): Refund
    {
        $this->funds_account = $funds_account;
        return $this;
    }

    /**
     * 设置金额
     * @param int $refund 退款金额
     * @param int $total 原订单金额
     * @param string $currency 退款币种
     * @return Refund
     */
    public function setAmount(int $refund, int $total, string $currency = 'CNY'): Refund
    {
        $this->amount = compact('refund', 'total', 'currency');
        return $this;
    }
}
