<?php
/**
 * Date: 2020/9/26 11:33
 * User: chenlong <vip_chenlong@163.com>
 */

namespace sdModule\layui\form\formUnit;


use sdModule\layui\Dom;

class Select extends UnitBase
{
    /**
     * @var array 联动选项
     */
    public $linkageOptions = [];

    /**
     * @param array $attr
     * @return mixed|string
     */
    public function getHtml(array $attr):Dom
    {
        $itemDom  = $this->getItem();
        $inputDiv = Dom::create();

        $options  = [
            Dom::create('option')->addContent($this->placeholder)->addAttr('value', '')
        ];
        foreach ($this->options as $value => $label) {
            if (is_array($label)) { // 有分组选项时
                $optgroup = Dom::create('optgroup')->addAttr('label', $value);
                foreach ($label as $value_children => $label_children){
                    $option = Dom::create('option')->addContent($label_children)->addAttr('value', $value_children);
                    $this->getCheck($value_children) and $option->addAttr('selected', '');
                    $optgroup->addContent($option);
                }
                $options[] = $optgroup;
            }else{
                $option = Dom::create('option')->addContent($label)->addAttr('value', $value);
                $this->getCheck($value) and $option->addAttr('selected', '');
                $options[] = $option;
            }
        }

        $select = Dom::create('select')
            ->addAttr('name', $this->name)
            ->addAttr('lay-search', '')
            ->addAttr('lay-filter', "filter-$this->name")
            ->addContent(implode($options))->addAttr($attr);

        if ($this->label) {
            $itemDom->addContent($this->getLabel($this->label));
            $inputDiv->addClass($this->shortTip ? 'layui-inline' : 'layui-input-block');
        }else{
            $inputDiv->addClass('layui-inline');
            return $inputDiv->addContent($select);
        }

        return $itemDom->addContent($inputDiv->addContent($select))->addContent($this->getShortTip());
    }

    /**
     * 获取选中状态
     * @param $value
     * @return bool
     */
    private function getCheck($value): bool
    {
        if ($this->default === '' || $this->default === null) {
            return false;
        }
        return $value == $this->default;
    }

    /**
     * 联动选项处理
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/8/20
     */
    private function linkageOptionsHandle()
    {
        $js_options_var = $linkage = '';
        foreach ($this->linkageOptions as $linkageOption){
            $options = [];
            foreach ($linkageOption['options'] as $option){
                $options[$option['linkage_id']][] = $option;
            }

            $js_options_var .= "let linkage_{$linkageOption['field']} = " . json_encode($options, true) . ';';
            $linkage .= <<<JS
            let options_{$linkageOption['field']} = linkage_{$linkageOption['field']}.hasOwnProperty(value) ? linkage_{$linkageOption['field']}[value] : [];
            let {$linkageOption['field']}_html = '<option value=""></optino>';
            for (let index in options_{$linkageOption['field']}) {
                    let selected = linkage_init && options_{$linkageOption['field']}[index].id == defaultData.{$linkageOption['field']} ? 'selected'  : '';
                   {$linkageOption['field']}_html += `<option \${selected} value="\${options_{$linkageOption['field']}[index].id}">\${options_{$linkageOption['field']}[index].label}</option>`;
            }
            $(`select[name={$linkageOption['field']}]`).html({$linkageOption['field']}_html);
            try{ linkage_fn_{$linkageOption['field']}(linkage_init ? defaultData.{$linkageOption['field']} : 0, linkage_init); }catch (e) {}
JS;
        }

        return [$js_options_var, $linkage];
    }


    public function getJs(): string
    {
        list($where_str, $default) = $this->getShowWhereJs();
        list($js_options_var, $linkage) = $this->linkageOptionsHandle();
        return (!$this->showWhere && !$this->linkageOptions) ? '' : <<<JS
        $default
        $js_options_var
        layui.form.on('select(filter-$this->name)', function(data){
          let value = data.value;
          $where_str
          linkage_fn_{$this->name}(value);
          form.render();
        });

        function linkage_fn_{$this->name}(value, linkage_init) {
            $linkage
        }
        linkage_fn_{$this->name}(defaultData.{$this->name}, 1);
JS;
    }

}
