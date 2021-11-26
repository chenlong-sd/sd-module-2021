<?php
/**
 * datetime: 2021/11/19 2:01
 * user    : chenlong<vip_chenlong@163.com>
 **/

namespace sdModule\layui\form4\formUnit\unit;

use sdModule\layui\form4\formUnit\BaseFormUnit;
use sdModule\layui\form4\formUnit\unitConfig\DefaultValue;
use sdModule\layui\form4\formUnit\unitConfig\JsConfig;
use sdModule\layui\form4\formUnit\unitConfig\Required;

abstract class UEditor extends BaseFormUnit
{
    use DefaultValue,Required,JsConfig;
}
