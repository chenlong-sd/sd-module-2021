<?php
/**
 * Date: 2021/5/12 10:30
 * User: chenlong <vip_chenlong@163.com>
 */

namespace weChat\appLet;


use weChat\common\AccessToken;
use weChat\common\Helper;

class QrCode
{
    /***
     * @var array
     */
    private $param = [];

    /**
     * 无限制
     * @var string 
     */
    private $url = "https://api.weixin.qq.com/wxa/getwxacodeunlimit?access_token=ACCESS_TOKEN";

    /**
     * 无限数量
     * @param string $scene
     * @param string|null $page
     * @return QrCode
     */
    public function setUnlimited(string $scene = '', string $page = null): QrCode
    {
        $this->param['page']  = $page;
        $this->param['scene'] = $scene;
        $this->url = 'https://api.weixin.qq.com/wxa/getwxacodeunlimit?access_token=ACCESS_TOKEN';
        return $this;
    }

    /**
     * 有限数量
     * @param string $path
     * @return QrCode
     */
    public function setLimited(string $path): QrCode
    {
        $this->param['path'] = $path;
        $this->url = 'https://api.weixin.qq.com/wxa/getwxacode?access_token=ACCESS_TOKEN';
        return $this;
    }

    /**
     * @param array $param
     * @return $this
     */
    public function param(array $param): QrCode
    {
        $this->param = array_merge($this->param, $param);
        return $this;
    }

    /**
     * 设置二维码
     */
    public function setQrCode()
    {
        $this->url = 'https://api.weixin.qq.com/cgi-bin/wxaapp/createwxaqrcode?access_token=ACCESS_TOKEN';
    }

    /**
     * 获取数据
     * @param string $config_tag
     * @return mixed
     */
    public function getCode($config_tag = 'common')
    {
        $url = strtr($this->url, ['ACCESS_TOKEN' => AccessToken::getAccessToken($config_tag)]);
        return Helper::postResource($url, $this->param);
    }

    /**
     * 获取并输出
     * @param string $config_tag
     * @return mixed
     */
    public function getAndOutput($config_tag = 'common')
    {
        $url = strtr($this->url, ['ACCESS_TOKEN' => AccessToken::getAccessToken($config_tag)]);
        ob_end_flush();
        header("Content-type:image/png");
        return Helper::postResource($url, $this->param);
    }
}
