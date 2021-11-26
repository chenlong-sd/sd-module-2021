<?php
/**
 * datetime: 2021/11/24 23:36
 * user    : chenlong<vip_chenlong@163.com>
 **/

namespace sdModule\layui\form4\formUnit\unit;

use sdModule\layui\form4\formUnit\BaseFormUnit;
use sdModule\layui\form4\formUnit\unitConfig\{DefaultValue, InputAttr, Placeholder, Required};

abstract class Textarea extends BaseFormUnit
{
    use DefaultValue,Required,InputAttr,Placeholder;
}
