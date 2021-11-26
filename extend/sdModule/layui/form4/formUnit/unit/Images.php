<?php
/**
 * datetime: 2021/11/25 0:15
 * user    : chenlong<vip_chenlong@163.com>
 **/

namespace sdModule\layui\form4\formUnit\unit;

use sdModule\layui\form4\formUnit\BaseFormUnit;
use sdModule\layui\form4\formUnit\unitConfig\{DefaultValue, Required, ShortTip, SystemResource};

/**
 * Class Images
 * @package sdModule\layui\form4\formUnit\unit
 * @author chenlong<vip_chenlong@163.com>
 * @date 2021/11/25
 */
abstract class Images extends BaseFormUnit
{
    use DefaultValue, Required, SystemResource, ShortTip;
}
