<?php
/**
 * Date: 2020/10/26 14:48
 * User: chenlong <vip_chenlong@163.com>
 */

namespace sdModule\layui\defaultForm\formUnit;


class Tag extends UnitBase
{
    public ?string $default = '';

    /**
     * @param string $attr
     * @return mixed|string
     */
    public function getHtml(string $attr)
    {
        $placeholder = $this->placeholder ?: $this->lang('press enter after typing');
        return <<<HTML
        <div class="tags sc-input-tags">
                <input type="text" {$attr} placeholder="{$placeholder}" name="" id="sc-tag-{$this->name}" value='' autocomplete="off" class="layui-input layui-fluid sc-tag-input">
                <input type="hidden" name="{$this->name}" value="">
        </div>
HTML;
    }

    public function getJs()
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
        $("#sc-tag-{$this->name}").on('blur', ()=>{
             window.submit_sc = false;
        }).on('focus', ()=> {
             window.submit_sc = true;
        })
    });
       
JS;

    }
}
