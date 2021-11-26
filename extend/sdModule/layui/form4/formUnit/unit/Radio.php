<?php
/**
 * datetime: 2021/11/24 23:51
 * user    : chenlong<vip_chenlong@163.com>
 **/

namespace sdModule\layui\form4\formUnit\unit;

use sdModule\layui\form4\formUnit\BaseFormUnit;
use sdModule\layui\form4\formUnit\unitConfig\DefaultValue;
use sdModule\layui\form4\formUnit\unitConfig\InputAttr;
use sdModule\layui\form4\formUnit\unitConfig\Options;
use sdModule\layui\form4\formUnit\unitConfig\Required;

abstract class Radio extends BaseFormUnit
{
    use DefaultValue, Required, Options, InputAttr;
}
