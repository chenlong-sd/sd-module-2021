<?php
/**
 * Date: 2020/9/26 16:14
 * User: chenlong <vip_chenlong@163.com>
 */

namespace sdModule\layui\form\formUnit;


class Images extends UnitBase
{
    public ?string $default = '';

    /**
     * @param string $attr
     * @return mixed|string
     */
    public function getHtml(string $attr)
    {
        return <<<HTML
            <div class="layui-upload">
                <input type="hidden" name="{$this->name}">
                <div class="layui-btn-group">
                    <button type="button" class="layui-btn" id="{$this->name}">
                        <i class="layui-icon layui-icon-upload"></i>选择图片</button>
                    {$this->systemResource()}
                </div>
                <blockquote class="layui-elem-quote layui-quote-nm" style="margin-top: 10px;">
                    <div>图片预览：</div>
                    <div class="layui-upload-list" id="{$this->name}-show" style="overflow: hidden"></div>
                </blockquote>
            </div>
HTML;

    }

    /**
     * @return mixed|string
     */
    public function getJs()
    {
        return <<<JS
    window.{$this->name} = custom.moreUpload(layui.jquery, '{$this->name}', layui.upload).init();
    defaultData.{$this->name} = function(){
         {$this->name}.init('{$this->default}'.split(','));
    };
JS;

    }

    /**
     * 系统资源
     * @return string
     */
    private function systemResource()
    {
        return !env('SYSTEM_RESOURCE')  ? "" : <<<SYS
<button type="button" class="layui-btn layui-btn-normal" id="{$this->name}-select"><i class="layui-icon layui-icon-picture"></i>系统图片</button>
SYS;

    }
}
