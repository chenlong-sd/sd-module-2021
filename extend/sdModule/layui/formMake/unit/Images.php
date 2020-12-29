<?php
/**
 *
 * Text.php
 * User: ChenLong
 * DateTime: 2020/5/25 13:21
 */


namespace sdModule\layui\formMake\unit;


class Images implements Unit
{
    /**
     * html 代码
     * @param $label
     * @param $name
     * @return string
     */
    public function htmlCode(string $label, string $name): string
    {
        return <<<HTML
          <div class="layui-form-item">
    <label class="layui-form-label">{$label}</label>
    <div class="layui-input-block">
    <div class="layui-upload">
        <input type="hidden" name="{$name}">
        <div class="layui-btn-group">
            <button type="button" class="layui-btn" id="{$name}">
                <i class="layui-icon layui-icon-upload"></i>选择图片</button>
            <button type="button" class="layui-btn layui-btn-normal" id="{$name}-select">
                <i class="layui-icon layui-icon-picture"></i>系统图片</button>
        </div>
        <blockquote class="layui-elem-quote layui-quote-nm" style="margin-top: 10px;">
            预览图：
            <div class="layui-upload-list" id="{$name}-show" style="overflow: hidden"></div>
        </blockquote>
    </div>
</div>
</div>
HTML;

    }

    /**
     * js 代码
     * @param string $name
     * @return string
     */
    public function jsCode(string $name): string
    {
        return <<<JSR
        
     window.{$name} = custom.moreUpload(layui.jquery, '{$name}', layui.upload).init();

JSR;
    }



    public function defaultJsCode($name, $default)
    {
        $content = $default[$name] ?? '';
        return <<<JSR
         {$name}.init('$content'.split(','));
JSR;
    }
}

