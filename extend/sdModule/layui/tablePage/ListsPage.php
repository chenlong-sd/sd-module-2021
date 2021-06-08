<?php
/**
 * Date: 2021/6/8 9:17
 * User: chenlong <vip_chenlong@163.com>
 */

namespace sdModule\layui\tablePage;


use sdModule\layui\Dom;
use sdModule\layui\Layui;

class ListsPage
{
    const MENU_MODE = 1;
    const BUTTON_MODE = 2;

    /**
     * @var string 字段的配置
     */
    private string $filedConfig = '';
    /**
     * @var array table 的配置
     */
    private array $config = [];
    /**
     * @var array 行事件
     */
    private array $event = [];
    /**
     * @var array|\sdModule\layui\tablePage\module\Event[] 头部事件
     */
    private array $barEvent = [];

    /**
     * @var int 事件模式
     */
    private int $eventMode = self::BUTTON_MODE;

    /**
     * ListsPage constructor.
     * @param TableColumn[] $filedConfig
     */
    public function __construct(array $filedConfig)
    {
        $this->filedConfig = $this->fieldConfigDataHandle($filedConfig);
    }


    /**
     * 创建列表页面数据
     * @param TableColumn[] $filedConfig
     * @return ListsPage
     * @author chenlong <vip_chenlong@163.com>
     * @date 2021/6/8
     */
    public static function create(array $filedConfig): ListsPage
    {
        return new self($filedConfig);
    }

    /**
     * 添加事件
     * @param string $event
     * @return module\Event
     * @author chenlong <vip_chenlong@163.com>
     * @date 2021/6/8
     */
    public function addEvent(string $event = ''): module\Event
    {
        return new \sdModule\layui\tablePage\module\Event($this, $event);
    }

    /**
     * 添加头部事件
     * @param string $event
     * @return module\Event
     * @author chenlong <vip_chenlong@163.com>
     * @date 2021/6/8
     */
    public function addBarEvent(string $event = ''): module\Event
    {
        return new \sdModule\layui\tablePage\module\Event($this, $event, true);
    }

    /**
     * 设置事件模式
     * @param int $eventMode
     * @return ListsPage
     */
    public function setEventMode(int $eventMode): ListsPage
    {
        $this->eventMode = $eventMode;
        return $this;
    }


    /**
     * 获取事件元素的html
     * @param bool $is_bar 是否是头部事件
     * @return string
     * @author chenlong <vip_chenlong@163.com>
     * @date 2021/6/8
     */
    public function getEventElement(bool $is_bar = false): string
    {
        return $this->btnModeElement($is_bar ? $this->barEvent :$this->event);
    }

    /**
     * 下拉菜单模式的html数据
     * @param bool $is_bar
     * @return false|string
     * @author chenlong <vip_chenlong@163.com>
     * @date 2021/6/8
     */
    public function getMenuModeEventData(bool $is_bar = false)
    {
        $dom = [];
        $element = $is_bar ? $this->barEvent : $this->event;
        foreach ($element as $event){
            $elem = Dom::create('span');
            if ($event->icon){
                $icon = $event->icon;
                if (substr($event->icon, 0, 9) === 'layui-icon'){
                    $icon = substr($event->icon, 9);
                }
                $icon = Dom::create('i')->addClass("layui-icon layui-icon-{$icon}");
                $elem->addContent($icon);
            }
            $dom['templet'] = (string)$elem->addContent($event->title);
            $dom['title'] = $event->title;
            $dom['id'] = $event->event;
        }

        return json_encode($dom, JSON_UNESCAPED_UNICODE);
    }


    /**
     * 按钮模式的元素html
     * @param array|\sdModule\layui\tablePage\module\Event[] $element
     * @return string
     * @author chenlong <vip_chenlong@163.com>
     * @date 2021/6/8
     */
    private function btnModeElement(array $element): string
    {
        $btn = [];
        foreach ($element as $event){
            $btn[] = Layui::button($event->title, $event->icon)->setEvent($event->event)->setSize($event->btnSize)
                ->addBtnClass("layui-btn-{$event->btnType}");
        }
        return implode($btn);
    }

    /**
     * 字段数据处理，主要针对模板处理
     * @param TableColumn[] $filedConfig
     * @return string
     * @author chenlong <vip_chenlong@163.com>
     * @date 2021/6/8
     */
    private function fieldConfigDataHandle(array $filedConfig): string
    {
        $functionReplace = [];

        foreach ($filedConfig as $column) {
            if (empty($column['templet'])) {
                continue;
            }

            $field             = empty($column['field']) ? 'field' . rand(1, 999) : $column['field'];
            $column['templet'] = "@{$field}";

            if ($column['templet'] instanceof \Closure) {
                $js_code = call_user_func($column['templet']);
                $functionReplace["\"@{$field}\""] = "function(obj){{$js_code}}";
            } elseif ($column['templet'] === '@image') {
                $this->config['size'] = 'lg';
                $functionReplace["\"@{$field}\""] = "function (obj) {return custom.tableImageShow(obj.{$field});}";
            }
        }

        $filedConfig = json_encode(array_map(fn($v) => array_filter($v->toArray()), $filedConfig), JSON_UNESCAPED_UNICODE);
        return strtr($filedConfig, $functionReplace);
    }

}
