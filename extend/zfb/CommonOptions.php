<?php
/**
 *
 * Options.php
 * User: ChenLong <vip_chenlong@163.com>
 * DateTime: 2020/7/21 10:01
 */


namespace zfb;

/**
 * 支付宝的公共参数
 * Class Options
 * @package zfb
 */
class CommonOptions
{
    /**
     * @var string AppId
     */
    public $app_id;
    /**
     * @var string
     */
    public $method;
    /**
     * @var string
     */
    public $format = 'JSON';
    /**
     * @var string
     */
    public $sign_type = 'RSA2';
    /**
     * @var string
     */
    public $timestamp;
    /**
     * @var string
     */
    public $version = '1.0';
    /**
     * @var string
     */
    public $notify_url;
    /**
     * @var string
     */
    public $return_url;
    /**
     * @var string
     */
    public $charset = 'utf-8';
    /**
     * @var string
     */
    public $biz_content;
    /**
     * @var string
     */
    public $sign;
    /**
     * @var string
     */
    public $app_auth_token;
}

