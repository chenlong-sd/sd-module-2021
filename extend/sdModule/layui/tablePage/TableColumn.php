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
     * @param mixed $value
     * @return $this
     * 有以下参数， 详情参考 <https://www.layui.com/doc/modules/table.html#cols>
     * width，minWidth，type，LAY_CHECKED，fixed，hide，totalRow，totalRowText，sort，unresize，edit，style，event
     * align，colspan，rowspan，templet，toolbar
     */
    public function param($param, $value = null): TableColumn
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
     * @param array $data 开关的两个数据， [open_value => open_title, close_value => close_title]
     * @param Ajax $js_code ajax 请求代码
     * @return $this
     */
    public function switch(array $data, Ajax $js_code): TableColumn
    {
        $open_value  = array_key_first($data);
        $close_value = array_key_last($data);
        $title       = implode('|', $data);

        $this->column['templet'] = function () use ($open_value, $title){
            return <<<JS
        let checked = "{$open_value}" == obj.{$this->column['field']} ? "checked" : "";
        return `<input type="checkbox" data-checked="\${checked}" id="switch-{$this->column['field']}\${obj.id}" name="{$this->column['field']}" value="\${obj.id}" lay-skin="switch" lay-text="{$title}" lay-filter="sc{$this->column['field']}" \${checked}>`;
JS;
        };
        $js_code->setFailCallback('location.reload();')->dataCode('quest_data');
        $this->js = <<<JS
    form.on('switch(sc{$this->column['field']})', function(switch_obj){
        let handle_value = switch_obj.elem['data-checked'] === 'checked' ? '{$open_value}' : '{$close_value}';
        let quest_data = {id:switch_obj.value, handle_value:handle_value};
        let origin = switch_obj.othis;
        console.log(switch_obj.othis, handle_value,switch_obj.elem['data-checked']);
        setTimeout(()=>{
            switch_obj.elem['data-checked'] = handle_value === '{$open_value}' ? 'checked' : '';
            switch_obj.othis.prop("class","layui-unselect layui-form-switch layui-form-onswitch").html(origin.html())
        }, 2000)
        /*{$js_code}*/
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
     * 设置格式化输出
     * @param string $format 格式 {var} var 为字段名字 eg：  姓名：{name},年龄：{age}
     * @return $this
     * @author chenlong <vip_chenlong@163.com>
     * @date 2021/6/4
     */
    public function setFormat(string $format): TableColumn
    {
        $format = strtr($format, ['{' => '${obj.']);
        $this->column['templet'] = fn() => "return `$format`;";
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
