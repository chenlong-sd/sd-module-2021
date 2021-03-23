<?php
/**
 *
 * TableConstruct.php
 * User: ChenLong <vip_chenlong@163.com>
 * DateTime: 2020/7/17 16:20
 */


namespace sdModule\layui\tableDetail;

/**
 * Class TableConstruct
 * @package sdModule\layui\tableDetail
 * @deprecated
 */
class TableConstruct
{
    const TR = "<tr>\r\n:tds</tr>\r\n";
    const TD = "<td>:title</td><td colspan=\":td_grid\">:value</td>\r\n";
    const TITLE = "<blockquote class=\"layui-elem-quote\">:title</blockquote>";
    const TABLE = '';

    private $data;
    private $title = '';
    private $multiple = false;
    private $current_table_index = 0;
    private $event_js = '';
    private $after_button = '';
    private $before_button = '';

    private $root;

    /**
     * TableConstruct constructor.
     * @param $table_data
     * @param $multiple
     */
    private function __construct(array $table_data, bool $multiple)
    {
        $this->root = rtrim(strtr(dirname($_SERVER['SCRIPT_NAME']), ['\\' => '/']), '/') . '/';
        $this->data = $table_data;
        $this->multiple = $multiple;
    }

    /**
     * 设置字段值[key => value]
     * @param array $data 值数据，多个表格则是二维数组
     * @param bool  $multiple 是否是多个表格
     * @return TableConstruct
     */
    public static function data(array $data, bool $multiple = false)
    {
        return new self($data, $multiple);
    }

    /**
     * 设置标题
     * @param string|array $title
     * @return $this
     */
    public function title($title)
    {
        $this->title = $title;
        return $this;
    }

    /**
     * 获取value值
     * @param $name
     * @param $type
     * @return string
     */
    private function getValue($name, $type)
    {
        $td_value = $this->multiple
            ? ($this->data[$this->current_table_index][$name] ?? null)
            : ($this->data[$name] ?? null);

        if ($td_value || $td_value === 0) {
            if ($type === 'text') return $td_value;
            if ($type === 'image') {
                $td_value = preg_match('/^http/', $td_value) ? $td_value : ($this->root . $td_value);
                return "<div class='img-table layui-inline'><img  src='{$td_value}' alt='{$td_value}' /></div>";
            }

            $string = '';
            foreach (explode(',', $td_value) as $value) {
                $value = preg_match('/^http/', $value) ? $value : ($this->root . $value);
                $string .= "<div class='img-table layui-inline'><img  src='{$value}' alt='{$value}' /></div>";
            }
            return $string;
        }
        return '——';
    }

    /**
     * @param array $trs 行数据
     * @return string
     */
    public function render(array $trs)
    {
        $table_html = '';

        if ($this->multiple) {
            foreach ($trs as $index => $sub_trs) {
                $this->current_table_index = $index;

                /**
                 * 判断是否是多维数组，多维数组即表示为 【 标题 | 值  |  标题 | 值 】类型
                 * 一维数组则表示数据为竖向的多个数据
                 * 姓名 | 年龄
                 * 张三 | 18
                 * 李四 | 18
                 */
                if (count($sub_trs) == count($sub_trs, 1)) {
                    $table_html .= $this->formatData($sub_trs);
                }else{
                    foreach ($sub_trs as $tr) {
                        $table_html .= $this->trRender($tr);
                    }
                }

                $title = is_array($this->title) && isset($this->title[$index + 1])
                    ? strtr(self::TITLE, [':title' => $this->title[$index + 1]]) : '';
                $table_html .= "</table>{$title}<table  class=\"layui-table\">";
            }
        } else {
            if (count($trs) == count($trs, 1)) {
                $table_html .= $this->formatData($trs);
            }else{
                foreach ($trs as $tr) {
                    $table_html .= $this->trRender($tr);
                }
            }
        }

        return strtr(self::TABLE, [
            ':title'            => is_array($this->title) ? $this->title[0] : $this->title,
            ':trs'              => $table_html,
            ':ROOT'             => $this->root,
            ':layui_js'         => $this->layuiJs(),
            ':custom_js'        => $this->customJs(),
            ':after_button'     => $this->after_button,
            ':before_button'    => $this->before_button,
            ':token'            => token_meta()
        ]);
    }

    /**
     * @param mixed ...$td
     * @return array
     */
    public static function tr(...$td)
    {
        return $td;
    }

    /**
     * 列表式的数据
     * @param array $thead  头字段
     * @return string
     */
    private function formatData(array $thead)
    {
        $title = '<th>' . implode('</th><th>', $thead) . '</th>';
        $th = "<tr>{$title}</tr>";

        $data = $this->multiple ? $this->data[$this->current_table_index] : $this->data;

        foreach ($data as $item) {
            $th .= '<tr>';
            foreach ($thead as $field => $omission){
                $th .= '<td>' . ($item[$field] ?? '——') . '</td>';
            }
            $th .= '</tr>';
        }
        return $th;
    }

    /**
     * @param string $title 标题
     * @param string $name 对应的值的field
     * @param int    $td_grid 值占用列数
     * @param string $type 可选:image|images|text
     * @return array
     */
    public static function td(string $title, string $name, $td_grid = 1, $type = 'text')
    {
        return compact('title', 'td_grid', 'name', 'type');
    }

    /**
     * 之后的按钮
     * @param mixed ...$buttons button按钮集合
     * @return mixed|string
     */
    public function afterButton(...$buttons)
    {
        foreach ($buttons as $button) {
            $this->buttonConstruct($button, false);
        }
        return $this;
    }

    /**
     * @param mixed ...$buttons button按钮集合
     * @return $this
     */
    public function beforeButton(...$buttons)
    {
        array_map([$this, 'buttonConstruct'], $buttons);
        return $this;
    }

    /**
     * button 组建
     * @param array $button 组建button的数据array
     * @param bool  $isBefore   是否是表格之前
     */
    private function buttonConstruct(array $button, $isBefore = true)
    {
        $event = 'e' . mt_rand(100, 999);
        $this->eventJs($button['url'], $button['request_data'], $event);

        $buttonHtml = "<button class=\"layui-btn {$button['class']}\" lay-active=\"{$event}\">{$button['title']}</button>";

        $isBefore ? $this->before_button .= $buttonHtml : $this->after_button .= $buttonHtml;
    }


    /**
     * 生成button
     * @param string $title 按钮文字
     * @param string|\Closure $url   事件请求地址或自定义js代码的回调函数
     * @param array  $request_data   请求数据，$url 为Closure时，此为$url的参数
     * @param string $class layui button的类名
     * @return array
     */
    public static function button(string $title, $url, array $request_data, $class = '')
    {
        return compact('title', 'url', 'request_data', 'class');
    }

    /**
     * 按钮事件的js代码
     * @param string $url 请求地址
     * @param string $data 请求数据
     * @param string $event 事件名
     */
    private function eventJs($url, $data, $event)
    {
        if ($url instanceof \Closure) {
            $code = call_user_func($url, ...$data);
        }else{
            $data = json_encode($data, JSON_UNESCAPED_UNICODE);
            $code = "sc_event('{$url}', {$data});";
        }

        $this->event_js .= <<<EVENT
            {$event}:function(){{$code}},
EVENT;
    }


    /**
     * @return string
     */
    private function eventRequest()
    {
        return <<<EVENT
        layui.util.event('lay-active', {
           {::event::}
        });
EVENT;
    }

    /**
     * @param string $title
     * @param string $name
     * @param int    $td_grid
     * @return array
     */
    public static function tdImages(string $title, string $name, $td_grid = 1)
    {
        return self::td($title, $name, $td_grid, 'images');
    }

    /**
     * @param string $title
     * @param string $name
     * @param int    $td_grid
     * @return array
     */
    public static function tdImage(string $title, string $name, $td_grid = 1)
    {
        return self::td($title, $name, $td_grid, 'image');
    }

    /**
     * @param array $tr
     * @return string
     */
    private function trRender(array $tr)
    {
        $tr_string = '';
        foreach ($tr as $td) {
            $tr_string .= $this->tdRender($td);
        }

        return strtr(self::TR, [':tds' => $tr_string]);
    }

    /**
     * 渲染td
     * @param array $td
     * @return string
     */
    private function tdRender(array $td)
    {
        return strtr(self::TD, [
            ':title' => $td['title'],
            ':td_grid' => $td['td_grid'],
            ':value' => $this->getValue($td['name'], $td['type'])
        ]);
    }

    private function lang($key)
    {
        return lang($key);
    }

    private function layuiJs()
    {
        return include __DIR__ . '/js_var.php';
    }

    private function customJs()
    {
        $event_js = $this->event_js ? strtr($this->eventRequest(), ['{::event::}' => $this->event_js]) : '';
        return <<<CUSTOM

<script >
   layer.ready(function() {
        custom.enlarge(layer,layui.jquery,'.img-table');
    });
    {$event_js}
</script>
CUSTOM;

    }

}

