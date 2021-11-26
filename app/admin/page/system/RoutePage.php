<?php
/**
 * Date: 2020/11/25 14:44
 * User: chenlong <vip_chenlong@163.com>
 */

namespace app\admin\page\system;


use app\common\BasePage;
use sdModule\layui\lists\module\Column;
use sdModule\layui\lists\module\EventHandle;
use sdModule\layui\lists\PageData;

class RoutePage extends BasePage
{
    public $list_template = 'common/tree_list_page';

    /**
     * 获取创建列表table的数据
     * @return PageData
     * @throws \app\common\SdException
     */
    public function listPageData(): PageData
    {
        $table = PageData::create([
            Column::checkbox(),
            Column::normal('ID', 'id')->moreConfiguration('width', 100),
            Column::normal('标题', 'title'),
            Column::normal('类型', 'type'),
            Column::normal('路由地址', 'route'),
            Column::normal('排序权重', 'weigh'),
        ]);

        $table->setHandleAttr([
            'width' => 150,
        ]);
        $table->setConfig([
            'treeColIndex' => 2
        ]);

        $table->removeBarEvent(['delete']);

        $table->addEvent('delete')->setDangerBtn('删除', 'delete', 'xs')
            ->setJs(EventHandle::ajax(url('delete'), '节点删除会同时删除对应的所有子节点，确认删除吗？')
                ->setConfig(['icon' => 3])->successCallback('tableRender();'));

        $table->addBarEvent()->setWarmBtn('自动检测', '', 'sm')
            ->setJs(EventHandle::openPage(url('automaticDetection'), '自动检测')->popUps());

        return $table;
    }
}
