<?php
/**
 * Date: 2021/6/4 11:12
 * User: chenlong <vip_chenlong@163.com>
 */

namespace sdModule\layui\form\formUnit;


use sdModule\layui\Dom;

class Color extends UnitBase
{
    /**
     * @param array $attr
     * @return Dom
     * @author chenlong <vip_chenlong@163.com>
     * @date 2021/6/4
     */
    public function getHtml(array $attr): Dom
    {
        $itemDom  = $this->getItem();
        $inputDiv = Dom::create();
        $input    = $this->getInput()->addAttr('type', 'text')->setId("{$this->name}-color-input")
            ->addAttr($attr)->addAttr('value', $this->default);

        $color = Dom::create()->addClass('layui-inline')
            ->addAttr('style', 'left:-11px;height:38px')
            ->addContent(Dom::create()->setId("{$this->name}-color"));

        if ($this->label) {
            $itemDom->addContent($this->getLabel($this->label));
            $inputDiv->addClass('layui-input-inline');
        }else{
            $inputDiv->addClass('layui-input-inline');
            return $inputDiv->addContent($input);
        }

        return $itemDom->addContent($inputDiv->addContent($input))
            ->addContent($color)
            ->addContent($this->getShortTip());
    }

    /**
     * @return string
     * @author chenlong <vip_chenlong@163.com>
     * @date 2021/6/4
     */
    public function getJs(): string
    {
        $predefine = isset($this->config['predefine']) ? 1 : 0;
        $colors    = json_encode($this->config['predefine'] ?? []);
        $alpha     = $this->config['alpha'] ?? 1;
        $format    = $this->config['format'] ?? 'hex';
        return <<<JS

    layui.colorpicker.render({
        elem: '#{$this->name}-color'
        ,color: '{$this->default}'
        ,predefine: {$predefine} // 开启预定义颜色
        ,alpha: {$alpha} //开启透明度
        ,format: '{$format}' //开启透明度
        ,colors: {$colors} //预定义颜色
        ,done: function(color){
            $('#{$this->name}-color-input').val(color);
        }
    });

JS;

    }
}
