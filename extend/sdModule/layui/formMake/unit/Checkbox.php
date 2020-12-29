<?php
/**
 *
 * Text.php
 * User: ChenLong
 * DateTime: 2020/5/25 13:21
 */


namespace sdModule\layui\formMake\unit;


class Checkbox implements Unit
{
    public $data;

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
      {$this->option($name)}
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
        return '';
    }

    private function option($name)
    {
        $html = '';
        foreach ($this->data as $key => $value) {
            $html .= "<input type=\"checkbox\" name=\"{$name}\" lay-skin=\"primary\" value=\"{$key}\" title=\"{$value}\">";
        }
        return $html;
    }
}

