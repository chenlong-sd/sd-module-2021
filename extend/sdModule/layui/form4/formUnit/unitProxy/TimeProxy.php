<?php
/**
 * datetime: 2021/11/20 0:22
 * user    : chenlong<vip_chenlong@163.com>
 **/

namespace sdModule\layui\form4\formUnit\unitProxy;

use sdModule\layui\form4\formUnit\BaseFormUnitProxy;
use sdModule\layui\form4\formUnit\unit\Time;
use sdModule\layui\form4\formUnit\unitEntity\TimeEntity;

/**
 * Class Time
 * @mixin Time
 * @package sdModule\layui\form4\formUnit\unitProxy
 * @author chenlong<vip_chenlong@163.com>
 * @date 2021/11/20
 */
class TimeProxy extends BaseFormUnitProxy
{
    /**
     * 设置代理表单
     * @return string
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/11/18
     */
    protected function proxyUnit(): string
    {
        return TimeEntity::class;
    }

}