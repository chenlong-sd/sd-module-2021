<?php
/**
 * datetime: 2021/11/25 15:04
 * user    : chenlong<vip_chenlong@163.com>
 **/

namespace sdModule\layui\form4\formUnit\unit;

use sdModule\layui\form4\formUnit\BaseFormUnit;
use sdModule\layui\form4\formUnit\unitConfig\DefaultValue;
use sdModule\layui\form4\formUnit\unitConfig\InputAttr;
use sdModule\layui\form4\formUnit\unitConfig\JsConfig;
use sdModule\layui\form4\formUnit\unitConfig\Required;
use sdModule\layui\form4\formUnit\unitConfig\ShortTip;

/**
 * Class Slider
 * @package sdModule\layui\form4\formUnit\unit
 * @author chenlong<vip_chenlong@163.com>
 * @date 2021/11/25
 */
class Slider extends BaseFormUnit
{
    use DefaultValue, Required, InputAttr, ShortTip, JsConfig;
}