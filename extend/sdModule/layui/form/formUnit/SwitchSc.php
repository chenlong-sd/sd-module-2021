<?php
/**
 * Date: 2020/12/7 13:37
 * User: chenlong <vip_chenlong@163.com>
 */

namespace sdModule\layui\form\formUnit;


use sdModule\layui\Dom;

class SwitchSc extends UnitBase
{

    /**
     * @param array $attr
     * @return Dom
     * @author chenlong <vip_chenlong@163.com>
     * @date 2021/6/2
     */
    public function getHtml(array $attr): Dom
    {
        $title = implode('|', $this->options);

        $itemDom  = $this->getItem();
        $inputDiv = Dom::create();
        $input    = Dom::create('input')->setSingleLabel()->addAttr([
            'type'          => 'checkbox',
            'lay-filter'    => $this->name,
            'lay-skin'      => 'switch',
            'lay-text'      => $title,
        ])->addAttr($attr);

        // 第一个值等于默认值时，为选中状态
        if ($this->default == array_key_first($this->options)) {
            $input->addAttr('checked', '');
        }
        $hidden = Dom::create('input')->setSingleLabel()->addAttr([
            'type' => 'hidden',
            'name' => $this->name,
            'value' => $this->default ?: array_key_last($this->options)
        ]);

        if ($this->label) {
            $itemDom->addContent($this->getLabel($this->label));
            $inputDiv->addClass($this->shortTip ? 'layui-inline' : 'layui-input-block');
        }else{
            $inputDiv->addClass('layui-input-inline');
            return $inputDiv->addContent($input)->addContent($hidden);
        }

        return $itemDom->addContent($inputDiv->addContent($input)->addContent($hidden))->addContent($this->getShortTip());
    }

    /**
     * @return string
     * @author chenlong <vip_chenlong@163.com>
     * @date 2021/6/2
     */
    public function getJs(): string
    {
        $open_value  = array_key_first($this->options);
        $close_value = array_key_last($this->options);
        return <<<JS
    form.on('switch({$this->name})', function(data){
        let value = data.elem.checked ? "{$open_value}" : "{$close_value}";
        layui.jquery("input[name={$this->name}]").val(value);
    });
JS;
    }
}
