<?php
/**
 * Date: 2020/11/25 14:54
 * User: chenlong <vip_chenlong@163.com>
 */

namespace app\admin\page\system;


use app\admin\enum\LogEnumMethod;
use app\common\BasePage;
use sdModule\layui\form\Form as DefaultForm;
use sdModule\layui\form\FormUnit;
use sdModule\layui\lists\module\Column;
use sdModule\layui\lists\PageData;

class LogPage extends BasePage
{
    /**
     * 获取创建列表table的数据
     * @return array
     * @throws \ReflectionException
     * @throws \app\common\SdException
     */
    public function listPageData(): PageData
    {
        $table = PageData::create([
            Column::normal('请求方式', 'method'),
            Column::normal('权限节点名', 'route_title'),
            Column::normal('操作管理员', 'administrators_name'),
            Column::normal('请求参数', 'param'),
            Column::normal('节点地址', 'route'),
            Column::normal('创建时间', 'create_time'),
        ]);

        $table->removeEvent();
        $table->removeBarEvent();

        return $table;
    }

    /**
     * @return DefaultForm
     * @throws \ReflectionException
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/11/9
     */
    public function listSearchFormData():DefaultForm
    {
        $form_data = [
            FormUnit::build(
                FormUnit::text('route.title%%')->placeholder('节点名'),
                FormUnit::select('i.method')->placeholder("请求方式")->options(LogEnumMethod::getAllMap()),
                FormUnit::text('i.route%%')->placeholder('节点地址'),
                FormUnit::text('administrators.name%%')->placeholder('操作人员'),
                FormUnit::time('i.create_time_~')->placeholder('创建时间'),
                FormUnit::custom()->customHtml(DefaultForm::searchSubmit())
            )
        ];

        return DefaultForm::create($form_data)->setSubmitHtml()->complete();
    }
}
