<?php
/**
 * 传承短信发送
 */
namespace sdModule\common\helper;

/**
 * Class CcSms
 * @package app\common\controller
 */

class CcSms
{
    private const URL = 'http://182.140.233.19/sms/send/index.php';
    private static $APP_KEY = '';

    private $name='';
    /**
     * @var string 账号
     */
    private $account = '';

    /**
     * @var string 密码
     */
    private $password = '';

    /**
     * @var string 模板ID
     */
    private $template;

    /***
     * @var string 类型，1 是短信，其他的自己看去
     */
    private $types = '1';

    /**
     * @var string 参数
     */
    private $parameter;

    /**
     * @var string 内容,可直接复制过来
     */
    private $content;

    /**
     * @var string 时间戳
     */
    private $timestamp;

    /**
     * @var string 签名
     */
    private $sign;

    /**
     * @var string 定时发送 eq: 2018-1-1 12:00:00 ,为空则是即时
     */
    private $sendtime;

    /**
     * @var string 手机号
     */
    private $mobile;

    /**
     * 初始化并设置手机号
     * CcSms constructor.
     * @param string|array $phone
     */
    public function __construct($phone)
    {
        $this->mobile = $phone;
        $this->account = env('CC_SMS.CS_ACCOUNT', '');
        $this->password = env('CC_SMS.CS_PASSWORD', '');
        self::$APP_KEY = env('CC_SMS.CS_APP_KEY', '');
        return $this;
    }


    /**
     * 发送模板
     * @param $template_id
     * @return $this
     */
    public function templateId($template_id)
    {
        $template_resource = env('CC_SMS.CS_TEMPLATE', []);

        if (isset($template_resource[$template_id])) {
            $this->content = $template_resource[$template_id]; //  复制模板内容

            $this->template = $template_id; // 模板ID
        }

        return $this;
    }

    /**
     * 发送短信
     * @param mixed ...$value 值，多个值依次传入即可
     * @return array
     * @throws \ReflectionException
     */
    public function send(...$value)
    {
//        属性重新赋值
        $this->parameter = $value ? '["' . implode('","', $value) . '"]' : '';
        $this->content = sprintf(strtr($this->content, ['{val}' => '%s']), ...$value);
        $this->timestamp = $_SERVER['REQUEST_TIME'];
        $this->sign = md5($this->mobile . $this->timestamp . self::$APP_KEY);

//      发送短信请求,测试失败时可打印返回的数据查看错误原因
        return $this->http_post_data(self::URL, $this->sendData());
    }

    /**
     * 发送请求
     * @param $url
     * @param $data_string
     * @return array
     */
    private function http_post_data($url, $data_string)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);

        ob_start();
        curl_exec($ch);
        $return_content = ob_get_contents();
        ob_end_clean();

        $return_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        return array($return_code, $return_content);
    }

    /**
     * 组装发送请求数据及返回
     * @return bool|string
     */
    private function sendData()
    {
        $sendData = '';

        foreach ($this as $property => $value) {
            $sendData .= $property . '=' . urlencode($value) . '&';
        }

        return substr($sendData, 0, -1);
    }
}

