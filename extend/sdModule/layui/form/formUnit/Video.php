<?php
/**
 * Date: 2020/9/26 16:02
 * User: chenlong <vip_chenlong@163.com>
 */

namespace sdModule\layui\form\formUnit;


class Video extends UnitBase
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
                        <i class="layui-icon layui-icon-upload"></i>选择视频</button>
                </div>
                <div class="layui-upload-list">
                    <video class="layui-upload-img" controls="controls" src="" style="max-width: 500px;" id="{$this->name}_show" ></video>
                    <p id="{$this->name}_tip"></p>
                </div>
            </div>
HTML;
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

    /**
     * @return mixed|string
     */
    public function getJs()
    {
        return <<<JS
    window.{$this->name} = custom.videoUpload('{$this->name}');
    defaultData.{$this->name} = function(){
         {$this->name}.defaults('{$this->default}');
    };
JS;

    }
}
