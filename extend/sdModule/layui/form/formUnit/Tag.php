<?php
/**
 * Date: 2020/10/26 14:48
 * User: chenlong <vip_chenlong@163.com>
 */

namespace sdModule\layui\form\formUnit;


use sdModule\layui\Dom;

class Tag extends UnitBase
{

    /**
     * @param array $attr
     * @return Dom
     * @author chenlong <vip_chenlong@163.com>
     * @date 2021/6/1
     */
    public function getHtml(array $attr): Dom
    {
        $itemDom  = $this->getItem();
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
                'placeholder' => $this->placeholder ?: $this->lang('press enter after typing'),
                'id' => "sc-tag-{$this->name}",
                'autocomplete' => "off",
                'class' => "layui-input layui-fluid sc-tag-input",
            ])->addAttr($attr)
        );
        if ($this->label) {
            $itemDom->addContent($this->getLabel($this->label));
            $inputDiv->addClass($this->shortTip ? 'layui-inline' : 'layui-input-block');
        }else{
            $inputDiv->addClass('layui-inline');
            return $inputDiv->addContent($tagDiv);
        }

        return $itemDom->addContent($inputDiv->addContent($tagDiv))->addContent($this->getShortTip());
    }

    /**
     * @return string
     * @author chenlong <vip_chenlong@163.com>
     * @date 2021/6/1
     */
    public function getJs(): string
    {
        $default = $this->default ? json_encode(explode('|-|', $this->default), JSON_UNESCAPED_UNICODE) : '[]';
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
        $('input[name={$this->name}]').val({$default}.join('|-|'));
        $("#sc-tag-{$this->name}").on('blur', ()=>{
             window.submit_sc = false;
        }).on('focus', ()=> {
             window.submit_sc = true;
        })
    });
       
JS;

    }
}
