<?php
/**
 * datetime: 2021/9/18 21:14
 * user    : chenlong<vip_chenlong@163.com>
 **/

namespace sdModule\layui\lists;

use sdModule\layui\item\Button;
use sdModule\layui\lists\module\Column;
use sdModule\layui\lists\module\EventHandle;
use sdModule\layui\lists\moduleSetProxy\Event;

/**
 * 页面数据
 * Class PageData
 * @package sdModule\layui\lists
 * @author chenlong<vip_chenlong@163.com>
 * @date 2021/9/18
 */
class PageData
{
    /**
     * @var array
     */
    private $data = [];


    /**
     * 数据列展示配置
     * PageData constructor.
     * @param array|Column[] $column
     * @throws \app\common\SdException
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/9/18
     */
    public function __construct(array $column)
    {
        $this->data['column'] = $column;
        $this->setDefaultEvent();
    }

    /**
     * 创建页面数据
     * @param array $column
     * @return PageData
     * @throws \app\common\SdException
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/9/21
     */
    public static function create(array $column): PageData
    {
        return new self($column);
    }

    /**
     * 删除事件
     * @param array $event 事件名字，不传清空已有全部事件
     * @return PageData
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/6/17
     */
    public function removeEvent(array $event = []): PageData
    {
        $this->data['event'] = $event ? array_diff_key($this->data['event'], array_flip($event)) : [];
        return $this;
    }

    /**
     * 删除bar事件
     * @param array $event 事件名字，不传清空已有全部事件
     * @return PageData
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/6/17
     */
    public function removeBarEvent(array $event = []): PageData
    {
        $this->data['barEvent'] = $event ? array_diff_key($this->data['barEvent'], array_flip($event)) : [];
        return $this;
    }


    /**
     * 添加行事件
     * @param string $event_name
     * @return Event
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/9/21
     */
    public function addEvent(string $event_name = ''): Event
    {
        return new Event($this, $event_name, false);
    }

    /**
     * 添加头部事件
     * @param string $event_name
     * @return Event
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/9/21
     */
    public function addBarEvent(string $event_name = ''): Event
    {
        return new Event($this, $event_name, true);
    }

    /**
     * 设置操作栏的属性
     * @param array $handleAttr
     * @return PageData
     */
    public function setHandleAttr(array $handleAttr): PageData
    {
        $this->data['handleAttr'] = $handleAttr;
        return $this;
    }

    /**
     * 设置配置
     * @param array $config
     * @return PageData
     */
    public function setConfig(array $config): PageData
    {
        $this->data['config'] = $config;
        return $this;
    }

    /**
     * @param string $doneJs
     * @return PageData
     */
    public function setDoneJs(string $doneJs): PageData
    {
        $this->data['doneJs'] = $doneJs;
        return $this;
    }

    /**
     * 添加自定义js
     * @param string $js
     * @return PageData
     */
    public function addJs(string $js): PageData
    {
        $this->data['js'][] = $js;
        return $this;
    }

    /**
     * 添加自定义css
     * @param string $css
     * @return PageData
     */
    public function addCss(string $css): PageData
    {
        $this->data['css'][] = $css;
        return $this;
    }

    /**
     * 设置菜单组的展示
     * @param string $group_name 组名
     * @param Button $button     按钮
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/9/22
     */
    public function setMenuGroup(string $group_name, Button $button): PageData
    {
        $this->data['menu_group'][$group_name] = $button;
        return $this;
    }

    /**
     * 页面数据完成，数据传输到渲染
     * @return PageRender
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/9/20
     */
    public function render(): PageRender
    {
        return new PageRender($this->data);
    }

    /**
     * @param $name
     * @param $value
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/9/21
     */
    public function __set($name, $value)
    {
        $this->data[$name] = $value;
    }

    /**
     * 设置默认事件
     * @throws \app\common\SdException
     * @author chenlong <vip_chenlong@163.com>
     * @date 2021/6/8
     */
    private function setDefaultEvent()
    {
        $this->addEvent('update')->setDefaultBtn('修改', 'edit', 'xs')
            ->setJs(EventHandle::openPage([url('update'), 'id'], '修改')->popUps());

        $this->addBarEvent('create')->setDefaultBtn('新增', 'add-1', 'sm')
            ->setJs(EventHandle::openPage(url('create'), '新增')->popUps());

        $this->addBarEvent('delete')->setDangerBtn('批量删除', 'delete', 'sm')
            ->setJs(EventHandle::ajax(url('delete'), '确认删除吗？', 'post')->setBatch());
    }

}

