<?php
/**
 * datetime: 2021/11/19 16:38
 * user    : chenlong<vip_chenlong@163.com>
 **/

namespace sdModule\layui\form4\formUnit\unit;

use sdModule\layui\form4\formUnit\BaseFormUnit;
use sdModule\layui\form4\formUnit\unitConfig\{DefaultValue, Required, ShortTip, SystemResource};

/**
 * Class Image
 * @package sdModule\layui\form4\formUnit\unit
 * @author chenlong<vip_chenlong@163.com>
 * @date 2021/11/19
 */
abstract class Image extends BaseFormUnit
{
    use DefaultValue, ShortTip, Required, SystemResource;
}
