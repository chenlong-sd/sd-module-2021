<?php
/**
 * 微信提现类
 * User: chenLong
 * Date: 2018/8/22
 * Time: 17:54
 */

namespace weChat\pay;

use Exception;
use weChat\common\Helper;
use weChat\common\Config;

/**
 * 微信提现,（企业提现到零钱）
 * Class PutForward
 * @package app\common\wechart
 */
class PutForward
{
    const REQUEST_URL = 'https://api.mch.weixin.qq.com/mmpaymkttransfers/promotion/transfers';
    const QUERY_URL   = 'https://api.mch.weixin.qq.com/mmpaymkttransfers/gettransferinfo';

    use PutForwardProperty,BasicsAction;

    public function __construct()
    {
        $this->mch_appid = Config::cashBonus('appid');                // 应用 appid
        $this->mchid = Config::cashBonus('mch_id');                            // 商户号
        $this->key = Config::cashBonus('key');        // 秘钥
        $this->check_name = 'NO_CHECK';                         // 校验用户姓名选项
        $this->getIp();                                         // 获取ip
        $this->random();                                        // 生成随机数
        $this->outTradeNo();                                    // 生成订单号
    }

    /**
     * 发起请求
     * @param array $wxCert 证书路径（可选）
     * @return mixed
     * @throws Exception
     */
    public function request(array $wxCert = [])
    {
        $wxCert or $wxCert = Config::get('cert');
        $result = self::postRequest(self::REQUEST_URL, $this->xml(), $wxCert);

        return Helper::xmlToArray($result);
    }

    /**
     * 查询接口
     * @param $partner_trade_no     string  订单号
     * @param array $wxCert 证书路径（可选，包含  cert 和 key ）
     * @return mixed
     * @throws Exception
     */
    public function query(string $partner_trade_no, array $wxCert = [])
    {
        $wxCert or $wxCert = Config::get('cert');
        $requestData = [
            'nonce_str'         => $this->random(),
            'partner_trade_no'  => $partner_trade_no,
            'mch_id'            => $this->mchid,
            'appid'             => $this->mch_appid
        ];
        $requestData['sign'] = $this->sign($requestData);

        // 发起查询请求
        $result = self::postRequest(self::QUERY_URL, $this->xml($requestData), $wxCert);
        return Helper::xmlToArray($result);
    }

    public function __set($name, $value)
    {
        if (!empty($value) && property_exists($this, $name)) {
            $this->$name = $value;
        }
    }

    /**
     * 生成订单号
     */
    private function outTradeNo()
    {
        $this->partner_trade_no = date('Ymd') . $_SERVER['REQUEST_TIME'] . mt_rand(100000, 999999);
    }
}

