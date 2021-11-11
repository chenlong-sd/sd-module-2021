<?php
/**
 * datetime: 2021/8/5 22:57
 * user    : chenlong<vip_chenlong@163.com>
 **/


namespace sdModule\layui\form\formUnit;


use app\common\SdException;
use sdModule\layui\Dom;
use think\helper\Str;

class Table extends UnitBase
{
    private $unit_js = '';
    private $unit_js_call = '';

    /**
     * @param array $attr
     * @return Dom
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/8/5
     */
    public function getHtml(array $attr): Dom
    {
        $table = Dom::create('table')->addClass('layui-table')->setId($this->boxID);
        $title_tr = Dom::create('tr')->addAttr([
            'style' => 'background-color:#f8f8f8'
        ]);
        /**
         * @var  UnitBase $children
         * @var  array $attr
         */
        foreach ($this->childrenItem as [$children, $attr]) {
            $title_tr->addContent(Dom::create('td')->addClass('s-title')->addContent($children->label));
        }

        $table->addContent($title_tr);

        if ($this->default) {
            foreach ($this->default as $default){
                $table->addContent($this->inputTr($default));
            }
        }else{
            $table->addContent($this->inputTr());
        }

        return $table;
    }

    /**
     * 表单行
     * @return Dom
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/8/6
     */
    private function inputTr(array $default = []): Dom
    {
        $form_tr = Dom::create('tr');
        /**
         * @var  UnitBase $children
         * @var  array $attr
         */
        foreach ($this->childrenItem as [$children, $attr]) {
            $type = explode('\\', get_class($children));
            $type = Str::camel(end($type));
            if (!method_exists($this, $type)) {
                throw new \Exception("table包含表单支持项：text, select, checkbox, time, 你使用了【{$type}】类型" );
            }

            $form_tr->addContent(Dom::create('td')->addContent(call_user_func([$this, $type], $children, $attr, $default)));
        }
        return $form_tr;
    }

    /**
     * @return string
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/8/6
     */
    public function getJs(): string
    {
        return <<<JS
        \$('#{$this->boxID}').on('click', 'tr:last-child>td:last-child', function (e) {
            e.stopPropagation();
            let html = `{$this->inputTr()}`;
            \$('#{$this->boxID}').find('tr:last-child').after(html);
            $this->unit_js_call
            layui.form.render();
        }).on('click', 'tr:not(:last-child)>td:last-child:not(.s-title)', function (e){
            e.stopPropagation();
            \$(this).parent('tr').remove();
        }).on('click', '*', function (e){
            e.stopPropagation();
        });

        $this->unit_js
JS;
    }

    /**
     * @return string
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/8/6
     */
    public function getCss(): string
    {
        return <<<CSS

<style>
    table#{$this->boxID} {
        table-layout: fixed;
    }
    table#{$this->boxID} td{
        padding: 0 !important;
        text-align: center;
    }
    table#{$this->boxID} input{
        border: none !important;
        text-align: center;
    }
    #{$this->boxID} tr:last-child td:last-child,#{$this->boxID} tr:not(:last-child)>td:last-child:not(.s-title){
        pointer-events: none;
    }
    #{$this->boxID} tr:last-child td:last-child *,#{$this->boxID} tr:not(:last-child)>td:last-child:not(.s-title) *{
        pointer-events: auto;
    }
    #{$this->boxID} tr:last-child td:last-child:after{
        content: '\\e624';
        position: absolute;
        right: -25px;
        bottom: 7px;
        font-size: 20px;
        color: green;
        font-family:layui-icon!important;
        pointer-events:auto;
    }
    #{$this->boxID} tr:last-child>td:last-child:hover:after{
        cursor: pointer;
        font-weight: bold;
    }
    #{$this->boxID} tr{
        position: relative;
    }
    #{$this->boxID} tr:not(:last-child)>td:last-child:not(.s-title):after{
        content: '\\e640';
        position: absolute;
        right: -28px;
        bottom: 7px;
        font-size: 20px;
        font-family:layui-icon!important;
        pointer-events:auto;
        color: rgba(255, 0, 0, 0.66);
        width: 30px;
    }
    #{$this->boxID} tr:not(:last-child)>td:last-child:not(.s-title):hover:after{
        cursor: pointer;
        color: red;
    }
    .s-title{
        font-weight: bold;
        height: 38px;
    }
</style>

CSS;

    }

    /**
     * @param UnitBase $unitBase
     * @param array $attr
     * @param array $default
     * @return Dom
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/8/6
     */
    private function text(UnitBase $unitBase, array $attr, array $default)
    {
        $inputDiv = Dom::create();
        $input = Dom::create('input')->setSingleLabel()
            ->addAttr([
                'name'         => "$unitBase->name[]",
                'class'        => 'layui-input',
                'autocomplete' => 'off',
                'placeholder'  => $unitBase->placeholder,
                'value'        => $default[$unitBase->name] ?? $unitBase->default
            ])->addAttr($attr);

        if ($unitBase->options) {
            $input->addAttr('list', 'datalist-' . $unitBase->name);
            $datalist = Dom::create('datalist')->setId('datalist-' . $unitBase->name);
            foreach ($unitBase->options as $value){
                $datalist->addContent(Dom::create('option')->addAttr('value', $value));
            }
            $inputDiv->addContent($datalist);
        }
        $inputDiv->addContent($input);

        return $inputDiv;
    }

    /**
     * @param UnitBase $unitBase
     * @param array $attr
     * @param array $default
     * @return Dom
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/8/6
     */
    private function select(UnitBase $unitBase, array $attr, array $default)
    {
        $options  = [
            Dom::create('option')->addContent($unitBase->placeholder)->addAttr('value', '')
        ];
        foreach ($unitBase->options as $value => $label) {
            if (is_array($label)) { // 有分组选项时
                $optgroup = Dom::create('optgroup')->addAttr('label', $value);
                foreach ($label as $value_children => $label_children){
                    $option = Dom::create('option')->addContent($label_children)->addAttr('value', $value_children);
                    if (!empty($default[$unitBase->name])){
                        $value_children == $default[$unitBase->name] and $option->addAttr('selected', '');
                    }else{
                        $value_children == $unitBase->default and $option->addAttr('selected', '');
                    }
                    $optgroup->addContent($option);
                }
                $options[] = $optgroup;
            }else{
                $option = Dom::create('option')->addContent($label)->addAttr('value', $value);
                if (!empty($default[$unitBase->name])){
                    $value == $default[$unitBase->name] and $option->addAttr('selected', '');
                }else{
                    $value == $unitBase->default and $option->addAttr('selected', '');
                }
                $options[] = $option;
            }
        }

        return Dom::create('select')
            ->addAttr('name', "$unitBase->name[]")
            ->addAttr('lay-search', '')
            ->addContent(implode($options))->addAttr($attr);
    }

    /**
     * 多选形式的表单
     * @param UnitBase $unitBase
     * @param array $attr
     * @param array $default
     * @return Dom
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/8/6
     */
    private function checkbox(UnitBase $unitBase, array $attr, array $default)
    {
        $inputDiv = Dom::create();
        $default = isset($default[$unitBase->name]) ? explode(',', $default[$unitBase->name] ?: '') : ($unitBase->default ?: []);

        foreach ($unitBase->options as $value => $label) {
            $customAttr = [
                'type'      => 'checkbox',
                'lay-skin'  => 'primary',
                'value'     => $value,
                'title'     => $label,
                'name'      => "$unitBase->name[]"
            ];

            in_array($value, $default) and $customAttr['checked'] = '';
            $inputDiv->addContent(Dom::create('input')->setSingleLabel()
                ->addAttr([
                    'class'        => 'layui-input',
                    'autocomplete' => 'off',
                    'placeholder'  => $unitBase->placeholder
                ])->addAttr($customAttr)->addAttr($attr));
        }
        return $inputDiv;
    }

    private function time(UnitBase $unitBase, array $attr, array $default)
    {
        $id = $unitBase->name;
        $range = is_bool($unitBase->config['range'])
            ? ($unitBase->config['range'] ? 'true' : 'false')
            : "'{$unitBase->config['range']}'";
        $type = $unitBase->config['type'] ?? 'date';

        $this->unit_js .= <<<JS
            
          function table_time_render_{$id}(){
             layui.lay('.layui-form .sc-time-render-$id').each(function(){
                  layui.laydate.render({
                    elem: this
                    ,trigger: 'click'
                    ,type: '{$type}'
                    ,range: {$range}
                  });
            });
          }
          table_time_render_{$id}();

JS;
        $this->unit_js_call .= "table_time_render_{$id}();";

        return Dom::create('input')->setSingleLabel()
            ->addAttr([
                'name'         => "$unitBase->name[]",
                'class'        => "layui-input sc-time-render-$id",
                'autocomplete' => 'off',
                'placeholder'  => $unitBase->placeholder,
                'value'        => $default[$unitBase->name] ?? $unitBase->default
            ])->addAttr($attr);
    }
}

