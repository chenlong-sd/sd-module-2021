<?php
/**
 * Date: 2020/12/14 13:47
 * User: chenlong <vip_chenlong@163.com>
 */

namespace sdModule\common\helper;

use AlibabaCloud\Client\AlibabaCloud;
use AlibabaCloud\Client\Request\RpcRequest;
use think\facade\Log;
use think\helper\Arr;

/**
 * Class ALiYunSms
 * @package sdModule\common\helper
 */
class ALiYunSms
{
    /**
     * @var RpcRequest
     */
    private $rpc;

    private const HOST = 'dysmsapi.aliyuncs.com';
    /**
     * @var string
     */
    private $product = 'Dysmsapi';
    /**
     * @var string
     */
    private $method = 'POST';
    /**
     * @var string
     */
    private $action = 'SendSms';
    /**
     * @var string
     */
    private $version = '2017-05-25';
    /**
     * @var string
     */
    private $sign;

    /**
     * @var string
     */
    private $regionId;

    /**
     * @var array
     */
    private $template = [];

    /**
     * @var mixed|string 模板代码
     */
    private $template_code = '';

    /**
     * @var array
     */
    private $param_list = [];

    /**
     * ALiYunSms constructor.
     * @param string|null $accessKeyId
     * @param string|null $accessKeySecret
     * @param string|null $regionId
     * @throws \AlibabaCloud\Client\Exception\ClientException
     */
    public function __construct(string $accessKeyId = null, string $accessKeySecret = null, string $regionId = null)
    {
        $accessKeyId     = $accessKeyId === null ? env('A_LI_YUN_SMS.SMS_ACCESS_KEY_ID') : $accessKeyId;
        $accessKeySecret = $accessKeySecret === null ? env('A_LI_YUN_SMS.SMS_ACCESS_KEY_SECRET') : $accessKeySecret;
        $this->regionId  = $regionId === null ? env('A_LI_YUN_SMS.REGION_ID') : $regionId;

        $this->templateCodeDefaultHandle();

        AlibabaCloud::accessKeyClient($accessKeyId, $accessKeySecret)
            ->regionId($this->regionId)->asDefaultClient();

        $this->sign = env('A_LI_YUN_SMS.SIGN', '');

        $this->rpc = AlibabaCloud::rpc()->product($this->product)
            ->host(self::HOST)->version($this->version)
            ->method($this->method);
    }

    /**
     * 默认模板参数处理
     */
    private function templateCodeDefaultHandle()
    {
        $this->template = env('A_LI_YUN_SMS.TEMPLATE', []);
        if ($this->template) {
            $this->template_code = (string)array_key_first($this->template);
            $this->param_list = explode(',', current($this->template));
        }
    }


    /**
     * 设置签名
     * @param string $sign
     * @return $this
     */
    public function setSign(string $sign)
    {
        $this->sign = $sign;
        return $this;
    }

    /**
     * 设置模板
     * @param string $code
     * @return $this
     */
    public function setTemplateCode(string $code)
    {
        $this->template_code = $code;
        if (isset($this->template[$code])) {
            $this->param_list = explode(',', $this->template[$code]);
        }else{
            $this->param_list = [];
        }
        return $this;
    }

    /**
     * 发送单条短信
     * @param string $phone 手机号，
     * @param mixed ...$param 参数（配置env文件后依次传），否则传关联数组
     * @return bool|mixed|string
     * @throws \AlibabaCloud\Client\Exception\ClientException
     */
    public function send(string $phone, ...$param)
    {
        $this->setAction('SendSms');

        if (!Arr::isAssoc($param) && $this->param_list){
            $param = array_combine($this->param_list, $param);
        }else{
            $param = current($param);
        }

        $query = [
            'RegionId'      => $this->regionId,
            'PhoneNumbers'  => $phone,
            'SignName'      => $this->sign,
            'TemplateCode'  => $this->template_code,
        ];

        if ($param){
            $query['TemplateParam'] = json_encode($param, JSON_UNESCAPED_UNICODE);
        }

        $this->rpc->action($this->action)->options(compact('query'));

        return $this->request();
    }

    /**
     * 批次发送信息
     * @param array $phone 手机号码
     * @param array $param 参数，照原参数传
     * @param array|null $sign 签名
     * @return bool|mixed|string
     * @throws \AlibabaCloud\Client\Exception\ClientException
     */
    public function batchSend(array $phone, array $param, array $sign = null)
    {
        $this->setAction('SendBatchSms');

        $query = [
            'RegionId'          => $this->regionId,
            'PhoneNumberJson'   => json_encode($phone),
            'TemplateCode'      => $this->template_code,
            'TemplateParamJson' => json_encode($param, JSON_UNESCAPED_UNICODE)
        ];
        $query['SignNameJson'] = $sign === null ? array_pad([], count($phone), $this->sign) : $sign;
        $query['SignNameJson'] = json_encode($query['SignNameJson'], JSON_UNESCAPED_UNICODE);

        $this->rpc->action($this->action)->options(compact('query'));

        return $this->request();
    }


    /**
     * 发送短信请求
     */
    private function request()
    {
        try {
            $result = $this->rpc->request()->toArray();
            if ($result['Code'] !== 'OK'){
                return $result['Message'];
            }
        } catch (\Exception $exception) {
            Log::write($exception->getMessage(), 'error');
            return 'error';
        }
        return true;
    }

    private function setAction(string $action)
    {
        $this->action = $action;
    }
}
