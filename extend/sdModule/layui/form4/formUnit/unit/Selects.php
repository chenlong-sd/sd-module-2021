<?php
/**
 * datetime: 2021/11/25 14:51
 * user    : chenlong<vip_chenlong@163.com>
 **/

namespace sdModule\layui\form4\formUnit\unit;

use sdModule\layui\form4\formUnit\BaseFormUnit;
use sdModule\layui\form4\formUnit\unitConfig\DefaultValue;
use sdModule\layui\form4\formUnit\unitConfig\JsConfig;
use sdModule\layui\form4\formUnit\unitConfig\Options;
use sdModule\layui\form4\formUnit\unitConfig\Required;
use sdModule\layui\form4\formUnit\unitConfig\ShortTip;

abstract class Selects extends BaseFormUnit
{
    use DefaultValue, Options, JsConfig, ShortTip, Required;
}
