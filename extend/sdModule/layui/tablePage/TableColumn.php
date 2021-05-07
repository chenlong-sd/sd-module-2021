<?php
/**
 * Date: 2021/4/19 18:45
 * User: chenlong <vip_chenlong@163.com>
 */

namespace sdModule\layui\tablePage;

/**
 * Class TableColumn
 * @package sdModule\layui\tablePage
 */
class TableColumn implements \ArrayAccess
{
    /**
     * @var array
     */
    private array $column = [];

    /**
     * @var string
     */
    public string $js = '';

    /**
     * TableColumn constructor.
     * @param string $field
     * @param string $title
     */
    public function __construct(string $field = '', string $title = '')
    {
        $this->column['field'] = $field;
        $this->column['title'] = $title;
    }

    /**
     * @return $this
     */
    public function checkbox(): TableColumn
    {
        $this->column['type'] = 'checkbox';
        return $this;
    }

    /**
     * @return $this
     */
    public function radio(): TableColumn
    {
        $this->column['type'] = 'radio';
        return $this;
    }

    /**
     * 设置图片模板
     * @return $this
     */
    public function image(): TableColumn
    {
        $this->column['templet'] = '@image';
        return $this;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return $this->column;
    }

    /**
     * 设置其他配置参数
     * @param string|array $param
     * @param string|null $value
     * @return $this
     * 有以下参数， 详情参考 <https://www.layui.com/doc/modules/table.html#cols>
     * width，minWidth，type，LAY_CHECKED，fixed，hide，totalRow，totalRowText，sort，unresize，edit，style，event
     * align，colspan，rowspan，templet，toolbar
     */
    public function param($param, ?string $value = null): TableColumn
    {
        if (is_array($param)) {
            foreach ($param as $key => $value){
                $this->column[$key] = $value;
            }
        }else{
            $this->column[$param] = $value;
        }

        return $this;
    }

    /**
     * 设置列为开关
     * @param string $open_value
     * @param Ajax $js_code
     * @param string $title
     * @return $this
     */
    public function switch(string $open_value, Ajax $js_code, string $title = 'ON|OFF'): TableColumn
    {
        $this->column['templet'] = function () use ($open_value, $title){
            return <<<JS
        let checked = "{$open_value}" == obj.{$this->column['field']} ? "checked" : "";
return `<input type="checkbox" name="{$this->column['field']}" value="\${obj.id}" lay-skin="switch" lay-text="{$title}" lay-filter="sc{$this->column['field']}" \${checked}>`;
JS;
        };
        $js_code->successCallback(<<<CAL
         switch_obj.elem.checked = !switch_obj.elem.checked;
         form.render('checkbox');
CAL);
        $this->js = <<<JS
    form.on('switch(sc{$this->column['field']})', function(switch_obj){
        {$js_code}
        switch_obj.elem.checked = !switch_obj.elem.checked;
        form.render('checkbox');
    });
JS;
        return $this;
    }

    /**
     * 设置展示模板
     * @param string $js_code
     * @return TableColumn
     */
    public function setTemplate(string $js_code): TableColumn
    {
        $this->column['templet'] = fn() => $js_code;
        return $this;
    }

    /**
     * 字段合并
     * @param string|array $field
     * @param string $link_symbol
     * @return $this
     */
    public function mergeField($field, string $link_symbol = ''): TableColumn
    {
        $field = (array)$field;
        array_unshift($field, $this->column['field']);
        $field = array_map(fn($v) => 'obj.' . $v, $field);
        $code  = 'return ' . implode(" + '{$link_symbol}' +", $field) . ';';
        $this->column['templet'] = fn() => $code;
        return $this;
    }

    public function offsetExists($offset): bool
    {
        return isset($this->column[$offset]);
    }

    public function offsetGet($offset)
    {
        return $this->column[$offset] ?? null;
    }

    public function offsetSet($offset, $value)
    {
        $this->column[$offset] = $value;
    }

    public function offsetUnset($offset)
    {
        unset($this->column[$offset]);
    }
}
