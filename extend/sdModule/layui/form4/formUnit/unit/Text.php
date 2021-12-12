<?php
/**
 * datetime: 2021/11/18 11:45
 * user    : chenlong<vip_chenlong@163.com>
 **/

namespace sdModule\layui\form4\formUnit\unit;

use sdModule\layui\form4\formUnit\BaseFormUnit;
use sdModule\layui\form4\formUnit\unitConfig\{DefaultValue, Icon, InputAttr, Options, Placeholder, Required, ShortTip};

/**
 * Class Text
 * @package sdModule\layui\form4\formUnit\unit
 * @author chenlong<vip_chenlong@163.com>
 * @date 2021/11/18
 */
abstract class Text extends BaseFormUnit
{
    use Options,ShortTip,DefaultValue,Placeholder,Required,InputAttr,Icon;


}
