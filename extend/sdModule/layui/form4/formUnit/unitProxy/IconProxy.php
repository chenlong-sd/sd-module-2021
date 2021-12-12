<?php
/**
 * datetime: 2021/12/11 16:25
 * user    : chenlong<vip_chenlong@163.com>
 **/

namespace sdModule\layui\form4\formUnit\unitProxy;

use sdModule\layui\form4\formUnit\BaseFormUnitProxy;
use sdModule\layui\form4\formUnit\unit\Icon;
use sdModule\layui\form4\formUnit\unitEntity\IconEntity;

/**
 * Class IconProxy
 * @mixin Icon
 * @package sdModule\layui\form4\formUnit\unitProxy
 * @author chenlong<vip_chenlong@163.com>
 * @date 2021/12/11
 */
class IconProxy extends BaseFormUnitProxy
{

    /**
     * 设置代理表单
     * @return string
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/11/18
     */
    protected function proxyUnit(): string
    {
        return IconEntity::class;
    }
}
