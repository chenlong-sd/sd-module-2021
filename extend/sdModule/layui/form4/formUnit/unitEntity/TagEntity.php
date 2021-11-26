<?php
/**
 * datetime: 2021/11/25 15:23
 * user    : chenlong<vip_chenlong@163.com>
 **/

namespace sdModule\layui\form4\formUnit\unitEntity;

use sdModule\layui\Dom;
use sdModule\layui\form4\formUnit\FormUnitT;
use sdModule\layui\form4\formUnit\unit\Tag;
use sdModule\layui\form4\formUnit\UnitI;

class TagEntity  extends Tag implements UnitI
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
        $itemDom  = $this->getItemElement();
        $inputDiv = Dom::create();
        $tagDiv   = Dom::create()->addClass('tags sc-input-tags');
        $tagDiv->addContent(
            Dom::create('input')->setSingleLabel()->addAttr('name', $this->name)
                ->addAttr('value', '')->addAttr('type', 'hidden')
        );
        $tagDiv->addContent(
            Dom::create('input')->setSingleLabel()
                ->addAttr([
                    'type' => 'text',
                    'placeholder' => $this->placeholder ?: '输入后按回车',
                    'id' => "sc-tag-$this->name",
                    'autocomplete' => "off",
                    'class' => "layui-input layui-fluid sc-tag-input",
                ])->addAttr($this->getCurrentSceneInputAttr($scene))
        );
        if ($this->label) {
            $itemDom->addContent($this->getLabelElement($this->label));
            $inputDiv->addClass($this->shortTip ? 'layui-inline' : 'layui-input-block');
            return $itemDom->addContent($inputDiv->addContent($tagDiv))->addContent($this->getShortTipElement($this->shortTip));
        }

        $inputDiv->addClass('layui-inline');
        return $inputDiv->addContent($tagDiv);

    }

    /**
     * @return string
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/11/25
     */
    public function getJs(): string
    {
        $default = [];
        if ($this->defaultValue) {
            $default = is_array($this->defaultValue) ? $this->defaultValue : explode('|-|', $this->defaultValue);
        }

        $default = json_encode($default, JSON_UNESCAPED_UNICODE);

        return <<<JS
    layui.use('inputTags', function() {
       var inputTags = layui.inputTags;
        inputTags.render({
            elem:'#sc-tag-{$this->name}',//定义输入框input对象
            content: {$default},//默认标签
            name:"{$this->name}",
            done: function(value){ //回车后的回调
               return false;
            },
            change(data){
                $('input[name={$this->name}]').val(data.join('|-|'));
                return false;
            }
        });
        $('input[name=$this->name]').val({$default}.join('|-|'));
        $("#sc-tag-{$this->name}").on('blur', ()=>{
             window.submit_sc = false;
        }).on('focus', ()=> {
             window.submit_sc = true;
        })
    });
       
JS;

    }

}