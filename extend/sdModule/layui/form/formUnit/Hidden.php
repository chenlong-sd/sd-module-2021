<?php
/**
 * Date: 2020/9/26 10:34
 * User: chenlong <vip_chenlong@163.com>
 */

namespace sdModule\layui\form\formUnit;


use sdModule\layui\Dom;

class Hidden extends UnitBase
{

    /**
     * @param array $attr
     * @return Dom
     * @author chenlong <vip_chenlong@163.com>
     * @date 2021/6/1
     */
    public function getHtml(array $attr): Dom
    {
        return $this->getInput()->addAttr('type', 'hidden')
            ->addAttr($attr)->addAttr('value', $this->default);
    }
}
