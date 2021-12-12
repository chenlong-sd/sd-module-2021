<?php
/**
 * datetime: 2021/11/25 15:22
 * user    : chenlong<vip_chenlong@163.com>
 **/

namespace sdModule\layui\form4\formUnit\unit;

use sdModule\layui\form4\formUnit\BaseFormUnit;
use sdModule\layui\form4\formUnit\unitConfig\DefaultValue;
use sdModule\layui\form4\formUnit\unitConfig\InputAttr;
use sdModule\layui\form4\formUnit\unitConfig\Placeholder;
use sdModule\layui\form4\formUnit\unitConfig\Required;
use sdModule\layui\form4\formUnit\unitConfig\ShortTip;

abstract class Tag extends BaseFormUnit
{
    use DefaultValue, Required, InputAttr, Placeholder, ShortTip;
}
