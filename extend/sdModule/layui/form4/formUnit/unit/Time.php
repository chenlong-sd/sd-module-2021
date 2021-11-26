<?php
/**
 * datetime: 2021/11/19 18:00
 * user    : chenlong<vip_chenlong@163.com>
 **/

namespace sdModule\layui\form4\formUnit\unit;

use sdModule\layui\form4\formUnit\BaseFormUnit;
use sdModule\layui\form4\formUnit\unitConfig\{DefaultValue, JsConfig, Placeholder, Required, ShortTip};

abstract class Time extends BaseFormUnit
{
    use DefaultValue, Required, JsConfig, Placeholder, ShortTip;

    protected $dateType = "date";

    /**
     * @param string $dateType
     * @param string|bool $range
     * @return $this
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/11/26
     */
    public function dateType(string $dateType, $range = false): Time
    {
        $this->dateType = $dateType;
        $this->jsConfig['range'] = $range;
        return $this;
    }


}
