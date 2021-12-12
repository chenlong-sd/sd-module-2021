<?php
/**
 * datetime: 2021/11/18 21:27
 * user    : chenlong<vip_chenlong@163.com>
 **/

namespace sdModule\layui\form4\formUnit\unitProxy;

use sdModule\layui\form4\formUnit\BaseFormUnitProxy;
use sdModule\layui\form4\formUnit\unit\Password;
use sdModule\layui\form4\formUnit\unitEntity\PasswordEntity;

/**
 * Class Password
 * @mixin Password
 * @package sdModule\layui\form4\formUnit\unitProxy
 * @author chenlong<vip_chenlong@163.com>
 * @date 2021/11/18
 */
class PasswordProxy extends BaseFormUnitProxy
{

    /**
     * 设置代理表单
     * @return string
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/11/18
     */
    protected function proxyUnit(): string
    {
        return PasswordEntity::class;
    }
}