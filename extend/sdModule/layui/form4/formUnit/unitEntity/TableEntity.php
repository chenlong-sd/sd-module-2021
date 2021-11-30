<?php
/**
 * datetime: 2021/11/20 1:58
 * user    : chenlong<vip_chenlong@163.com>
 **/

namespace sdModule\layui\form4\formUnit\unitEntity;

use sdModule\layui\Dom;
use sdModule\layui\form4\formUnit\{FormUnitT,
    unit\Table,
    UnitI,
    unitProxy\CheckboxProxy,
    unitProxy\SelectProxy,
    unitProxy\TextProxy,
    unitProxy\TimeProxy};
use think\helper\Str;

class TableEntity extends Table implements UnitI
{
    use FormUnitT;

    private $unit_js = [];
    /**
     * @var string
     */
    private $unit_call_js = '';
    /**
     * @var int
     */
    private $currentTrIndex = 0;

    /**
     * @param string $scene 表单场景
     * @return Dom
     * @throws \Exception
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/11/18
     */
    public function getElement(string $scene): Dom
    {
        $table = Dom::create('table')->addClass('layui-table')->setId($this->formUnitId);
        $title_tr = Dom::create('tr')->addAttr([
            'style' => 'background-color:#f8f8f8'
        ]);

        foreach ($this->childrenItem as $children) {
            $title_tr->addContent(Dom::create('td')->addClass('s-title')
                ->addContent($children->getLabel()));
        }

        $table->addContent($title_tr);

        if ($this->defaultValue) {
            foreach ($this->defaultValue as $index => $default){
                $this->currentTrIndex = $index;
                $table->addContent($this->inputTr($default));
            }
        }else{
            $table->addContent($this->inputTr());
        }

        return $table;
    }

    /**
     * @return string
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/11/26
     */
    public function getCss(): string
    {
        return <<<CSS

<style>
    table#{$this->formUnitId} {
        table-layout: fixed;
    }
    table#{$this->formUnitId} td{
        padding: 0 !important;
        text-align: center;
    }
    table#{$this->formUnitId} input{
        border: none !important;
        text-align: center;
    }
    #{$this->formUnitId} tr:last-child td:last-child,#{$this->formUnitId} tr:not(:last-child)>td:last-child:not(.s-title){
        pointer-events: none;
    }
    #{$this->formUnitId} tr:last-child td:last-child *,#{$this->formUnitId} tr:not(:last-child)>td:last-child:not(.s-title) *{
        pointer-events: auto;
    }
    #{$this->formUnitId} tr:last-child td:last-child:after{
        content: '\\e624';
        position: absolute;
        right: -25px;
        bottom: 7px;
        font-size: 20px;
        color: green;
        font-family:layui-icon!important;
        pointer-events:auto;
    }
    #{$this->formUnitId} tr:last-child>td:last-child:hover:after{
        cursor: pointer;
        font-weight: bold;
    }
    #{$this->formUnitId} tr{
        position: relative;
    }
    #{$this->formUnitId} tr:not(:last-child)>td:last-child:not(.s-title):after{
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
    #{$this->formUnitId} tr:not(:last-child)>td:last-child:not(.s-title):hover:after{
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
     * @return string
     * @throws \Exception
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/11/26
     */
    public function getJs(): string
    {
        $this->unit_js['event'] = <<<JS
        window['sc-table-4-index'] = $this->currentTrIndex;
        layui.jquery('#$this->formUnitId').on('click', 'tr:last-child>td:last-child', function (e) {
            e.stopPropagation();
            let html = `{$this->inputTr()}`.replace(/\[$this->currentTrIndex\]/g, `[\${++window['sc-table-4-index']}]`);
            layui.jquery('#$this->formUnitId').find('tr:last-child').after(html);
            $this->unit_call_js;
            layui.form.render();
        }).on('click', 'tr:not(:last-child)>td:last-child:not(.s-title)', function (e){
            e.stopPropagation();
            layui.jquery(this).parent('tr').remove();
        }).on('click', '*', function (e){
            e.stopPropagation(); 
        });
JS;
        return implode(';', $this->unit_js) . ';' . $this->unit_call_js;
    }

    private function inputTr(array $default = []): Dom
    {
        $form_tr = Dom::create('tr');

        foreach ($this->childrenItem as $children) {
            $type = explode('\\', get_class($children));
            $type = strtr(Str::camel(end($type)), ['Proxy' => '']);
            if (!method_exists($this, $type)) {
                throw new \Exception("table包含表单支持项：text, select, checkbox, time, 你使用了【{$type}】类型" );
            }
            $form_tr->addContent(Dom::create('td')->addContent(call_user_func([$this, $type], $children, $default)));
        }
        return $form_tr;
    }

    /**
     * @param TextProxy|TextEntity $unitBase
     * @param array $default
     * @return Dom
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/11/20
     */
    private function text(TextProxy $unitBase, array $default): Dom
    {
        $inputDiv = Dom::create();
        $name = $unitBase->getName();
        $input = Dom::create('input')->setSingleLabel()
            ->addAttr([
                'name'         => "$this->name[$this->currentTrIndex][$name]",
                'class'        => 'layui-input',
                'autocomplete' => 'off',
                'placeholder'  => $unitBase->getPlaceholder(),
                'value'        => $default[$name] ?? $unitBase->getDefaultValue()
            ]);

        if ($options = $unitBase->getOptions()) {
            $input->addAttr('list', 'datalist-' . $name);
            $datalist = Dom::create('datalist')->setId('datalist-' . $name);
            foreach ($options as $value){
                $datalist->addContent(Dom::create('option')->addAttr('value', $value));
            }
            $inputDiv->addContent($datalist);
        }
        $inputDiv->addContent($input);

        return $inputDiv;
    }

    /**
     * @param TimeProxy|TimeEntity $unitBase
     * @param array $default
     * @return Dom
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/11/21
     */
    private function time(TimeProxy $unitBase, array $default): Dom
    {
        $name = $unitBase->getName();
        $jsConfig = $unitBase->getJsConfig() ? json_encode($this->getJsConfig(), JSON_UNESCAPED_UNICODE) : '{}';
        $this->unit_js['timeRender'] = <<<JS
            
          function table_time_render(name, type, config){
             layui.lay(`.layui-form .sc-4-time-render-\${name}`).each(function(){
                  layui.laydate.render(Object.assign({
                    elem: this
                    ,type: `\${type}`
                  }, config));
            });
          }
          table_time_render();

JS;
        $this->unit_call_js .= "table_time_render('$name', '{$unitBase->getDateType()}', $jsConfig);";

        return Dom::create('input')->setSingleLabel()
            ->addAttr([
                'name'         => "$this->name[$this->currentTrIndex][$name]",
                'class'        => "layui-input sc-4-time-render-$name",
                'autocomplete' => 'off',
                'placeholder'  => $unitBase->getPlaceholder(),
                'value'        => $default[$name] ?? $unitBase->getDefaultValue()
            ]);
    }

    /**
     * @param SelectProxy|SelectEntity $unitBase
     * @param array $default
     * @return Dom
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/8/6
     */
    private function select(SelectProxy $unitBase, array $default)
    {
        $options  = [
            Dom::create('option')->addContent($unitBase->getPlaceholder())->addAttr('value', '')
        ];
        foreach ($unitBase->getOptions() as $value => $label) {
            if (is_array($label)) { // 有分组选项时
                $optgroup = Dom::create('optgroup')->addAttr('label', $value);
                foreach ($label as $value_children => $label_children){
                    $option = Dom::create('option')->addContent($label_children)->addAttr('value', $value_children);
                    if (!empty($default[$unitBase->getName()])){
                        $value_children == $default[$unitBase->getName()] and $option->addAttr('selected', '');
                    }else{
                        $value_children == $unitBase->getDefaultValue() and $option->addAttr('selected', '');
                    }
                    $optgroup->addContent($option);
                }
                $options[] = $optgroup;
            }else{
                $option = Dom::create('option')->addContent($label)->addAttr('value', $value);
                if (!empty($default[$unitBase->getName()])){
                    $value == $default[$unitBase->getName()] and $option->addAttr('selected', '');
                }else{
                    $value == $unitBase->getDefaultValue() and $option->addAttr('selected', '');
                }
                $options[] = $option;
            }
        }

        return Dom::create('select')
            ->addAttr('name', "$this->name[$this->currentTrIndex][{$unitBase->getName()}]")
            ->addAttr('lay-search', '')
            ->addContent(implode($options));
    }

    /**
     * @param CheckboxProxy|CheckboxEntity $unitBase
     * @param array $default
     * @return Dom
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/11/21
     */
    private function checkbox(CheckboxProxy $unitBase, array $default): Dom
    {
        $inputDiv = Dom::create();
        $default = isset($default[$unitBase->getName()]) ? $default[$unitBase->getName()] ?: [] : ($unitBase->getDefaultValue() ?: []);
        $default = is_array($default) ? $default : explode(',', $default);

        foreach ($unitBase->getOptions() as $value => $label) {
            $customAttr = [
                'type'      => 'checkbox',
                'lay-skin'  => 'primary',
                'value'     => $value,
                'title'     => $label,
                'name'      => "$this->name[$this->currentTrIndex][{$unitBase->getName()}][]"
            ];

            in_array($value, $default) and $customAttr['checked'] = '';
            $inputDiv->addContent(Dom::create('input')->setSingleLabel()
                ->addAttr([
                    'class'        => 'layui-input',
                    'autocomplete' => 'off'
                ])->addAttr($customAttr));
        }
        return $inputDiv;
    }

}

