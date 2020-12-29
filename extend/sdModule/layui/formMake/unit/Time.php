<?php
/**
 *
 * Text.php
 * User: ChenLong
 * DateTime: 2020/5/25 13:21
 */


namespace sdModule\layui\formMake\unit;


class Time implements Unit
{
    public $data = 'date';

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
      <input type="text" name="{$name}" id="{$name}-sc" required  placeholder="请输入" autocomplete="off" class="layui-input">
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
        $range = 'false';
        if (is_array($this->data)) {
            list($this->data) = $this->data;
            $range = 'true';
        }

        return <<<JSR
        
  layui.laydate.render({
    elem: '#{$name}-sc'
    ,type: '{$this->data}'
    ,range: {$range}
  });

JSR;
    }

}

