<?php
/**
 * Date: 2020/8/5 16:59
 * User: chenlong <vip_chenlong@163.com>
 */

namespace weChat\pay;


trait BasicsAction
{
    /**
     * 初始加载
     */
    private function init()
    {
        $this->getIp();                                     // 获取ip
        $this->random();                                    // 生成随机数
        $this->outTradeNo();                                // 生成订单号
        $this->time_start  = date('YmdHis', $_SERVER['REQUEST_TIME']);                   // 开始时间
        $this->time_expire = date('YmdHis', $_SERVER['REQUEST_TIME'] + 3600);// 结束时间
        $this->sign_type   = 'MD5';                           // 签名加密方式
    }

    /**
     * 获取appid等基础重要参数
     * @param string $param
     * @return mixed
     */
    private function getParam(string $param)
    {
        $config_param = [
            self::JS_API => '\\weChat\\common\\Config::xPay',
            self::H5     => '\\weChat\\common\\Config::xPay',
            self::APP    => '\\weChat\\common\\Config::appPay',
            self::NATIVE => '\\weChat\\common\\Config::sPay',
        ];

        return call_user_func($config_param[$this->trade_type], $param);
    }

    /**
     * 获取并设置客户端IP
     */
    private function getIp()
    {
        $unknown = 'unknown';
        if (isset($_SERVER['HTTP_X_FORWARDED_FOR']) && $_SERVER['HTTP_X_FORWARDED_FOR'] && strcasecmp($_SERVER['HTTP_X_FORWARDED_FOR'], $unknown)) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } elseif (isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], $unknown)) {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        /*
        处理多层代理的情况
        或者使用正则方式：$ip = preg_match("/[\d\.]{7,15}/", $ip, $matches) ? $matches[0] : $unknown;
        */
        if (false !== strpos($ip, ','))
            $ip = explode(',', $ip)[0];
        $this->spbill_create_ip = $ip;
    }

    /**
     * 生成随机字符串
     * @return string
     */
    private function random(): string
    {
        $array   = array_merge(range('A', 'Z'), range('a', 'z'), range(0, 9));
        $random  = mt_rand(24, 31);
        $array[] = substr(time(), -1);
        $string  = '';
        for ($i = 0; $i <= $random; ++$i) {
            $string .= $array[array_rand($array)];
        }
        $this->nonce_str = $string;
        return $string;
    }

    /**
     * 生成签名
     * @param array $signArray
     * @return string
     */
    private function sign(array $signArray = []): string
    {
        if (empty($signArray)) {
            foreach ($this as $key => $value) {
                if ($key != 'key' && $value) $signArray[$key] = $value;
            }
        }
        ksort($signArray);
        $signString = '';
        foreach ($signArray as $key => $value) {
            $signString .= $key . '=' . $value . '&';
        }
        $signString .= 'key=' . $this->key;
        $signType = $this->sign_type ?? 'md5';
        $this->sign = strtoupper(call_user_func($signType, $signString));
        return $this->sign;
    }

    /**
     * 生成订单号
     */
    private function outTradeNo()
    {
        $this->out_trade_no = date('Ymd') . $_SERVER['REQUEST_TIME'] . mt_rand(100000, 999999);
    }

    /**
     * 生成xml数据
     * @param array $data
     * @return string
     */
    private function xml($data = []): string
    {
        $data or $this->sign();      // 生成签名
        $xml = '<xml>' . PHP_EOL;
        foreach (($data ?: $this) as $k => $v) {
            if ($v && $k != 'key') {
                $xml .= "<{$k}>{$v}</{$k}>" . PHP_EOL;
            }
        }
        $xml .= '</xml>';
        return $xml;
    }


    /**
     * 获取内部属性
     * @param string $attr
     * @return mixed
     */
    public function getAttr(string $attr = '')
    {
        return $this->$attr ?? '';
    }


    /**
     * 设置appid，用于多个小程序一个后端
     * @param $appId
     * @return $this
     */
    public function setAppId(string $appId): BasicsAction
    {
        if ($appId) {
            $this->appid = $appId;
        }

        return $this;
    }

    /**
     * 设置商户号和对应的秘钥
     * @param $mch  string  商户号
     * @param $key  string  秘钥
     * @return $this
     */
    public function setMchAndKey(string $mch, string $key): BasicsAction
    {
        if (!empty($mch) && !empty($key)) {
            $this->mch_id = $mch;
            $this->key = $key;
        }

        return $this;
    }

    /**
     * 设置场景值（H5支付时的）
     * @param $url  string  网站地址
     * @param $name string  网站名字
     */
    public function setSceneInfo(string $url, string $name)
    {
        $scene_info = [
            'store_info' => [
                'type' => 'Wap',
                'wap_url' => $url,
                'wap_name' => $name
            ]
        ];

        $this->scene_info = json_encode($scene_info, JSON_UNESCAPED_UNICODE);
    }


    /**
     * 发起post请求
     * @param string $url 路径
     * @param mixed $data 数据
     * @param array|null $cert 证书
     * @return bool|string
     * @throws \Exception
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/6/17
     */
    private static function postRequest(string $url, $data, array $cert = null)
    {
        $headerArray = array("Content-type:application/xml;charset='utf-8'");
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headerArray);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

        if ($cert !== null){
            curl_setopt($curl, CURLOPT_SSLCERTTYPE, 'pem');
            curl_setopt($curl, CURLOPT_SSLCERT, $cert['cert']);
            curl_setopt($curl, CURLOPT_SSLKEYTYPE, 'pem');
            curl_setopt($curl, CURLOPT_SSLKEY, $cert['key']);
        }
        $output = curl_exec($curl);
        if (curl_errno($curl)) {
            throw new \Exception(curl_error($curl));
        }
        curl_close($curl);
        return $output;
    }
}

