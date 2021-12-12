<?php
/**
 * datetime: 2021/11/18 23:06
 * user    : chenlong<vip_chenlong@163.com>
 **/

namespace sdModule\layui\form4\formUnit\unitProxy;

use sdModule\layui\form4\formUnit\BaseFormUnitProxy;
use sdModule\layui\form4\formUnit\unit\Checkbox;
use sdModule\layui\form4\formUnit\unitEntity\CheckboxEntity;

/**
 * Class Checkbox
 * @mixin Checkbox
 * @package sdModule\layui\form4\formUnit\unitProxy
 * @author chenlong<vip_chenlong@163.com>
 * @date 2021/11/18
 */
class CheckboxProxy extends BaseFormUnitProxy
{

    /**
     * 设置代理表单
     * @return string
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/11/18
     */
    protected function proxyUnit(): string
    {
        return CheckboxEntity::class;
    }
}