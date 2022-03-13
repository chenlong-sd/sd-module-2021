<?php
/**
 * Date: 2020/11/25 14:54
 * User: chenlong <vip_chenlong@163.com>
 */

namespace app\admin\page\system;


use app\admin\enum\LogEnumMethod;
use app\admin\model\system\Log as LogModel;
use app\common\BasePage;
use sdModule\layui\Dom;
use sdModule\layui\form4\FormProxy as DefaultForm;
use sdModule\layui\form4\FormUnit;
use sdModule\layui\lists\module\Column;
use sdModule\layui\lists\module\EventHandle;
use sdModule\layui\lists\PageData;
use sdModule\layui\tableDetail\Page;
use sdModule\layui\tableDetail\Table;

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
            Column::normal('节点地址', 'route'),
            Column::normal('创建时间', 'create_time'),
        ]);

        $table->removeEvent();
        $table->removeBarEvent();

        $table->addEvent()->setNormalBtn('详情', 'read')
            ->setJs(EventHandle::openPage([url('detail'), 'id'], '请求详情')->popUps());

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
            FormUnit::group(
                FormUnit::text('route.title%%')->placeholder('节点名'),
                FormUnit::select('i.method')->placeholder("请求方式")->options(LogEnumMethod::getMap()),
                FormUnit::text('i.route%%')->placeholder('节点地址'),
                FormUnit::text('administrators.name%%')->placeholder('操作人员'),
                FormUnit::time('i.create_time_~')->placeholder('创建时间'),
            )
        ];

        return DefaultForm::create($form_data)->setSearchSubmitElement();
    }

    /**
     * 日志详情
     * @param $id
     * @return string
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/11/27
     */
    public function detail($id): string
    {
        $info = LogModel::alias('i')
            ->join('route', 'i.route_id = route.id ', 'left')
            ->join('administrators', 'i.administrators_id = administrators.id ', 'left')
            ->field('i.id,i.method,route.title route_title,route.id route_id,administrators.name administrators_name,i.param,i.route,i.create_time')
            ->where('i.id', $id)->findOrEmpty()->toArray();

        if (!empty($info['param'])){
            $info['param'] = var_export(json_decode($info['param'], true), true);
        }

        $page = new  Page('请求日志详情');

        $table = Table::create('明细')->data($info)
        ->field([
            ['method' => '请求类型', 'route' => '请求地址'],
            ['route_title' => '地址映射', 'administrators_name' => '操作人员'],
            ['create_time(3)' => '请求时间',],
            ['param(3)' => '详细参数',],
        ])->fieldAttr([
            '-' => 'style="width:100px;background:#fafafa;text-align:center;"',
            ])->customField([
                'param' => " <textarea name=\"s\" id=\"codes\"><?php\n\n {$info["param"]}</textarea>"
            ])
            ->complete();

        return $page->addTable($table)
            ->setLoadCss([
                'admin_static/codeMirror/codemirror.css',
                'admin_static/codeMirror/darcula.css',
            ])->addLoadJs([
                'admin_static/codeMirror/codemirror.js',
                'admin_static/codeMirror/php.js',
                'admin_static/codeMirror/htmlmixed.js',
                'admin_static/codeMirror/xml.js',
                'admin_static/codeMirror/css.js',
                'admin_static/codeMirror/clike.js',
                'admin_static/codeMirror/javascript.js',
            ])
            ->customJs(<<<JS

    var editor = CodeMirror.fromTextArea(document.getElementById('codes'), {
        lineNumbers: true,
        theme: 'darcula',
        matchBrackets: true,
        mode: "application/x-httpd-php",
        indentUnit: 4,
        indentWithTabs: true,
    });

JS)->render();
    }
}
