<?php
/**
 *
 * Options.php
 * User: ChenLong <vip_chenlong@163.com>
 * DateTime: 2020/7/21 10:42
 */


namespace zfb\pay;


use zfb\CommonOptions;
use zfb\Config;
use zfb\Helper;

trait CommonPay
{
    /**
     * @var string
     */
    public $out_trade_no;
    /**
     * @var
     */
    public $product_code;
    /**
     * @var
     */
    public $total_amount;
    /**
     * @var
     */
    public $subject;

    /**
     * @var CommonOptions
     */
    public static $commonOptions;

    /**
     * @param string $charset
     * @return $this
     */
    public function charset(string $charset)
    {
        self::$commonOptions->charset = $charset;
        return $this;
    }

    /**
     * @param $return_url
     * @return $this
     */
    public function setReturnUrl(string $return_url)
    {
        self::$commonOptions->return_url = $return_url;
        return $this;
    }

    /**
     * @param string $notify_url
     * @return $this
     */
    public function setNotifyUrl(string $notify_url)
    {
        self::$commonOptions->notify_url = $notify_url;
        return $this;
    }

    /**
     * 简单参数创建订单并生成数据html
     * @param $subject
     * @param $amount
     * @return string
     */
    public function easyCreate($subject, $amount)
    {
        $this->subject = $subject;
        $this->total_amount = $amount;
        $this->sign((array)$this);

        return $this->createFormHtml();
    }

    /**
     * 创建订单并生成数据html
     * @param array $param
     * @return string
     */
    public function create(array $param)
    {
        $this->sign(array_merge((array)$this, $param));

        return $this->createFormHtml();
    }

    /**
     * 创建简单订单请求参数
     * @param array $param
     * @return CommonOptions
     */
    public function createParam(array $param)
    {
        $this->sign(array_merge((array)$this, $param));
        return Helper::filter((array)self::$commonOptions);
    }

    /**
     * 创建订单请求参数
     * @param $subject
     * @param $amount
     * @return CommonOptions
     */
    public function easyCreateParam($subject, $amount)
    {
        $this->subject = $subject;
        $this->total_amount = $amount;
        $this->sign((array)$this);
        return Helper::filter((array)self::$commonOptions);
    }

    /**
     * 生成签名
     * @param $data
     */
    private function sign(array $data)
    {
        self::$commonOptions->biz_content = json_encode(Helper::filter($data), JSON_UNESCAPED_UNICODE);
        self::$commonOptions->sign = Helper::sign((array)self::$commonOptions);
    }

    /**
     * 创建提交数据的表单
     * @return string
     */
    private function createFormHtml()
    {
        $host = Config::get('gateway') . '?charset=' . self::$commonOptions->charset;
        $form = "<form id='alipaysubmit' name='alipaysubmit' action='{$host}' >:input:submit</form>:script";
        $submit = "<input type='submit' value='ok' style='display:none;'>";

        $input = "";
        foreach ((array)self::$commonOptions as $key => $value) {
            empty($value) or $input .= "<input type='hidden' name='{$key}' value='{$value}'/>";
        }

        $script = "<script>document.forms['alipaysubmit'].submit();</script>";

        return strtr($form, [":input" => $input, ':submit' => $submit, ':script'  => $script]);
    }
}

