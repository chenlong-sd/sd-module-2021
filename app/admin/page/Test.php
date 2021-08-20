<?php
/**
 * Test.php
 * Date: 2021-06-17 16:27:13
 * User: chenlong <vip_chenlong@163.com>
 */

namespace app\admin\page;

use app\common\BasePage;
use sdModule\layui\tablePage\ListsPage;
use sdModule\layui\tablePage\module\TableAux;
use sdModule\layui\form\Form;
use sdModule\layui\form\FormUnit;
use app\admin\model\Test as MyModel;
use app\admin\model\system\Administrators;


/**
 * Class Test
 * @package app\admin\page\Test
 */
class Test extends BasePage
{
    /**
     * 获取创建列表table的数据
     * @return ListsPage
     */
    public function getTablePageData(): ListsPage
    {
        $table = ListsPage::create([
            TableAux::column()->checkbox(),
            TableAux::column('id', 'ID'),
            TableAux::column('title', '标题'),
            TableAux::column('cover', '封面')->image(),
            TableAux::column('intro', '简介'),
            TableAux::column('status', '状态'),
            TableAux::column('administrators_name', '管理员'),
            TableAux::column('parent_title', '上级'),
            TableAux::column('create_time', '创建时间'),
            TableAux::column('update_time', '修改时间'),
            TableAux::column('delete_time', '删除时间'),
        ]);

        $table->setHandleAttr([
            'align' => 'center',
            'width' => 150
        ]);
        $table->setEventMode($table::MENU_MODE);
        $table->addBarEvent()->setWarmBtn('dsd')->setJs(TableAux::batchAjax(url('test'),  'post'));



        return $table;
    }

    /**
    * 生成表单的数据
    * @param string $scene
    * @param array $default_data
    * @return Form
    * @throws \ReflectionException
    * @throws \app\common\SdException
    */
    public function formData(string $scene, array $default_data = []): Form
    {
        $unit = [
            FormUnit::hidden('id'),
            FormUnit::text('title', '标题')->required(),
            FormUnit::image('cover', '封面')->required(['edit']),
            FormUnit::images('show_images', '展示图'),
            FormUnit::radio('status', '状态')->options(MyModel::getStatusSc(false)),
            FormUnit::build(
                FormUnit::text('intro', '简介'),
                FormUnit::select('administrators_id', '管理员')->inputAttr(['-' => ['lay-filter' => 'ss']])->options(Administrators::column('name', 'id')),
            )->setShowWhere('status', 1),
            FormUnit::select('pid', '上级')->options(MyModel::column('title', 'id')),
            FormUnit::uEditor('content', '详情'),
        ];
        $form = Form::create($unit, $scene)->setDefaultData($default_data);

        if ($scene == 'add') {
            $form->setSubmitHtml();
        }

        $data = [
             1 => [
                'id' => 1,
                'name' => 'yao',
                'children' => [
                    2 => [
                        'id' => 2,
                        'name' => 'youyao'
                    ]
                ]
            ]
        ];
        $data = json_encode($data);
        $form->setJs(<<<JS
        let uvd = {$data};
        layui.form.on('select(ss)', function (obj){
             console.log(obj.value);
             layui.jquery('select[name=pid]').html(optionHtmlMake(uvd[obj.value].children));
             layui.form.render();
        });

        function optionHtmlMake(obj){
            console.log(obj)
            let html = '<option value=""></option>';
            for(var i = 0; i < obj.length; i++) {
                html += `<option value="\${obj[i].id}">\${obj[i].name}</option>`;
            }
            return html;
        }
JS);



        return $form->complete();
    }


    /**
     * 创建搜索表单的数据
     * @return Form
     * @throws \ReflectionException
     * @throws \app\common\SdException
     */
    public function searchFormData(): Form
    {
        $form_data = [];
        return Form::create($form_data)->setSubmitHtml()->complete();
    }

}
