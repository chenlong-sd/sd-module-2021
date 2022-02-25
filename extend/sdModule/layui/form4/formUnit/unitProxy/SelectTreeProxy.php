<?php
/**
 * datetime: 2021/11/25 15:02
 * user    : chenlong<vip_chenlong@163.com>
 **/

namespace sdModule\layui\form4\formUnit\unitProxy;

use sdModule\layui\form4\formUnit\BaseFormUnitProxy;
use sdModule\layui\form4\formUnit\unit\SelectTree;
use sdModule\layui\form4\formUnit\unitEntity\SelectTreeEntity;

/**
 * Class SelectsProxy
 * @mixin SelectTree
 * @package sdModule\layui\form4\formUnit\unitProxy
 * @author chenlong<vip_chenlong@163.com>
 * @date 2021/11/25
 */
class SelectTreeProxy extends BaseFormUnitProxy
{

    /**
     * 设置代理表单
     * @return string
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/11/18
     */
    protected function proxyUnit(): string
    {
        return SelectTreeEntity::class;
    }
}