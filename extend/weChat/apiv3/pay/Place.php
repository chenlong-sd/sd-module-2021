<?php
/**
 * datetime: 2021/9/7 11:23
 * user    : chenlong<vip_chenlong@163.com>
 **/


namespace weChat\apiv3\pay;

use think\helper\Str;
use weChat\apiv3\AesUtil;
use weChat\apiv3\BaseParams;
use weChat\apiv3\pay\place_param\Amount;
use weChat\apiv3\pay\place_param\Detail;
use weChat\apiv3\pay\place_param\Payer;
use weChat\apiv3\pay\place_param\SceneInfo;
use weChat\apiv3\pay\place_param\SettleInfo;
use weChat\common\Config;
use weChat\common\Helper;

/**
 * 下单
 * Class Place
 * 必填参数
 * @property string $appid          应用ID
 * @property string $mchid          直连商户号
 * @property string $description    商品描述
 * @property string $out_trade_no   商户订单号
 * @property string $notify_url     通知地址
 * @property Amount $amount         订单金额
 * @property Payer  $payer          支付者
 * 以下参数非必填
 * @property string $time_expire     交易结束时间
 * @property string $attach          附加数据
 * @property string $goods_tag       订单优惠标记
 * @property Detail $detail          优惠功能
 * @property SceneInfo  $scene_info  场景信息
 * @property SettleInfo $settle_info 结算信息
 * @package weChat\apiv3\pay
 * @author chenlong<vip_chenlong@163.com>
 * @date 2021/9/7
 */
class Place extends BaseParams
{
    private $url = [
        'JSAPI' => 'https://api.mch.weixin.qq.com/v3/pay/transactions/jsapi'
    ];

    /**
     * Place constructor.
     * @param string $config_tag 配置标签，自定义获取哪个配置下的参数
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/9/8
     */
    public function __construct(string $config_tag = 'common_pay')
    {
        $this->appid = Config::get("$config_tag.appid");
        $this->mchid = Config::get("$config_tag.mch_id");
    }

    /**
     * 生成商户订单号
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/9/8
     */
    private function generateOutTradeNo()
    {
        $this->offsetExists('out_trade_no') or $this->out_trade_no = date('YmdHis') . mt_rand(100000, 999999);
    }

    /**
     * 通知地址处理
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/9/8
     */
    private function noticeUrlHandle()
    {
        if (!preg_match('/^(http:|https:)/', $this->notify_url)){
            $protocol = empty($_SERVER['HTTPS']) ? 'http' : 'https';
            $domain   = $_SERVER['HTTP_HOST'];
            $this->notify_url = sprintf('%s://%s/%s', $protocol, $domain, ltrim($this->notify_url, '/'));
        }
    }

    /**
     * 随机字符串
     * @return string
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/9/8
     */
    private function randomStr()
    {
        return Str::random(16);
    }
    /**
     * 获取交易ID
     * @return bool|string
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/9/8
     */
    public function getPrepayId()
    {
        $this->generateOutTradeNo();
        $this->noticeUrlHandle();

        $result = Helper::PayApiV3Post($this->url['JSAPI'], $this->toArray());

        return empty($result['prepay_id']) ? '' : $result['prepay_id'];
    }

    /**
     * 获取支付数据
     * @return array
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/9/8
     */
    public function getPayData()
    {
        if (!$prepay_id = $this->getPrepayId()){
            return [];
        }
        $sign_field = [
            'appId'     => $this->appid,
            'timeStamp' => (string)time(),
            'nonceStr'  => $this->randomStr(),
            'package'   => "prepay_id=$prepay_id",
        ];

        $signStr = implode("\n", $sign_field) . "\n";

        openssl_sign($signStr, $sign, file_get_contents(Config::get('cert.key')), 'sha256WithRSAEncryption');
        $pay_data = $sign_field;
        $pay_data['paySign']  = base64_encode($sign);
        $pay_data['signType'] = 'RSA';
        unset($pay_data['appId']);

        return $pay_data;
    }

    /**
     * 通知信息解密
     * @param array $data
     * @return bool|string
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/9/8
     */
    public static function noticeDecrypt(array $data)
    {
        $AesUtil = new AesUtil(Config::get('common_pay.v3_key'));
        return $AesUtil->decryptToString($data['resource']['associated_data'] ?? '', $data['resource']['nonce'], $data['resource']['ciphertext']);
    }
}

