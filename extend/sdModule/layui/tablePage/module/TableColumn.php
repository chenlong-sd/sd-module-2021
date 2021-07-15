<?php
/**
 * Date: 2021/4/19 18:45
 * User: chenlong <vip_chenlong@163.com>
 */

namespace sdModule\layui\tablePage\module;

use think\helper\Str;

/**
 * Class TableColumn
 * @package sdModule\layui\tablePage
 */
class TableColumn implements \ArrayAccess
{
    /**
     * 列数据配置
     * @var array
     */
    private $column = [];

    /**
     * 额外的js代码
     * @var string
     */
    public $js = '';

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
     * @return $this
     * @author chenlong <vip_chenlong@163.com>
     * @date 2021/6/7
     */
    public function addSort(): TableColumn
    {
        $this->column['sort'] = true;
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
     * @param string $field 该值对应的字段
     * @param array $data 开关的两个数据， [open_value => open_title, close_value => close_title], 不传默认读取对应的common/model下的字段设置
     * @param Ajax|null $js_code ajax 请求代码 可不传，默认请求当前的 switchHandle 函数， 自定义的可传这个参数或者重写 switchHandle
     * @return $this
     */
    public function switch(string $field = '', array $data = [], ?Ajax $js_code = null): TableColumn
    {
        $field = $field ?: $this->column['field'];
        if (!$data) {
            // 没有传值data, 读取对应的common/model下的字段配置数据
            $controller = request()->controller();
            $fieldA     = Str::studly($field);
            $data       = call_user_func("\\app\\common\\model\\{$controller}::get{$fieldA}Sc", false);
        }

        $open_value  = array_key_first($data);
        $close_value = array_key_last($data);
        $title       = implode('|', $data);
        $js_code     = $js_code instanceof Ajax ? $js_code : new Ajax(url('switchHandle'));

        if ((string)$js_code === 'false') {
            $this->column['field'] = $field;
            return $this;
        }

        $this->column['templet'] = function () use ($open_value, $title){
            return <<<JS
        let checked = "{$open_value}" == obj.{$this->column['field']} ? "checked" : "";
        return `<input type="checkbox" data-checked="\${checked}" id="switch-{$this->column['field']}\${obj.id}" name="{$this->column['field']}" value="\${obj.id}" lay-skin="switch" lay-text="{$title}" lay-filter="sc{$this->column['field']}" \${checked}>`;
JS;
        };
        // 失败执行的js代码
        $fail = <<<JS
            switch_obj.elem.setAttribute("data-checked", origin_checked);
            let checked_class = origin_checked === 'checked' ? 'layui-form-onswitch' : '';
            switch_obj.othis.attr("class",`layui-unselect layui-form-switch \${checked_class}`).find('em')
            .html(title[handle_value])
JS;
        $js_code->method('post')->setFailCallback($fail)
            ->successCallback('')->dataCode('quest_data');

        $this->js = <<<JS
    form.on('switch(sc{$this->column['field']})', function(switch_obj){
        let origin_checked = switch_obj.elem.getAttribute('data-checked');
        let handle_value   = origin_checked === 'checked' ? '{$close_value}' : '{$open_value}';
        switch_obj.elem.setAttribute("data-checked", origin_checked === 'checked' ? '' : 'checked');
        let quest_data = {id:switch_obj.value, handle_value:handle_value,field:"{$field}"};
        // 重新赋值显示用，所以反过来
        let title = {
            {$close_value}:"{$data[$open_value]}",
            {$open_value}:"{$data[$close_value]}",
        }
        {$js_code}
    });
JS;
        return $this;
    }

    /**
     * 设置展示模板
     * @param string|callable $js_code
     * @return TableColumn
     */
    public function setTemplate($js_code): TableColumn
    {
        $this->column['templet'] = is_callable($js_code) ? $js_code : function () use ($js_code) {
            return $js_code;
        };
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
        $this->setTemplate("return `$format`;");
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
        $field = array_map(function ($v) {
            return 'obj.' . $v;
        }, $field);
        $code  = 'return ' . implode(" + '{$link_symbol}' +", $field) . ';';
        $this->setTemplate($code);
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
