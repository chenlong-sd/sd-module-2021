<?php
/**
 * datetime: 2021/11/25 14:27
 * user    : chenlong<vip_chenlong@163.com>
 **/

namespace sdModule\layui\form4\formUnit\unit;

use sdModule\layui\Dom;
use sdModule\layui\form4\formUnit\BaseFormUnit;

/**
 * Class Customize
 * @package sdModule\layui\form4\formUnit\unit
 * @author chenlong<vip_chenlong@163.com>
 * @date 2021/11/25
 */
abstract class Customize extends BaseFormUnit
{
    /**
     * @var Dom
     */
    protected $element = null;

    /**
     * 设置自定义的元素内容
     * @param Dom $element
     * @return Customize
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/11/25
     */
    public function setElement(Dom $element): Customize
    {
        $this->element = $element;
        return $this;
    }
}
