<?php
/**
 * datetime: 2021/11/25 11:25
 * user    : chenlong<vip_chenlong@163.com>
 **/

namespace sdModule\layui\form4\formUnit\unit;

use sdModule\layui\form4\formUnit\BaseFormUnit;
use sdModule\layui\form4\formUnit\unitConfig\{DefaultValue, InputAttr, JsConfig, Required, ShortTip};

/**
 * Class Color
 * @package sdModule\layui\form4\formUnit\unit
 * @author chenlong<vip_chenlong@163.com>
 * @date 2021/11/25
 */
abstract class Color extends BaseFormUnit
{
    use DefaultValue, Required, InputAttr, ShortTip, JsConfig;

    /**
     * 预定义颜色
     * @param array $predefine 预定义的颜色组 ,eg: ['#fff', '#aaa']
     * @return $this
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/11/25
     */
    public function predefine(array $predefine): Color
    {
        $this->jsConfig['predefine'] = 1;
        $this->jsConfig['colors'] = $predefine;
        return $this;
    }

    /**
     * 关闭透明度
     * @return Color
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/11/25
     */
    public function closeAlpha(): Color
    {
        $this->jsConfig['alpha'] = 0;
        return $this;
    }

    /**
     * 设置颜色格式  hex、rgb
     * @param string $format
     * @return Color
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/11/25
     */
    public function format(string $format): Color
    {
        $this->jsConfig['format'] = $format;
        return $this;
    }
}
