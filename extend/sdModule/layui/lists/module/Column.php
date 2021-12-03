<?php
/**
 * datetime: 2021/9/18 21:17
 * user    : chenlong<vip_chenlong@163.com>
 **/

namespace sdModule\layui\lists\module;


use sdModule\layui\Dom;
use sdModule\layui\lists\error\ColumnTypeIsNotSupportedException;
use think\helper\Str;

/**
 * Class Column
 * @method static Column checkbox(string $title = '')
 * @method static Column radio(string $title, string $field = '')
 * @method static Column normal(string $title, string $field = '')
 * @method static Column numbers(string $title, string $field = '')
 * @method static Column space(string $title, string $field = '')
 * @package sdModule\layui\lists\module
 * @author chenlong<vip_chenlong@163.com>
 * @date 2021/9/18
 */
class Column implements \ArrayAccess
{

    /**
     * @var array 数据列配置详情
     */
    public $configInfo = [];

    /**
     * 数据列需要的js
     * @var string
     */
    public $js = '';

    /**
     * Column constructor.
     * @param string $column_type
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/9/18
     */
    public function __construct(string $column_type)
    {
        $this->configInfo['type'] = $column_type;
    }

    /**
     * 添加排序
     * @return $this
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/9/18
     */
    public function addSort(): Column
    {
        $this->configInfo['sort'] = true;
        return $this;
    }

    /**
     * 设置为开关显示
     * @param string $field
     * @param array $valueMapping
     * @param Ajax|null $js_code
     * @return $this
     * @throws \app\common\SdException
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/9/18
     */
    public function showSwitch(string $field = '', array $valueMapping = [], ?Ajax $js_code = null): Column
    {
        $field = $field ?: $this->configInfo['field'];
        if (!$valueMapping) {
            // 没有传值映射 $valueMapping, 读取对应的common/model下的字段配置数据
            $controller   = request()->controller();
            $fieldMapping = Str::studly($field);

            if (is_callable($mapping_function = "\\app\\common\\model\\{$controller}::get{$fieldMapping}Sc")){
                $valueMapping = call_user_func($mapping_function, false);
            }
        }
        // 解析开关对应的 打开值 和 关闭值
        list($open_value, $close_value) = array_keys($valueMapping);
        $valueMapping = array_map(function ($v) {
            return $v instanceof Dom ? current($v->getContent()) : $v;
        }, $valueMapping);
        // 构建开关的展示值和js
        $title       = implode('|', $valueMapping);
        $js_code     = $js_code instanceof Ajax ? $js_code : new Ajax(url('switchHandle'));

        if ((string)$js_code === 'false') {
            $this->configInfo['field'] = $field;
            return $this;
        }

        $this->setTemplate(<<<JS
        let checked = "{$open_value}" == obj.{$this->configInfo['field']} ? "checked" : "";
        return `<input type="checkbox" data-checked="\${checked}" value="\${obj.id}" lay-skin="switch" lay-text="{$title}" lay-filter="sc{$this->configInfo['field']}" \${checked} />`;
JS);
        // 失败执行的js代码
        $fail = <<<JS
            switch_obj.elem.setAttribute("data-checked", origin_checked);
            let checked_class = origin_checked === 'checked' ? 'layui-form-onswitch' : '';
            switch_obj.othis.attr("class",`layui-unselect layui-form-switch \${checked_class}`).find('em')
            .html(change_the_value_to === '$close_value' ? '$valueMapping[$open_value]' : '$valueMapping[$close_value]')
JS;
        $js_code->method('post')->setFailCallback($fail)
            ->successCallback('')->dataCode('request_data');

        $this->js = <<<JS
    layui.form.on('switch(sc{$this->configInfo['field']})', function(switch_obj){
        let origin_checked = switch_obj.elem.getAttribute('data-checked');
        let change_the_value_to   = origin_checked === 'checked' ? '{$close_value}' : '{$open_value}';
        switch_obj.elem.setAttribute("data-checked", origin_checked === 'checked' ? '' : 'checked');
        let request_data = {id:switch_obj.value, handle_value:change_the_value_to,field:"{$field}"};
        {$js_code}
    });
JS;
        return $this;
    }

    /**
     * 设置展示为图片
     * @return $this
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/9/18
     */
    public function showImage(): Column
    {
        return $this->setTemplate("@image");
    }

    /**
     * 更多参数配置
     * @param array|string $configuration
     * @author chenlong<vip_chenlong@163.com>
     * @see https://www.layui.com/doc/modules/table.html#cols
     * @example   width，minWidth，type，LAY_CHECKED，fixed，hide，totalRow，totalRowText，sort，unresize，edit，style，event
     * align，colspan，rowspan，templet，toolbar
     * @date 2021/9/18
     */
    public function moreConfiguration($configuration, $value = null): Column
    {
        if (!is_array($configuration)){
            $configuration = [$configuration => $value];
        }
        $this->configInfo = array_merge($this->configInfo, $configuration);

        return $this;
    }


    /**
     * 设置展示模板
     * @param string|callable $js_code
     * @return $this
     */
    public function setTemplate($js_code): Column
    {
        $this->configInfo['templet'] = is_callable($js_code)
            ? $js_code
            : function () use ($js_code) {
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
    public function setFormat(string $format): Column
    {
        $format = strtr($format, ['{' => '${obj.']);
        $this->setTemplate("return `$format`;");
        return $this;
    }


    /**
     * 静态创建基本列配置
     * @param string $name 列类型
     * @param array  $arguments 参数
     * @return Column
     * @throws ColumnTypeIsNotSupportedException
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/9/18
     */
    public static function __callStatic($name, $arguments)
    {
        if (!in_array($name, ['checkbox', 'radio', 'normal', 'numbers', 'space'])){
            throw new ColumnTypeIsNotSupportedException($name);
        }

        $column = new self($name);

        if ($arguments) {
            $column->configInfo['title'] = $arguments[0];

            empty($arguments[1]) or
            $column->configInfo['field'] = $arguments[1];
        }

        return $column;
    }

    public function offsetExists($offset)
    {
        return isset($this->configInfo[$offset]);
    }

    public function offsetGet($offset)
    {
        return $this->configInfo[$offset] ?? null;
    }

    public function offsetSet($offset, $value)
    {
        $this->configInfo[$offset] = $value;
    }

    public function offsetUnset($offset)
    {
        unset($this->configInfo[$offset]);
    }
}
