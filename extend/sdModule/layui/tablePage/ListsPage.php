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
     * @var array 字段的配置
     */
    private array $filedConfig = [];
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
     * @var array 页面的js
     */
    private array $js = [];
    /**
     * @var array 操作列的数据
     */
    private array $handleAttr = [];

    /**
     * ListsPage constructor.
     * @param TableColumn[] $filedConfig
     * @throws \app\common\SdException
     */
    public function __construct(array $filedConfig)
    {
        $this->filedConfig = $filedConfig;
        $this->setDefaultEvent();
    }


    /**
     * 创建列表页面数据
     * @param TableColumn[] $filedConfig
     * @return ListsPage
     * @throws \app\common\SdException
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
     * 开始页面渲染
     * @author chenlong <vip_chenlong@163.com>
     * @date 2021/6/8
     */
    public function render()
    {
        $this->eventPowerCheck();
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
        $event = $is_bar ? $this->barEvent : $this->event;
        $js    = array_map(fn($v) => "{$v->event}(obj){{$v->js}},", $event);
        return implode($js);
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
        if ($this->eventMode === self::BUTTON_MODE) {
            return $this->btnModeElement($is_bar ? $this->barEvent : $this->event);
        }
        return $is_bar ? $this->btnModeElement($this->barEvent): $this->menuModeElement();
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
            $dom['title']   = $event->title;
            $dom['id']      = $event->event;
        }

        return json_encode($dom, JSON_UNESCAPED_UNICODE);
    }


    /**
     * 添加js
     * @param string $js
     * @return ListsPage
     */
    public function addJs(string $js): ListsPage
    {
        $this->js[] = $js;
        return $this;
    }

    /**
     * @return string
     * @author chenlong <vip_chenlong@163.com>
     * @date 2021/6/8
     */
    public function getJs(): string
    {
        return implode($this->js);
    }

    /**
     * @author chenlong <vip_chenlong@163.com>
     * @date 2021/6/8
     */
    private function eventPowerCheck()
    {
        array_filter($this->event,    fn($v) => $v->js !== 'false');
        array_filter($this->barEvent, fn($v) => $v->js !== 'false');
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
     * 按钮模式的元素html
     * @return string
     * @author chenlong <vip_chenlong@163.com>
     * @date 2021/6/8
     */
    private function menuModeElement(): string
    {
        return Layui::button('操作', 'senior')->addBtnClass('menu-down-sc')->normal('xs');
    }

    /**
     * 默认事件
     * @throws \app\common\SdException
     * @author chenlong <vip_chenlong@163.com>
     * @date 2021/6/8
     */
    private function setDefaultEvent()
    {
        $this->addEvent('update')->setNormalBtn('修改', 'edit', 'xs')
            ->setJs(TableAux::openPage([url('update')], '修改'));

        $this->addBarEvent('create')->setDefaultBtn('修改', 'add-1', 'sm')
            ->setJs(TableAux::openPage(url('update'), '修改'));

        $this->addBarEvent('delete')->setDangerBtn('批量删除', 'delete', 'sm')
            ->setJs(TableAux::batchAjax(url('del'), 'post')->setTip('确认删除吗？'));
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
            if ($column->js) $this->addJs($column->js);

            if (empty($column['templet'])) {
                continue;
            }

            $field = empty($column['field']) ? 'field' . rand(1, 999) : $column['field'];
            if ($column['templet'] instanceof \Closure) {
                $js_code = call_user_func($column['templet']);
                $functionReplace["\"@{$field}\""] = "function(obj){{$js_code}}";
                $column['templet'] = "@{$field}";
            } elseif ($column['templet'] === '@image') {
                $column['templet'] = "@{$field}";
                $this->config['size'] = 'lg';
                $functionReplace["\"@{$field}\""] = "function (obj) {return custom.tableImageShow(obj.{$field});}";
            }
        }

        $filedConfig = json_encode(array_map(fn($v) => array_filter($v->toArray()), $filedConfig), JSON_UNESCAPED_UNICODE);
        return strtr($filedConfig, $functionReplace);
    }

    /**
     * @return string
     */
    public function getFiledConfig(): string
    {
        if ($this->event) {
            $handle = array_merge(['templet' => "#table_line"], $this->handleAttr);
            $this->filedConfig[] = TableAux::column('', '操作')->param($handle);
        }

        return $this->fieldConfigDataHandle($this->filedConfig);
    }

    /**
     * @return string
     * @author chenlong <vip_chenlong@163.com>
     * @date 2021/6/8
     */
    public function getConfig(): string
    {
        return json_encode($this->config);
    }

    /**
     * @return int
     */
    public function getEventMode(): int
    {
        return $this->eventMode;
    }

    /**
     * @param array $handleAttr
     * @return ListsPage
     */
    public function setHandleAttr(array $handleAttr): ListsPage
    {
        $this->handleAttr = $handleAttr;
        return $this;
    }

}
