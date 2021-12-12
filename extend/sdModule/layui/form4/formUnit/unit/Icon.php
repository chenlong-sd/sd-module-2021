<?php
/**
 * datetime: 2021/12/11 16:23
 * user    : chenlong<vip_chenlong@163.com>
 **/

namespace sdModule\layui\form4\formUnit\unit;

use sdModule\layui\form4\formUnit\BaseFormUnit;
use sdModule\layui\form4\formUnit\unitConfig\DefaultValue;
use sdModule\layui\form4\formUnit\unitConfig\JsConfig;
use sdModule\layui\form4\formUnit\unitConfig\Placeholder;
use sdModule\layui\form4\formUnit\unitConfig\ShortTip;

/**
 * Class Icon
 * @package sdModule\layui\form4\formUnit\unit
 * @author chenlong<vip_chenlong@163.com>
 * @date 2021/12/11
 */
abstract class Icon extends BaseFormUnit
{
    use DefaultValue,Placeholder,ShortTip, JsConfig;

    public function __construct(string $name = '', string $label = '')
    {
        parent::__construct($name, $label);
        $this->placeholder = '请选择';
    }
}

