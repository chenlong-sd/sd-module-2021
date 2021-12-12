<?php
/**
 * datetime: 2021/11/18 21:01
 * user    : chenlong<vip_chenlong@163.com>
 **/

namespace sdModule\layui\form4\formUnit\unit;

use sdModule\layui\form4\formUnit\BaseFormUnit;
use sdModule\layui\form4\formUnit\unitConfig\{InputAttr, Placeholder, Required, ShortTip,Icon};

abstract class Password extends BaseFormUnit
{
    use ShortTip,Placeholder,Required,InputAttr, Icon;
}

