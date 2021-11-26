<?php
/**
 * datetime: 2021/11/18 21:56
 * user    : chenlong<vip_chenlong@163.com>
 **/

namespace sdModule\layui\form4\formUnit\unit;

use sdModule\layui\form4\formUnit\BaseFormUnit;
use sdModule\layui\form4\formUnit\unitConfig\{DefaultValue, InputAttr, Options, Placeholder, Required, ShortTip};

abstract class Checkbox extends BaseFormUnit
{
    use Options,DefaultValue,Required,InputAttr;

}
