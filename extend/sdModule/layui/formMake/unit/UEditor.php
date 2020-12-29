<?php
/**
 *
 * Text.php
 * User: ChenLong
 * DateTime: 2020/5/25 13:21
 */


namespace sdModule\layui\formMake\unit;


class UEditor implements Unit
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
      <script id="{$name}-ue" name="{$name}" type="text/plain"></script>
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

    let ue_{$name} = custom.editorRender(UE, "{$name}-ue");

JSR;
    }


    public function defaultJsCode($name, $default)
    {
        $content = html_entity_decode($default[$name] ?? '');
        return <<<JSR
            ue_{$name}.ready(()=>{
                 ue_{$name}.setContent('{$content}');
            });
JSR;
    }

}

