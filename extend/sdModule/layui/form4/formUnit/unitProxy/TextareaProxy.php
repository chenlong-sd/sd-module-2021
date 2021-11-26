<?php
/**
 * datetime: 2021/11/24 23:41
 * user    : chenlong<vip_chenlong@163.com>
 **/

namespace sdModule\layui\form4\formUnit\unitProxy;

use sdModule\layui\form4\formUnit\BaseFormUnitProxy;
use sdModule\layui\form4\formUnit\unit\Textarea;
use sdModule\layui\form4\formUnit\unitEntity\TextareaEntity;

/**
 * Class TextareaProxy
 * @mixin Textarea
 * @package sdModule\layui\form4\formUnit\unitProxy
 * @author chenlong<vip_chenlong@163.com>
 * @date 2021/11/24
 */
class TextareaProxy extends BaseFormUnitProxy
{

    /**
     * 设置代理表单
     * @return string
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/11/18
     */
    protected function proxyUnit(): string
    {
        return TextareaEntity::class;
    }
}