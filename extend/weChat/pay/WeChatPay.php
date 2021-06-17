<?php
/**
 * Date: 2020/8/5 16:58
 * User: chenlong <vip_chenlong@163.com>
 */

namespace weChat\pay;

use app\common\SdException;
use think\response\Xml;
use weChat\common\Helper;

/**
 * 微信支付
 * Class WeChatPay
 * @package weChat\pay
 */
class WeChatPay implements \ArrayAccess
{
    use BasicsAction,BasicsProperty,ArrayAccess;

    // 请求地址
    const REQUEST_URL = 'https://api.mch.weixin.qq.com/pay/unifiedorder';

    // 支付类型
    const APP = 'APP';
    const H5 = 'MWEB';
    const JS_API = 'JSAPI';
    const NATIVE = 'NATIVE';

    /**
     * 初始化
     * WeChatPay constructor.
     * @param string $trade_type 支付类型
     */
    public function __construct(string $trade_type = self::JS_API)
    {
        $this->trade_type = $trade_type;                   // 支付方式

        $this->appid  = $this->getParam('appid');    // 小程序或公众号 appid
        $this->mch_id = $this->getParam('mch_id');   // 商户号
        $this->key    = $this->getParam('key');      // 商秘钥

        $this->init();
    }

    /**
     * 发起下单请求，返回微信统一下单返回的数据
     * @return mixed
     * @throws \Exception
     */
    public function request()
    {
        $unifiedOrder = self::postRequest(self::REQUEST_URL, $this->xml());        // CURL 的 post请求
        return Helper::xmlToArray($unifiedOrder);
    }

    /**
     * 生成调起支付接口的提交数据
     * @param string|bool $prepayId 微信返回的 prepay_id(request方法), 为true 的时候可直接调用返回微信支付所需数据
     * @return array
     * @throws SdException|\Exception
     */
    public function requestPayData($prepayId): array
    {
        if ($prepayId === true) {
            $prepayId = $this->request();
            $prepayId = $prepayId['prepay_id'] ?? 0;
        }

        if (empty($prepayId)) throw new SdException('下单失败！');

//      组织再次签名所需的数据
        $SignField = [
            'appId'     => $this->appid,
            'timeStamp' => (string)time(),
            'nonceStr'  => $this->random(),
            'package'   => 'prepay_id=' . $prepayId,
            'signType'  => $this->sign_type
        ];

        $wxPayData = array_merge($SignField, ['paySign' => $this->sign($SignField)]);

        unset($wxPayData['appId']);
        return $wxPayData;
    }


    /**
     * 微信支付结果通知方法调用，返回数据；
     * @param callable|null $errHandle 处理错误的函数
     * @return bool|mixed
     */
    public function wxPayCallbackNotice(callable $errHandle = null)
    {
//        获取微信返回的xml数据
        $xmlData = file_get_contents('php://input');
        libxml_disable_entity_loader(true);
        $data = Helper::xmlToArray($xmlData);

        if ($data['return_code'] == 'SUCCESS') {
            $sign = $data['sign'];
            unset($data['sign']);
            if ($sign == (new self())->sign($data)) return $data;
            if ($errHandle !== null) call_user_func($errHandle, '签名错误！');
        }else{
            if ($errHandle !== null) call_user_func($errHandle, $data);
        }
        return false;
    }

    /**
     * 异步成功通知
     * @return Xml
     */
    public static function AsyncReturnSuccess(): Xml
    {
        return xml(['return_code' => 'SUCCESS']);
    }

    /**
     * 异步失败通知
     * @return Xml
     */
    public static function AsyncReturnFail(): Xml
    {
        return xml(['return_code' => 'FAIL']);
    }

    /**
     * 获取通知地址
     * @param string $uri 地址
     * @param bool $isIntact 是否是完整地址
     * @return $this
     */
    public function setNoticeUrl(string $uri, bool $isIntact = false): WeChatPay
    {
        $this->notify_url = $isIntact ? $uri :
            $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'] . '/' . $uri;

        return $this;
    }

}

