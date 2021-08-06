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
    /**
     * @var array
     */
    private $typesetting;


    public function setTypesetting(array $typesetting)
    {
        $example = [
            'field1' => 'FIELD1',
            'field2' => 'FIELD2',
            'field3' => 'FIELD3',
            'field4' => 'FIELD4',
            'field5' => 'FIELD5',
            'field6' => 'FIELD6',
        ];
        $this->typesetting = $typesetting;

    }


    /**
     * @param array $attr
     * @return Dom
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/8/5
     */
    public function getHtml(array $attr): Dom
    {
        $table = Dom::create('table')->addClass('layui-table')->setId('sc-form-table-' . $this->name);
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
        return $table->addContent($title_tr)->addContent($this->inputTr());
    }

    /**
     * 表单行
     * @return Dom
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/8/6
     */
    private function inputTr(): Dom
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
                throw new \Exception("table包含表单支持项：text, select, checkbox, 你使用了【{$type}】类型" );
            }

            $form_tr->addContent(Dom::create('td')->addContent(call_user_func([$this, $type], $children, $attr)));
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
        $tr = $this->inputTr();
        return <<<JS
        console.log('$tr');
        \$('#sc-form-table-$this->name').on('click', 'tr:last-child>td:last-child', function (e) {
            e.stopPropagation();
            let html = `{$this->inputTr()}`;
            \$('#sc-form-table-$this->name').find('tr:last-child').after(html);
            layui.form.render();
        }).on('click', 'tr:not(:last-child)>td:last-child:not(.s-title)', function (e){
            e.stopPropagation();
            \$(this).parent('tr').remove();
        }).on('click', '*', function (e){
            e.stopPropagation();
        });
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
    table#sc-form-table-$this->name {
        table-layout: fixed;
    }
    table#sc-form-table-$this->name td{
        padding: 0 !important;
        text-align: center;
    }
    table#sc-form-table-$this->name input{
        border: none !important;
        text-align: center;
    }
    #sc-form-table-$this->name tr:last-child td:last-child,tr:not(:last-child)>td:last-child:not(.s-title){
        pointer-events: none;
    }
    #sc-form-table-$this->name tr:last-child td:last-child *,tr:not(:last-child)>td:last-child:not(.s-title) *{
        pointer-events: auto;
    }
    #sc-form-table-$this->name tr:last-child td:last-child:after{
        content: '\\e624';
        position: absolute;
        right: -25px;
        bottom: 7px;
        font-size: 20px;
        color: green;
        font-family:layui-icon!important;
        pointer-events:auto;
    }
    #sc-form-table-$this->name tr:last-child>td:last-child:hover:after{
        cursor: pointer;
        font-weight: bold;
    }
    #sc-form-table-$this->name tr{
        position: relative;
    }
    #sc-form-table-$this->name tr:not(:last-child)>td:last-child:not(.s-title):after{
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
    #sc-form-table-$this->name tr:not(:last-child)>td:last-child:not(.s-title):hover:after{
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
     * @return Dom
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/8/6
     */
    private function text(UnitBase $unitBase, array $attr)
    {
        return Dom::create('input')->setIsSingleLabel()
            ->addAttr([
                'name'         => "$unitBase->name[]",
                'class'        => 'layui-input',
                'autocomplete' => 'off',
                'placeholder'  => $unitBase->placeholder
            ])->addAttr($attr);
    }

    /**
     * @param UnitBase $unitBase
     * @param array $attr
     * @return Dom
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/8/6
     */
    private function select(UnitBase $unitBase, array $attr)
    {
        $options  = [
            Dom::create('option')->addContent($unitBase->placeholder)->addAttr('value', '')
        ];
        foreach ($unitBase->options as $value => $label) {
            if (is_array($label)) { // 有分组选项时
                $optgroup = Dom::create('optgroup')->addAttr('label', $value);
                foreach ($label as $value_children => $label_children){
                    $option = Dom::create('option')->addContent($label_children)->addAttr('value', $value_children);
                    $value_children == $unitBase->default and $option->addAttr('selected', '');
                    $optgroup->addContent($option);
                }
                $options[] = $optgroup;
            }else{
                $option = Dom::create('option')->addContent($label)->addAttr('value', $value);
                $value == $unitBase->default and $option->addAttr('selected', '');
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
     * @return Dom
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/8/6
     */
    public function checkbox(UnitBase $unitBase, array $attr)
    {
        $inputDiv = Dom::create();
        foreach ($unitBase->options as $value => $label) {
            $customAttr = [
                'type'      => 'checkbox',
                'lay-skin'  => 'primary',
                'value'     => $value,
                'title'     => $label,
                'name'      => "{$unitBase->name}[]"
            ];
            $value == $unitBase->default and $customAttr['checked'] = '';
            $inputDiv->addContent(Dom::create('input')->setIsSingleLabel()
                ->addAttr([
                    'class'        => 'layui-input',
                    'autocomplete' => 'off',
                    'placeholder'  => $unitBase->placeholder
                ])->addAttr($customAttr)->addAttr($attr));
        }
        return $inputDiv;
    }
}

