<?php
/**
 * QuickOperation.php
 * Date: 2021-12-03 21:05:04
 */

namespace app\admin\page\system;

use app\common\BasePage;
use sdModule\layui\lists\module\Ajax;
use sdModule\layui\lists\module\Column;
use sdModule\layui\lists\module\EventHandle;
use sdModule\layui\lists\PageData;
use sdModule\layui\form4\FormProxy as Form;
use sdModule\layui\form4\FormUnit;
use app\admin\model\system\Route;
use app\admin\enum\QuickOperationEnumIsShow;
use app\admin\model\system\Administrators;


/**
 * 快捷操作
 * Class QuickOperationPage
 * @package app\admin\page\system\QuickOperationPage
 */
class QuickOperationPage extends BasePage
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
            Column::normal('节点', 'route_title'),
            Column::normal('是否有效', 'route')->setTemplate('return obj.route ? "有效" : ""'),
            Column::normal('是否展示', 'is_show_true')
                ->showSwitch('is_show', QuickOperationEnumIsShow::getMap(true), new Ajax(admin_url('quick-entrance-set'))),
        ]);

        $table->removeEvent();
        $table->removeBarEvent();

        $table->setConfig([
            'treeColIndex' => 0
        ]);

        return $table;
    }

    /**
    * 生成表单的数据
    * @param string $scene
    * @param array $default_data
    * @return Form
    */
    public function formPageData(string $scene, array $default_data = []): Form
    {
        $unit = [
            FormUnit::hidden('id'),
            FormUnit::select('route_id', '节点')->options(Route::column('title', 'id')),
            FormUnit::radio('is_show', '是否展示')->options(QuickOperationEnumIsShow::getMap(true)),
            FormUnit::select('administrators_id', '管理员')->options(Administrators::column('name', 'id')),
        ];

        $form = Form::create($unit, $default_data)->setScene($scene);

        return $form;
    }




}
