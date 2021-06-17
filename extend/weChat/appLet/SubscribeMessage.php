<?php
/**
 * Date: 2021/3/22 16:36
 * User: chenlong <vip_chenlong@163.com>
 */

namespace weChat\appLet;


use weChat\common\AccessToken;
use weChat\common\Helper;

class SubscribeMessage
{
    const STATE_DEVELOPER = 'developer'; // 开发板
    const STATE_TRIAL     = 'trial';     // 体验版
    const STATE_FORMAL    = 'developer'; // 正式版

    private const URL = 'https://api.weixin.qq.com/cgi-bin/message/subscribe/send?access_token={ACCESS_TOKEN}';
    /**
     * @var string 用户openid
     */
    private $openid = '';
    /**
     * @var string 获取配置标签标签
     */
    private $configTag = '';
    /**
     * @var string|null 跳转类型
     */
    private $type = '';
    /**
     * @var string 跳转页面
     */
    private $page = '';
    /**
     * @var string 模板ID
     */
    private $templateId = '';

    /**
     * SubscribeMessage constructor.
     * @param string $config_tag
     */
    public function __construct(string $config_tag = 'common')
    {
        $this->configTag = $config_tag;
    }

    /**
     * 指定用户
     * @param string $openid
     * @return $this
     */
    public function toUser(string $openid): SubscribeMessage
    {
        $this->openid = $openid;
        return $this;
    }


    /**
     * 指定模板ID
     * @param string $template_id
     * @return $this
     */
    public function templateId(string $template_id): SubscribeMessage
    {
        $this->templateId = $template_id;
        return $this;
    }

    /**
     * 跳转页面
     * @param null|string $type
     * @param string $page {@see SubscribeMessage::STATE_DEVELOPER, SubscribeMessage::STATE_FORMAL, SubscribeMessage::STATE_TRIAL}
     * @return $this
     */
    public function jump(string $page = '', string $type = null)
    {
        $this->type = $type;
        $this->page = $page;
        return $this;
    }

    /**
     * 发送消息
     * @param array $data
     * @return mixed 9Fe9TRDNGTfCffJKPTyK6l7PiLG1sKawmadDEEl2a80
     */
    public function send(array $data)
    {
        $data = [
            'touser'      => $this->openid,
            'template_id' => $this->templateId,
            'data'        => array_map(function ($value) {
                return compact('value');
            }, $data)
        ];

        $this->type and $data['miniprogram_state'] = $this->type;
        $this->page and $data['page'] = $this->page;

        return Helper::postRequest(strtr(self::URL, ['{ACCESS_TOKEN}' => AccessToken::getAccessToken($this->configTag)]), $data);
    }
}
