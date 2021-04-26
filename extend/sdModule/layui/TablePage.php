<?php


namespace sdModule\layui;

use sdModule\layui\item\Button;
use sdModule\layui\tablePage\Event;
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
    private int $handleWidth = 80;
    /**
     * @var string 操作胡id
     */
    private string $handle = "#table_line";

    /**
     * @var array 字段数据
     */
    private array $fieldData;

    /**
     * @var array 函数替换的数据
     */
    private array $functionReplace = [];

    /**
     * @var string 行大小
     */
    private string $size = '';

    /**
     * @var array|string[] 事件
     */
    private array $toolEvent = ['update'];

    /**
     * @var array|string[] html
     */
    private array $toolEventHtml = [];

    /**
     * @var array|string[] js
     */
    private array $toolEventJs = [];

    /**
     * @var array|string[] 事件
     */
    private array $toolbarEvent = ['create', 'delete'];

    /**
     * @var array|string[] html
     */
    private array $toolbarEventHtml = [];

    /**
     * @var array|string[] js
     */
    private array $toolbarEventJs = [];

    /**
     * @var array|string[] js
     */
    private array $eventWhere = [];

    /**
     * @var array 不满租条件的事件是否隐藏
     */
    private array $whereNotMeet = [];
    /**
     * @var array 表格配置
     */
    private array $config = [];

    /**
     * @var int 设置表格操作样式
     */
    private int $handleStyle = self::HANDLE_STYLE_ALL;
    /**
     * @var array
     */
    private array $handleAttr = [];

    /**
     * 设置操作栏宽度
     * @param int $width
     * @return $this
     */
    public function setHandleWidth(int $width): TablePage
    {
        $this->handleWidth = $width;
        return $this;
    }

    /**
     * 创建页面
     * @param array $fieldData 字段数据
     * @return TablePage
     * @throws \app\common\SdException
     */
    public static function create(array $fieldData): TablePage
    {
        $table = new self();
        $table->defaultEventHtml();
        $table->defaultEventJs();
        $table->config    = array_filter(config('admin.layui_config'));
        $table->fieldData = array_map(fn($v) => array_filter($v->toArray()), $fieldData);
        $table->functionHandle();
        return $table;
    }

    /**
     * 设置操作栏的js ID标识 eg: #table_line
     * @param string $handle
     * @return $this
     */
    public function setHandle(string $handle): TablePage
    {
        $this->handle = $handle;
        return $this;
    }

    /**
     * @param int $style  1, 2, 3
     * @return TablePage
     */
    public function setHandleStyle(int $style = self::HANDLE_STYLE_NORMAL): TablePage
    {
        $this->handleStyle = $style;
        return $this;
    }


    /**
     * 获取字段信息
     * @return string
     */
    public function getField(): string
    {
        if ($this->handle && $this->toolEvent && ($this->handleStyle & self::HANDLE_STYLE_NORMAL)) {
            $this->fieldData[] = array_merge([
                "width"   => $this->handleWidth,
                "title"   => lang("operating"),
                "templet" => $this->handle,
            ], $this->handleAttr);
        }

        $data = json_encode($this->fieldData, JSON_UNESCAPED_UNICODE);

        return strtr($data, $this->functionReplace);
    }

    /**
     * 函数处理
     * @return mixed
     */
    private function functionHandle(): array
    {
        foreach ($this->fieldData as &$item) {
            if (empty($item['templet'])) {
                continue;
            }

            if ($item['templet'] instanceof \Closure) {
                $js_code = call_user_func($item['templet']);
                $this->functionReplace["\"@{$item['field']}\""] = "function(obj){{$js_code}}";
                $item['templet'] = empty($item['field']) ? '' : "@{$item['field']}";
            } elseif ($item['templet'] === '@image') {
                $this->config['size'] = 'lg';
                $this->functionReplace["\"@{$item['field']}\""] = "function (obj) {return custom.tableImageShow(obj.{$item['field']});}";
                $item['templet'] = empty($item['field']) ? '' : "@{$item['field']}";
            }
        }
        return $this->fieldData;
    }

    /**
     * @param $method
     * @param $vars
     * @return mixed|string
     */
    public function __call($method, $vars): string
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
     * 添加事件
     * @param string $event
     * @return Event
     */
    public function addEvent(string $event): Event
    {
        return new Event($this, $event);
    }

    /**
     * 添加事件头部bar的事件
     * @param string $event
     * @return Event
     */
    public function addBarEvent(string $event):Event
    {
        return new Event($this, $event, true);
    }

    /**
     * 删除事件
     * @param array|string $event
     * @return TablePage
     */
    public function removeEvent($event): TablePage
    {
        return $this->removeEventHandle($event);
    }

    /**
     * 删除头部bar的事件
     * @param array|string $event
     * @return TablePage
     */
    public function removeBarEvent($event): TablePage
    {
        return $this->removeEventHandle($event, true);
    }

    /**
     * 删除事件处理
     * @param array|string $event
     * @param bool $is_bar
     * @return TablePage
     */
    private function removeEventHandle($event, bool $is_bar = false): TablePage
    {
        if ($is_bar){
            $this->toolbarEvent = array_diff($this->toolbarEvent, (array)$event);
        }else{
            $this->toolEvent    = array_diff($this->toolEvent, (array)$event);
        }
        return $this;
    }

    /**
     * 展示条件
     * @param array|string $event
     * @param null|string|bool $where 条件
     * @param bool $isHidden 是否隐藏按钮（不满足条件）
     */
    public function setEventWhere($event, $where = null, $isHidden = false)
    {
        if (is_array($event)){
            $this->eventWhere    = array_merge($this->eventWhere, $event);
            foreach ($event as $e){
                $this->whereNotMeet[$e] = $where === null ? $isHidden : $where;
            }
        }else{
            $this->eventWhere[$event]   = $where;
            $this->whereNotMeet[$event] = $isHidden;
        }
    }

    /**
     * @return string 获取头部工具栏html
     */
    public function getToolbar(): string
    {
        return implode(array_map(fn($v) => ($this->toolbarEventHtml[$v] ?? ''), $this->toolbarEvent));
    }

    /**
     * @return string 获取工具栏html
     */
    public function getTool(): string
    {
        if (!($this->handleStyle & self::HANDLE_STYLE_NORMAL)) {
            return '';
        }

        return implode(array_map(function ($event){
            $whereTemplate = empty($this->whereNotMeet[$event])
                ? "{{# if (:where) { }} :html {{# }else{ }} :disable {{# } }}"
                : "{{# if (:where) { }} :html {{# } }}";

            if (empty($this->toolEventHtml[$event])){
                return '';
            }

            $disable = preg_replace('/lay-event="\w+"/', '', $this->toolEventHtml[$event]);
            if (!preg_match('/(btn-danger)|(btn-warm)|(btn-normal)|(btn-primary)/', $disable)){
                $disable = strtr($disable, ['layui-btn ' => 'layui-btn layui-btn-disabled ']);
            }else{
                $disable = preg_replace(['/danger/', '/warm/', '/normal/', '/primary/'], 'disabled', $disable);
            }

            return isset($this->eventWhere[$event])
                ? strtr($whereTemplate, [
                    ':where'    => $this->eventWhere[$event],
                    ':html'     => $this->toolEventHtml[$event],
                    ':disable'  => $disable
                ])
                : $this->toolEventHtml[$event];
        }, $this->toolEvent));
    }

    /**
     * 获取上下文操作
     * @return string
     */
    public function getContextHtml(): string
    {
        if (!($this->handleStyle & (self::HANDLE_STYLE_CONTEXT_BUTTON + self::HANDLE_STYLE_CONTEXT_LI))) {
            return '';
        }

        $li         = '<div class="shadow" %s>%s %s</div>';
        $disable_li = '<div class="shadow layui-disabled" %s>%s %s</div>';
        $html = "";
        foreach ($this->toolEvent as $event){
            $where_template = empty($this->whereNotMeet[$event])
                ? "{{# if (:where) { }} :html {{# }else{ }} :disable {{# } }}"
                : "{{# if (:where) { }} :html {{# } }}";
            if (empty($this->toolEventHtml[$event]) || !$this->toolEventHtml[$event] instanceof Button){
                continue;
            }
            $icon  = (fn() => $this->icon())->call($this->toolEventHtml[$event]);
            $title = (fn() => $this->title)->call($this->toolEventHtml[$event]);
            $liNormal   = sprintf($li, "lay-event='{$event}'", $icon, $title);
            $liDisabled = sprintf($disable_li, "", $icon, $title);

            $html .= isset($this->eventWhere[$event])
                ? strtr($where_template, [
                    ":where"   => $this->eventWhere[$event],
                    ":html"    => $liNormal,
                    ":disable" => $liDisabled
                ])
                : ($this->handleStyle & self::HANDLE_STYLE_CONTEXT_LI ? $liNormal : $this->toolEventHtml[$event]);
        }
        return $html;
    }

    /**
     * 获取上下文的js
     * @return string
     */
    public function getContextJs(): string
    {
        if (!($this->handleStyle & (self::HANDLE_STYLE_CONTEXT_BUTTON + self::HANDLE_STYLE_CONTEXT_LI))) {
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
    public function getToolbarJs(): string
    {
        return $this->getJsHandle(true);
    }

    /**
     * js事件处理
     * @param bool $isBar
     * @return string
     */
    private function getJsHandle($isBar = false): string
    {
        $jsExit = "";
        $eventJsVar  = $isBar ? "toolbarEventJs" : "toolEventJs";
        $eventVar    = $isBar ? "toolbarEvent" : "toolEvent";
        foreach ($this->$eventJsVar as $event => $js){
            in_array($event, $this->$eventVar) and $jsExit .= "{$event}(obj){{$js}},";
        }
        return "{{$jsExit}}";
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
        $this->toolbarEventHtml = [
            'create' => Layui::button($this->lang('add'), 'add-1')->setEvent('create')->defaults('sm'),
            'delete' => Layui::button($this->lang('delete'), 'delete')->setEvent('delete')->danger('sm')
        ];

        $this->toolEventHtml = [
            'update' => Layui::button($this->lang('edit'), 'edit')->setEvent('update')->defaults('xs'),
        ];
    }

    /**
     * 默认事件js
     * @throws \app\common\SdException
     */
    private function defaultEventJs()
    {
        if (!access_control($createUrl = $this->url('create'))){
            $this->removeBarEvent('create');
        }
        if (!access_control($updateUrl = $this->url('update'))){
            $this->removeEvent('update');
        }
        if (!access_control($this->url('del'))){
            $this->removeBarEvent('delete');
        }

        $this->toolbarEventJs = [
            'create' => TableAux::openPage($createUrl, $this->lang('add')),
            'delete' => TableAux::batchAjax(url('del'), 'post')->setConfig(['icon' => 3])->setTip('确认删除数据吗？')
        ];

        $this->toolEventJs = [
            'update' => TableAux::openPage([$updateUrl], $this->lang('edit')),
        ];
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

    /**
     * @param array $handleAttr
     * @return TablePage
     */
    public function setHandleAttr(array $handleAttr): TablePage
    {
        $this->handleAttr = $handleAttr;
        return $this;
    }
}

