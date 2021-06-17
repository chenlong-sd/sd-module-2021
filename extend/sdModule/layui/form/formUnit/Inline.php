<?php
/**
 * Date: 2021/6/1 16:16
 * User: chenlong <vip_chenlong@163.com>
 */

namespace sdModule\layui\form\formUnit;


use sdModule\layui\Dom;

/**
 * 行内元素组件
 * Class Inline
 * @package sdModule\layui\form\formUnit
 * @author chenlong <vip_chenlong@163.com>
 * @date 2021/6/1
 */
class Inline extends UnitBase
{
    /**
     * @param array $attr
     * @return Dom
     * @author chenlong <vip_chenlong@163.com>
     * @date 2021/6/1
     */
    public function getHtml(array $attr): Dom
    {
        $item = $this->getItem();

        if ($this->label) {
            $item->addContent($this->getLabel($this->label));
        }

        foreach ($this->childrenItem as [$dom, $attr]) {
            /** @var Dom $newDom */
            $newDom = $dom->setItemClass('layui-inline')->getHtml($attr);
            if (isset($attr['pane'])){
                $newDom->addAttr('pane', '');
            }
            $item->addContent($newDom);
        }
        return $item;
    }
}
