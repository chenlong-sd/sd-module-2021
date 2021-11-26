<?php
/**
 * datetime: 2021/11/25 0:13
 * user    : chenlong<vip_chenlong@163.com>
 **/

namespace sdModule\layui\form4\formUnit\unitEntity;

use sdModule\layui\Dom;
use sdModule\layui\form4\formUnit\FormUnitT;
use sdModule\layui\form4\formUnit\unit\Hidden;
use sdModule\layui\form4\formUnit\UnitI;

class HiddenEntity extends Hidden implements UnitI
{
    use FormUnitT;

    /**
     * @param string $scene 表单场景
     * @return Dom
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/11/18
     */
    public function getElement(string $scene): Dom
    {
        return $this->getInputElement()->addAttr('type', 'hidden')
            ->addAttr($this->getCurrentSceneInputAttr($scene))
            ->addAttr('value', $this->defaultValue);
    }

}