<?php
/**
 *
 * Text.php
 * User: ChenLong
 * DateTime: 2020/5/25 13:21
 */


namespace sdModule\layui\formMake\unit;


class Image implements Unit
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
                <div class="layui-upload-list">
                    <img class="layui-upload-img" src="" style="max-width: 300px;" id="{$name}_show" alt=""/>
                    <p id="{$name}_tip"></p>
                </div>
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

    window.{$name} = custom.upload(layui.jquery, layui.upload, '{$name}');

JSR;
    }

    public function defaultJsCode($name, $default)
    {
        $content = $default[$name] ?? '';
        return <<<JSR
          {$name}.defaults('{$content}');
JSR;
    }

}

