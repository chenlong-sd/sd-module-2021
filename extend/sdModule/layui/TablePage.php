<?php


namespace sdModule\layui;

use sdModule\layui\item\Button;
use sdModule\layui\tablePage\TableAux;

/**
 * PHP version 7.4.* ~
 * Class TablePage
 * @package sdModule\layui
 * @method mixed lang($value) 多语言
 * @method mixed url(...$value) 路径
 */
class TablePage
{
    const HANDLE_STYLE_ALL     = 7;
    const HANDLE_STYLE_NORMAL  = 1;
    const HANDLE_STYLE_CONTEXT_LI     = 2;
    const HANDLE_STYLE_CONTEXT_BUTTON = 4;

    /**
     * @var int
     */
    private int $handle_width = 150;
    /**
     * @var string 操作胡id
     */
    private string $handle = "#table_line";

    /**
     * @var array 字段数据
     */
    private array $field_data;

    /**
     * @var array 函数替换的数据
     */
    private array $function_replace = [];

    /**
     * @var string 行大小
     */
    private string $size = '';

    /**
     * @var array|string[] 事件
     */
    private array $tool_event = ['update', 'delete'];

    /**
     * @var array|string[] html
     */
    private array $tool_event_html = [];

    /**
     * @var array|string[] js
     */
    private array $tool_event_js = [];

    /**
     * @var array|string[] 事件
     */
    private array $toolbar_event = ['create', 'delete'];

    /**
     * @var array|string[] html
     */
    private array $toolbar_event_html = [];

    /**
     * @var array|string[] js
     */
    private array $toolbar_event_js = [];

    /**
     * @var array|string[] js
     */
    private array $event_where = [];

    /**
     * @var bool 不满租条件的事件是否隐藏
     */
    private bool $where_not_meet = false;
    /**
     * @var array 表格配置
     */
    private array $config = [];

    /**
     * @var int 设置表格操作样式
     */
    private int $handle_style = self::HANDLE_STYLE_ALL;

    /**
     * 设置操作栏宽度
     * @param int $width
     * @return $this
     */
    public function setHandleWidth(int $width)
    {
        $this->handle_width = $width;
        return $this;
    }

    /**
     * 创建页面
     * @param array $field_data 字段数据
     * @return TablePage
     */
    public static function create(array $field_data)
    {
        $table = new self();
        $table->defaultEventHtml();
        $table->defaultEventJs();
        $table->config     = array_filter(config('admin.layui_config'));
        $table->field_data = array_map(fn($v) => array_filter($v), $field_data);
        $table->functionHandle();
        return $table;
    }

    /**
     * 设置操作栏的js ID标识 eg: #table_line
     * @param string $handle
     * @return $this
     */
    public function setHandle(string $handle)
    {
        $this->handle = $handle;
        return $this;
    }

    /**
     * @param int $style  1, 2, 3
     * @return TablePage
     */
    public function setHandleStyle(int $style = self::HANDLE_STYLE_NORMAL)
    {
        $this->handle_style = $style;
        return $this;
    }

    /**
     * 表格列数据
     * @param string|array $field 字段,或数组（直接返回数组信息
     * @param string $title 字段label
     * @param string|\Closure $templet 模板id或匿名函数返回js代码
     * @param array $params 有以下参数， 详情参考 <https://www.layui.com/doc/modules/table.html#cols>
     * width，minWidth，type，LAY_CHECKED，fixed，hide，totalRow，totalRowText，sort，unresize，edit，style，event
     * align，colspan，rowspan，templet，toolbar
     * @deprecated
     * @uses \sdModule\layui\tablePage\TableAux::column()
     * @return array
     */
    public static function column($field, $title = '', $templet = '', array $params = [])
    {
        if (is_array($field)) {
            return $field;
        }

        return array_merge(compact('field', 'title', 'templet'), $params);
    }

    /**
     * 获取字段信息
     * @return string
     */
    public function getField()
    {
        if ($this->handle && $this->tool_event && ($this->handle_style & self::HANDLE_STYLE_NORMAL)) {
            $this->field_data[] = [
                "width"   => $this->handle_width,
                "title"   => lang("operating"),
                "templet" => $this->handle
            ];
        }

        $data = json_encode($this->field_data, JSON_UNESCAPED_UNICODE);

        return strtr($data, $this->function_replace);
    }

    /**
     * 函数处理
     * @return mixed
     */
    private function functionHandle()
    {
        foreach ($this->field_data as &$item) {
            if (empty($item['templet'])) {
                continue;
            }

            if ($item['templet'] instanceof \Closure) {
                $js_code = call_user_func($item['templet']);
                $this->function_replace["\"@{$item['field']}\""] = "function(obj){{$js_code}}";
                $item['templet'] = empty($item['field']) ? '' : "@{$item['field']}";
            } elseif ($item['templet'] === '@image') {
                $this->config['size'] = 'lg';
                $this->function_replace["\"@{$item['field']}\""] = "function (obj) {return custom.tableImageShow(obj.{$item['field']});}";
                $item['templet'] = empty($item['field']) ? '' : "@{$item['field']}";
            }
        }
        return $this->field_data;
    }

    /**
     * @param $method
     * @param $vars
     * @return mixed|string
     */
    public function __call($method, $vars)
    {
        return function_exists($method) ? call_user_func_array($method, $vars) : '';
    }

    /**
     * @return string
     */
    public function getSize(): string
    {
        return $this->size;
    }

    /**
     * 设置事件
     * @deprecated
     * @param array $event
     */
    public function setEvent(array $event): void
    {
        $this->tool_event = $event;
    }

    /**
     * 添加事件
     * @param array|string $event
     * @param null $html
     * @param null $js
     * @return TablePage
     */
    public function addEvent($event, $html = null, $js = null)
    {
        return $this->addEventHandle($event, $html, $js);
    }

    /**
     * 添加事件头部bar的事件
     * @param array|string $event
     * @param null $html
     * @param null $js
     * @return TablePage
     */
    public function addBarEvent($event, $html = null, $js = null)
    {
        return $this->addEventHandle($event, $html, $js, true);
    }

    /**
     * Event 添加处理
     * @param $event
     * @param null $html
     * @param null $js
     * @param bool $is_bar
     * @return TablePage
     */
    private function addEventHandle($event, $html = null, $js = null, $is_bar = false)
    {
        if (is_string($event)){
            if ($is_bar) {
                $this->toolbar_event[] = $event;
                $html and $this->toolbar_event_html[$event] = $html;
                $js   and $this->toolbar_event_js[$event]   = $js;
            }else{
                $this->tool_event[] = $event;
                $html and $this->tool_event_html[$event] = $html;
                $js   and $this->tool_event_js[$event]   = $js;
            }
        }elseif(is_array($event)){
            $is_bar
                ? $this->toolbar_event = array_merge($this->toolbar_event, $event)
                : $this->tool_event    = array_merge($this->tool_event, $event);
        }
        return $this;
    }

    /**
     * 删除事件
     * @param array|string $event
     * @return TablePage
     */
    public function removeEvent($event)
    {
        return $this->removeEventHandle($event);
    }

    /**
     * 删除头部bar的事件
     * @param array|string $event
     * @return TablePage
     */
    public function removeBarEvent($event)
    {
        return $this->removeEventHandle($event, true);
    }

    /**
     * 删除事件处理
     * @param array|string $event
     * @param bool $is_bar
     * @return TablePage
     */
    private function removeEventHandle($event, bool $is_bar = false)
    {
        if ($is_bar){
            $this->toolbar_event = array_diff($this->toolbar_event, (array)$event);
        }else{
            $this->tool_event    = array_diff($this->tool_event, (array)$event);
        }
        return $this;
    }

    /**
     * 设置头部事件
     * @deprecated
     * @param array $event
     */
    public function setBarEvent(array $event)
    {
        $this->toolbar_event = $event;
    }

    /**
     * 设置事件html
     * @param string $event
     * @param string $html
     * @return $this
     */
    public function setEventHtml(string $event, $html)
    {
        return $this->setEventHtmlHandle($event, $html);
    }

    /**
     * 设置头部事件html
     * @param string $event
     * @param string $html
     * @return $this
     */
    public function setBarEventHtml(string $event, string $html)
    {
        return $this->setEventHtmlHandle($event, $html, true);
    }

    /**
     * 设置事件html
     * @param string $event
     * @param $html
     * @param bool $is_toolbar
     * @return $this
     */
    private function setEventHtmlHandle(string $event, $html, $is_toolbar = false)
    {
        if ($is_toolbar) {
            $this->toolbar_event_html[$event] = $html;
        }else{
            $this->tool_event_html[$event] = $html;
        }
        return $this;
    }

    /**
     * 设置事件js
     * @param string $event
     * @param string|bool $js_code
     * @return $this
     */
    public function setEventJs(string $event, $js_code)
    {
        return $this->setEventJsHandle($event, $js_code);
    }

    /**
     * 设置事件js
     * @param string $event
     * @param string $js_code
     * @return $this
     */
    public function setBarEventJs(string $event, $js_code)
    {
        return $this->setEventJsHandle($event, $js_code, true);
    }

    /**
     * 设置事件js处理
     * @param string $event
     * @param $js_code
     * @param bool $is_toolbar
     * @return $this
     */
    private function setEventJsHandle(string $event, $js_code, bool $is_toolbar = false)
    {
        if ($is_toolbar) {
            if ($js_code === false){
                $this->removeBarEvent($event);
            }else{
                $this->toolbar_event_js[$event] = $js_code;
            }
        }else{
            if ($js_code === false){
                $this->removeEvent($event);
            }else {
                $this->tool_event_js[$event] = $js_code;
            }
        }
        return $this;
    }

    /**
     * 展示条件
     * @param array|string $event
     * @param null|string|bool $where 条件
     * @param bool $is_hidden 是否隐藏按钮（不满足条件）
     */
    public function setEventWhere($event, $where = null, $is_hidden = false)
    {
        if (is_array($event)){
            $this->event_where    = array_merge($this->event_where, $event);
            $this->where_not_meet = $where === null ? $is_hidden : $where;
        }else{
            $this->event_where[$event] = $where;
            $this->where_not_meet      = $is_hidden;
        }
    }

    /**
     * @return string 获取头部工具栏html
     */
    public function getToolbar()
    {
        return implode(array_map(fn($v) => ($this->toolbar_event_html[$v] ?? ''), $this->toolbar_event));
    }

    /**
     * @return string 获取工具栏html
     */
    public function getTool()
    {
        if (!($this->handle_style & self::HANDLE_STYLE_NORMAL)) {
            return '';
        }

        $where_template = $this->where_not_meet
            ? "{{# if (:where) { }} :html {{# } }}"
            : "{{# if (:where) { }} :html {{# }else{ }} :disable {{# } }}";

        return implode(array_map(function ($v) use ($where_template){
            if (empty($this->tool_event_html[$v])){
                return '';
            }

            $disable = preg_replace('/lay-event="\w+"/', '', $this->tool_event_html[$v]);
            if (!preg_match('/(btn-danger)|(btn-warm)|(btn-normal)|(btn-primary)/', $disable)){
                $disable = strtr($disable, ['layui-btn ' => 'layui-btn layui-btn-disabled ']);
            }else{
                $disable = preg_replace(['/danger/', '/warm/', '/normal/', '/primary/'], 'disabled', $disable);
            }

            return isset($this->event_where[$v])
                ? strtr($where_template, [
                    ':where'    => $this->event_where[$v],
                    ':html'     => $this->tool_event_html[$v],
                    ':disable'  => $disable
                ])
                : $this->tool_event_html[$v];
        }, $this->tool_event));
    }

    /**
     * 获取上下文操作
     * @return string
     */
    public function getContextHtml()
    {
        if (!($this->handle_style & (self::HANDLE_STYLE_CONTEXT_BUTTON + self::HANDLE_STYLE_CONTEXT_LI))) {
            return '';
        }

        $where_template = $this->where_not_meet
            ? "{{# if (:where) { }} :html {{# } }}"
            : "{{# if (:where) { }} :html {{# }else{ }} :disable {{# } }}";
        $li         = '<div class="shadow" %s>%s %s</div>';
        $disable_li = '<div class="shadow layui-disabled" %s>%s %s</div>';
        $html = "";
        foreach ($this->tool_event as $event){
            if (empty($this->tool_event_html[$event]) || !$this->tool_event_html[$event] instanceof Button){
                continue;
            }
            $icon  = (fn() => $this->icon())->call($this->tool_event_html[$event]);
            $title = (fn() => $this->title)->call($this->tool_event_html[$event]);
            $li_normal   = sprintf($li, "lay-event='{$event}'", $icon, $title);
            $li_disabled = sprintf($disable_li, "", $icon, $title);

            $html .= isset($this->event_where[$event])
                ? strtr($where_template, [
                    ":where"   => $this->event_where[$event],
                    ":html"    => $li_normal,
                    ":disable" => $li_disabled
                ])
                : ($this->handle_style & self::HANDLE_STYLE_CONTEXT_LI ? $li_normal : $this->tool_event_html[$event]);
        }
        return $html;
    }

    /**
     * 获取上下文的js
     * @return string
     */
    public function getContextJs()
    {
        if (!($this->handle_style & (self::HANDLE_STYLE_CONTEXT_BUTTON + self::HANDLE_STYLE_CONTEXT_LI))) {
            return '';
        }

        return <<<JS
            var data = res.data;
            $('.layui-table-body tr').on('contextmenu', function (e) {
                var index = $(this).attr('data-index');  //获取该表格行的数据
                var x = e.originalEvent.x;  //获取鼠标位置x坐标
                var y = e.originalEvent.y;  //获取鼠标位置y坐标

                window.y_table_data = data[index];    //将该行的数据存放到自己定义的变量中
                let html = $('#sc-menu-s').html();
                layui.laytpl(html).render(data[index], function (html) {
                    let menu = $('#sc-menu');
                    menu.html(html);
                    var hw = $('html').offsetWidth;
                    var hh = $('html').offsetHeight;
                    let mw = menu.width();
                    let mh = menu.height();
                    x = (hw - x - 20) < mw ? (x - mw) : x;
                    y = (hh - y - 20) < mh ? (y - mh) : y;

                    if ($.trim(html) !== ''){
                        $("#sc-menu").show().css({
                            top: y + 'px',    //定位右键菜单的位置
                            left: x + 'px'
                        });
                    }
                });
                
                if ($.trim(html) !== ''){
                    e.preventDefault();//取消事件的默认动
                }
            });
            $(document).on('click', '#sc-menu>[lay-event]', function (e) {
                e.stopPropagation();
                if (table_page.tool_event.hasOwnProperty($(this).attr('lay-event'))) {
                    table_page.tool_event[$(this).attr('lay-event')]({data:window.y_table_data});
                    $('#sc-menu').hide();
                }
            }).on('contextmenu', '#sc-menu', (e)=>e.preventDefault());

            $(document).click(()=>$('#sc-menu').hide());
JS;

    }

    /**
     * 获取tool事件js
     * @return false|string
     */
    public function getToolJs()
    {
        return $this->getJsHandle();
    }

    /**
     * 获取toolbar事件js
     * @return string
     */
    public function getToolbarJs()
    {
        return $this->getJsHandle(true);
    }

    /**
     * js事件处理
     * @param bool $is_bar
     * @return string
     */
    private function getJsHandle($is_bar = false)
    {
        $js_exit = "";
        $event_js_var = $is_bar ? "toolbar_event_js" : "tool_event_js";
        $event_var    = $is_bar ? "toolbar_event" : "tool_event";
        foreach ($this->$event_js_var as $event => $js){
            in_array($event, $this->$event_var) and $js_exit .= "{$event}(obj){{$js}},";
        }
        return "{{$js_exit}};";
    }

    /**
     * @param string $size
     */
    public function setSize(string $size): void
    {
        $this->size = $size;
    }

    /**
     * 返回json
     * @return false|string
     */
    public function getConfig()
    {
        return json_encode($this->config, JSON_UNESCAPED_UNICODE) ?: '{}';
    }

    /**
     * 默认事件html
     */
    private function defaultEventHtml()
    {
        $this->toolbar_event_html = [
            'create' => Layui::button($this->lang('add'), 'add-1')->setEvent('create')->defaults('sm'),
            'delete' => Layui::button($this->lang('batch deletion'), 'delete')->setEvent('delete')->danger('sm')
        ];

        $this->tool_event_html = [
            'update' => Layui::button($this->lang('edit'), 'edit')->setEvent('update')->defaults('xs'),
            'delete' => Layui::button($this->lang('delete'), 'delete')->setEvent('delete')->danger('xs')
        ];
    }

    /**
     * 默认事件js
     * @throws \app\common\SdException
     */
    private function defaultEventJs()
    {
        if (!access_control($create_url = $this->url('create'))){
            $this->removeBarEvent('create');
        }
        if (!access_control($update_url = $this->url('update'))){
            $this->removeEvent('update');
        }
        if (!access_control($this->url('del'))){
            $this->removeEvent('delete');
            $this->removeBarEvent('delete');
        }

        $this->toolbar_event_js = [
            'create' => TableAux::openPage($create_url, $this->lang('add')),
            'delete' => TableAux::batchDataHandle('del')
        ];

        $this->tool_event_js = [
            'update' => TableAux::openPage([$update_url], $this->lang('edit')),
            'delete' => "del(obj.data[primary]);"
        ];
    }

    /**
     * 打开页面的js代码
     * @param string|array $url
     * @param string $title
     * @param array $config
     * @param bool $is_parent
     * @return string
     * @deprecated
     * @uses \sdModule\layui\tablePage\TableAux::openPage()
     * @throws \app\common\SdException
     */
    public function openPage($url, string $title, array $config = [], $is_parent = false)
    {
        return TableAux::openPage($url, $title, $config, $is_parent);
    }

    /**
     * 跳转
     * @param $url
     * @return string
     * @throws \app\common\SdException
     * @deprecated
     * @uses \sdModule\layui\tablePage\TableAux::jump()
     */
    public function jump(string $url)
    {
        return TableAux::jump($url);
    }

    /**
     * 打开新的tab标签
     * @param string|array $url
     * @param string $title
     * @return string
     * @deprecated
     * @throws \app\common\SdException
     * @uses \sdModule\layui\tablePage\TableAux::openTabs()
     */
    public function openTabsPage($url, string $title)
    {
        return TableAux::openTabs($url, $title);
    }

    /**
     * ajax请求的js代码
     * @param string $url
     * @param string $type
     * @param string $tip
     * @param string $title
     * @return string
     * @deprecated
     * @uses \sdModule\layui\tablePage\TableAux::ajax()
     * @throws \app\common\SdException
     */
    public function ajax(string $url, $type = 'get', string $tip = '' ,string $title = '警告')
    {
        return TableAux::ajax($url, $type, $tip, $title);
    }

    /**
     * ajax请求的js代码
     * @param string $url
     * @param string $type
     * @param string $tip
     * @return string
     * @deprecated
     * @uses \sdModule\layui\tablePage\TableAux::batchAjax()
     * @throws \app\common\SdException
     */
    public function batchAjax(string $url, $type = 'get', string $tip = '')
    {
        return TableAux::batchAjax($url, $type, $tip);
    }

    /**
     * 增加搜索条件
     * @param array $search
     * @deprecated
     * @uses \sdModule\layui\tablePage\TableAux::searchWhere()
     * @return string
     */
    public function searchWhere(array $search)
    {
        return TableAux::searchWhere($search);
    }

    /**
     * @param array $config
     * @return TablePage
     */
    public function setConfig(array $config): TablePage
    {
        $this->config = array_merge($config, $this->config);
        return $this;
    }
}

