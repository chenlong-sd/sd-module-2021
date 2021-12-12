<?php
/**
 * datetime: 2021/11/20 3:11
 * user    : chenlong<vip_chenlong@163.com>
 **/

namespace sdModule\layui\form4\formUnit\unitProxy;

use sdModule\layui\form4\formUnit\BaseFormUnitProxy;
use sdModule\layui\form4\formUnit\unit\Table;
use sdModule\layui\form4\formUnit\unitEntity\TableEntity;

/**
 * Class Table
 * @mixin Table
 * @package sdModule\layui\form4\formUnit\unitProxy
 * @author chenlong<vip_chenlong@163.com>
 * @date 2021/11/20
 */
class TableProxy extends BaseFormUnitProxy
{

    /**
     * 设置代理表单
     * @return string
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/11/18
     */
    protected function proxyUnit(): string
    {
        return TableEntity::class;
    }
}