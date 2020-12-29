<?php
/**
 *
 * Select.php
 * User: ChenLong
 * DateTime: 2020/5/25 14:12
 */


namespace sdModule\layui\formMake\unit;


class Select implements Unit
{
    public $data = [];

    /**
     * html 代码
     * @param string $label
     * @param string $name
     * @return string
     */
    public function htmlCode(string $label, string $name): string
    {
        return <<<HTML
  <div class="layui-form-item">
    <label class="layui-form-label">{$label}</label>
    <div class="layui-input-block">
      <select name="{$name}" lay-search="">
        <option value=""></option>
        {$this->option()}
      </select>
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

    /**
     * @return string
     */
    private function option()
    {
        $html = '';
        foreach ($this->data as $key => $value) {
            $html .= '<option value="' . $key . '">' . $value . '</option>>';
        }
        return $html;
    }
}

