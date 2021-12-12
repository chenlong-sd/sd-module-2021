<?php
/**
 * datetime: 2021/11/18 13:39
 * user    : chenlong<vip_chenlong@163.com>
 **/

namespace sdModule\layui\form4\formUnit\unitEntity;
use sdModule\layui\Dom;
use sdModule\layui\form4\formUnit\{FormUnitT, unit\Text, UnitI};

/**
 * Class Text
 * @package sdModule\layui\form4\formUnit\unitEntity
 * @author chenlong<vip_chenlong@163.com>
 * @date 2021/11/18
 */
class TextEntity extends Text implements UnitI
{
    use FormUnitT;

    /**
     * @param string $scene
     * @return Dom
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/11/18
     */
    public function getElement(string $scene): Dom
    {
        $inputDiv = Dom::create()->addClass('layui-input-wrap');
        if ($this->prefixIconClass) {
            $inputDiv->addClass('layui-input-wrap-prefix');
        }

        $inputAttr = array_merge([
            'type'          => 'text',
            'placeholder'   => $this->placeholder,
            'value'         => $this->defaultValue,
        ], $this->getCurrentSceneInputAttr($scene));

        if ($this->suffixIsFunctionIcon) {
            $inputAttr['lay-affix'] = $this->suffixIconClass;
        }

        $input    = $this->getInputElement()->addAttr($inputAttr);

        // 表单的预选项
        if ($this->options) {
            $input->addAttr('list', 'datalist-' . $this->name);
            $inputDiv->addContent($this->optionsHandle());
        }

        if ($this->label) {
            $item = $this->getItemElement();
            // =添加label
            $item->addContent($this->getLabelElement($this->label));
            // 如果有提示，给标签加上不换行class
            $inputDiv->addClass($this->shortTip ? 'layui-inline' : 'layui-input-block');

            return $item->addContent($inputDiv->addContent($input)->addContent($this->iconElement())) // 追加input的div
                ->addContent($this->getShortTipElement($this->shortTip)); // 追加提示的
        }

        $inputDiv->addClass('layui-inline');
        $inputDiv->addContent($input)->addContent($this->iconElement());
        return $inputDiv;
    }



    /**
     * @return Dom
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/8/20
     */
    private function optionsHandle(): Dom
    {
        $datalist = Dom::create('datalist')->setId('datalist-' . $this->name);
        foreach ($this->options as $value){
            $datalist->addContent(Dom::create('option')->addAttr('value', $value));
        }
        return $datalist;
    }

    /**
     * @return string
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/11/18
     */
    private function getShowWhereJs(): string
    {
        if (empty($this->showWhere['value'])) {
            return '';
        }

        $whereObj = json_encode($this->showWhere['value'], JSON_UNESCAPED_UNICODE);

        return <<<JS
        window['sc-4-text-$this->name'] = $whereObj;
        layui.jquery('input[name=$this->name]').on('change', function (){
           let event_value = layui.jquery(this).val();
           window['sc4ControlShow'](window['sc-4-text-$this->name'], event_value);
        });
        $(()=>window['sc4ControlShow'](window['sc-4-text-$this->name'], "$this->defaultValue"))
JS;
    }

    /**
     * @return string
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/11/18
     */
    public function getJs(): string
    {
        return $this->getShowWhereJs();
    }
}
