<?php


namespace weChat\common;

/**
 * 常规参数配置
 * Trait Config
 * @package app\common\wechat
 */
class Config
{
    /**
     * @var array
     */
    private $config;
    private static $instance;

    private function __construct()
    {
    }
    private function __clone()
    {
        // TODO: Implement __clone() method.
    }

    /**
     * @return Config
     */
    private static function getInstance(): Config
    {
        if (!self::$instance) {
            self::$instance = new self();
            self::$instance->config = include_once dirname(__DIR__) . '/config_.php';
        }

        return self::$instance;
    }

    /**
     * 获取配置
     * @param $key
     * @return mixed|null
     */
    public static function get($key)
    {
        $value = self::getInstance()->config;
        foreach (explode('.', $key) as $k) {
            if (!is_array($value)) break;
            $value = $value[$k] ?? null;
        }
        return $value;
    }

    /**
     * app 支付配置的参数，默认读取$payConfig, 可自定义
     * @param string $param
     * @return string
     */
    public static function appPay(string $param = '')
    {
        return self::get('app_pay.' . $param) ?: self::get('common_pay.' . $param);
    }

    /**
     * 小程序|公众号|h5 支付配置的参数，默认读取$payConfig, 可自定义
     * @param string $param
     * @return string
     */
    public static function xPay(string $param = '')
    {
        return self::get('small_pay.' . $param) ?: self::get('common_pay.' . $param);
    }

    /**
     * 扫码 支付配置的参数，默认读取$payConfig, 可自定义
     * @param string $param
     * @return string
     */
    public static function sPay(string $param = '')
    {
        return self::get('scan_pay.' . $param) ?: self::get('common_pay.' . $param);
    }

    /**
     * 现金红包的配置参数
     * @param string $param
     * @return string
     */
    public static function cashBonus(string $param = '')
    {
        return self::get('cash.' . $param) ?: self::get('common_pay.' . $param);
    }
}

