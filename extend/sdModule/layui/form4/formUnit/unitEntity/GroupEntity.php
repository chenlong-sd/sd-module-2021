<?php
/**
 * datetime: 2021/11/19 17:04
 * user    : chenlong<vip_chenlong@163.com>
 **/

namespace sdModule\layui\form4\formUnit\unitEntity;

use sdModule\layui\Dom;
use sdModule\layui\form4\formUnit\{FormUnitT, unit\Group, UnitI};

class GroupEntity extends Group implements UnitI
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
        $item = $this->getItemElement();

        if ($this->label) {
            $item->addContent($this->getLabelElement($this->label));
        }

        foreach ($this->childrenItem as $unit) {
            $newDom = $unit->itemClass(['layui-inline'])->getElement($scene);
            $item->addContent($newDom);
        }
        return $item->setId($this->formUnitId);
    }

    /**
     * @return string
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/11/19
     */
    public function getJs():string
    {
        return implode(array_map(function ($v){return $v->getJs();}, $this->childrenItem));
    }

    /**
     * @return array
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/11/19
     */
    public function getLoadJs():array
    {
        return array_merge(...array_map(function ($v){return $v->getLoadJs();}, $this->childrenItem));
    }

    /**
     * @return string
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/11/19
     */
    public function getCss(): string
    {
        return implode(array_map(function ($v){return $v->getCss();}, $this->childrenItem));
    }

    /**
     * @return array|\sdModule\layui\form4\formUnit\BaseFormUnitProxy[]|UnitI[]
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/11/26
     */
    public function getChildren(): array
    {
        return $this->childrenItem;
    }
}

