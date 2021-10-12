<?php
/**
 * Date: 2020/9/26 16:42
 * User: chenlong <vip_chenlong@163.com>
 */

namespace sdModule\layui\form\formUnit;


use sdModule\layui\Dom;

class Radio extends UnitBase
{


    /**
     * @param array $attr
     * @return mixed|string
     */
    public function getHtml(array $attr):Dom
    {
        $itemDom  = $this->getItem();
        $inputDiv = Dom::create();

        foreach ($this->options as $value => $label) {
            $customAttr = [
                'type'          => 'radio',
                'lay-filter'    => "filter-$this->name",
                'value'         => $value,
                'title'         => $label
            ];
            $this->getCheck($value) and $customAttr['checked'] = '';
            $inputDiv->addContent($this->getInput()->addAttr($customAttr)->addAttr($attr));
        }

        if ($this->label) {
            $itemDom->addContent($this->getLabel($this->label));
            $inputDiv->addClass($this->shortTip ? 'layui-inline' : 'layui-input-block');
        }else{
            return $inputDiv->addClass('layui-inline');
        }

        return $itemDom->addContent($inputDiv);
    }

    /**
     * 获取选中状态
     * @param $value
     * @return bool
     */
    private function getCheck($value): bool
    {
        if ($this->default === '' || $this->default === null) {
            return false;
        }
        return $value == $this->default;
    }

    public function getJs() :string
    {
        list($where_str, $default) = $this->getShowWhereJs();
        return  !$this->showWhere ? '' : <<<JS
        $default
        layui.form.on('radio(filter-$this->name)', function(data){
          let value = data.value;
          $where_str
        });
JS;
    }
}
