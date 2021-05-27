<?php
/**
 * Date: 2020/8/10 9:55
 * User: chenlong <vip_chenlong@163.com>
 */

namespace weChat\thePublic;

use weChat\common\AccessToken;
use weChat\common\Helper;

/**
 * 模板消息
 * Class TemplateMessage
 * @package weChat\thePublic
 */
class TemplateMessage
{
    const SEND_URL = 'https://api.weixin.qq.com/cgi-bin/message/template/send?access_token={:token}';

    /**
     * @var string 发送的用户的openid
     */
    private $touser = '';
    /**
     * @var string 模板id
     */
    private $template_id = '';
    /**
     * @var string 跳转的路径
     */
    private $url = '';
    /**
     * @var array 小程序的信息
     */
    private $miniprogram = [];

    /**
     * TemplateMessage constructor.
     * @param string $openid 发送的用户的openid
     * @param string $template_id   模板id
     */
    public function __construct(string $openid, string $template_id)
    {
        $this->touser = $openid;
        $this->template_id = $template_id;
    }

    /**
     * 跳转的路径
     * @param string $url
     */
    public function setUrl(string $url)
    {
        $this->url = $url;
    }

    /**
     * 设置小程序的信息
     * @param string $appid 小程序 appid
     * @param string $pagepath  小程序的路径
     */
    public function setMiniprogram(string $appid, string $pagepath)
    {
        $this->miniprogram = compact('appid', 'pagepath');
    }

    /**
     * 发送消息
     * @param array $data
     * @return mixed
     */
    public function send(array $data)
    {
        return Helper::postRequest(strtr(self::SEND_URL, ['{:token}' => AccessToken::getAccessToken()]),
            $this->makeData($data));
    }

    /**
     * 组合发送的数据
     * @param $data
     * @return array
     */
    private function makeData($data)
    {
        $sendData = ['data' => $data];

        foreach ($this as $key => $value){
            $sendData[$key] = $value;
        }
        return array_filter($sendData);
    }
}
