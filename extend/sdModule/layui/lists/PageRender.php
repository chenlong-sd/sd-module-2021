<?php
/**
 * datetime: 2021/9/18 21:12
 * user    : chenlong<vip_chenlong@163.com>
 **/

namespace sdModule\layui\lists;

use sdModule\layui\Dom;
use sdModule\layui\Layui;
use sdModule\layui\lists\moduleSetProxy\Column;
use sdModule\layui\lists\module\Event;

/**
 * 列表页面
 * Class Page
 * @package sdModule\layui\lists
 * @author chenlong<vip_chenlong@163.com>
 * @date 2021/9/18
 */
class PageRender
{
    /**
     * @var array 页面配置数据
     */
    private $pageData;

    /**
     * @var array 头部下拉菜单的数据
     */
    private $headDropDownMenuData = [];

    /**
     * @var array 行下拉菜单的数据
     */
    private $rowDropDownMenuData = [];

    /**
     * PageRender constructor.
     * @param array $page_data
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/9/21
     */
    public function __construct(array $page_data)
    {
        $this->pageData = $page_data;
    }

    /**
     * 获取页面的数据
     * @param string $data_name 数据名字
     * @param array|string $default    数据默认值
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/9/21
     */
    private function getPageData(string $data_name, $default = [])
    {
        return $this->pageData[$data_name] ?? $default;
    }

    /**
     * 获取页面新增的css
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/9/21
     */
    public function getCss(): string
    {
        return implode($this->getPageData('css'));
    }

    /**
     * 获取页面新增的js
     * @return string
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/9/21
     */
    public function getJs(): string
    {
        return implode($this->getPageData('js'));
    }

    /**
     * 获取事件js
     * @param bool $is_bar 是否是头部事件
     * @return string
     * @author chenlong <vip_chenlong@163.com>
     * @date 2021/6/8
     */
    public function getEventJs(bool $is_bar = false): string
    {
        $event = $this->getPageData($is_bar ? 'barEvent' : 'event');
        $js    = array_map(function ($v) {
            return "$v->event(obj){ $v->js; }";
        }, $event);
        return implode(',', $js);
    }

    /**
     * 获取页面表格配置
     * @return string
     * @author chenlong <vip_chenlong@163.com>
     * @date 2021/6/8
     */
    public function getConfig(): string
    {
        return json_encode($this->getPageData('config', []));
    }

    /**
     * 页面渲染成功后的js
     * @return string
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/9/18
     */
    public function getDoneJs()
    {
        return $this->getPageData('doneJs', '');
    }

    /**
     * 获取事件元素字符串
     * @param bool $is_bar
     * @return string
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/9/23
     */
    public function getEventElement(bool $is_bar = false): string
    {
        $events     = $this->getPageData($is_bar ? 'barEvent' : 'event', []);
        $menu_group = $this->getPageData('menu_group', []);

        /** @var Event $event */
        $element = $ignore = [];
        foreach ($events as $event_name => $event){
            if (in_array($event_name, $ignore)) continue;

            // 没有对事件分组的处理
            if (!$event->menuGroup) {
                $showButton = Layui::button($event->title, $event->icon)
                    ->setSize($event->btnSize)
                    ->setEvent($event->event)
                    ->addBtnClass("layui-btn-{$event->btnType}");
                $disabledButton = Layui::button($event->title, $event->icon)
                    ->setSize($event->btnSize)
                    ->addBtnClass("layui-btn-disabled");
                $code = $showButton;
                if ($event->where) {
                    $where = preg_replace('/\{([a-zA-Z0-9_]+)\}/', 'd.$1', $event->where);
                    $code = "{{# if ($where) { }} $showButton {{# }else{ }} $disabledButton {{# } }}";
                }
                $element[] = $code;
                continue;
            }
            // 创建分组的按钮元素
            $menu_group_default_element = Layui::button('更多操作', 'more-vertical')->primary('xs');
            $menu_group_element = $menu_group[$event->menuGroup] ?? $menu_group_default_element;
            $element[] = $menu_group_element->addBtnClass(($is_bar ? 'sc-menu-head-' : 'sc-menu-row-') . $event->menuGroup);

            // 找出和该组的所有事件进行处理
            /** @var Event $event_g */
            $menu_group_item = [];
            foreach ($events as $event_name_g => $event_g){
                if ($event_g->menuGroup != $event->menuGroup) {
                    continue;
                }
                $elem = Dom::create('span');
                if ($event_g->icon){
                    $icon = $event_g->icon;
                    if (substr($event_g->icon, 0, 9) === 'layui-icon'){
                        $icon = substr($event_g->icon, 9);
                    }
                    $icon = Dom::create('i')->addClass("layui-icon layui-icon-{$icon}");
                    $elem->addContent($icon);
                }
                $menu_group_item[] = [
                    'templet' => (string)$elem->addContent($event_g->title),
                    'title'   => $event_g->title,
                    'id'      => $event_g->event,
                    'where'   => $event_g->where,
                ];
                $ignore[] = $event_name_g;
            }
            $is_bar ? $this->headDropDownMenuData['sc-menu-head-' . $event->menuGroup] = $menu_group_item
                : $this->rowDropDownMenuData['sc-menu-row-' . $event->menuGroup] = $menu_group_item;
        }

        return implode($element);
    }

    /**
     * 获取头部下拉菜单
     * @return array
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/9/23
     */
    public function getHeaderDropDownMenu(): array
    {
        return $this->headDropDownMenuData;
    }

    /**
     * 获取行事件的下拉菜单
     * @return array
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/9/23
     */
    public function getRowDropDownMenu(): array
    {
        return $this->rowDropDownMenuData;
    }

    /**
     * 获取列配置
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/9/23
     */
    public function getColumnConfigure(): string
    {
        $columns = $this->getPageData('column', []);
        if ($this->getPageData('event')) {
            $handle_attr = array_merge(['templet' => "#table_line", 'width' => 150, 'align' => 'center'], $this->getPageData('handleAttr', []));
            $columns[] = Column::space('操作')->moreConfiguration($handle_attr);
        }

        $functionReplace = [];

        foreach ($columns as $column) {
            if ($column->js) $this->pageData['js'][] = $column->js;
            if (empty($column['templet']) || !is_callable($column['templet'])) continue;

            $field = empty($column['field']) ? 'field' . rand(1, 999) : $column['field'];

            $js_code = call_user_func($column['templet']);

            if ($js_code === '@image') {
                $js_code = "return custom.tableImageShow(obj.{$column['field']});";
                $this->pageData['config']['size'] = 'lg';
            }
            $functionReplace["\"@{$field}\""] = "function(obj){ $js_code }";
            $column['templet'] = "@{$field}";
        }

        $columnConfigure = json_encode(array_map(function ($v) {
            return array_filter($v->configInfo);
        }, $columns), JSON_UNESCAPED_UNICODE);

        return strtr($columnConfigure, $functionReplace);
    }
}

