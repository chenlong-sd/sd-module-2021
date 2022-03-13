<?php
/**
 * NewDictionary.php
 * Date: 2021-11-24 23:14:45
 */

namespace app\admin\page\system;

use app\common\BasePage;
use sdModule\layui\lists\module\Column;
use sdModule\layui\lists\module\EventHandle;
use sdModule\layui\lists\PageData;
use sdModule\layui\form4\FormProxy as Form;
use sdModule\layui\form4\FormUnit;
use app\admin\enum\NewDictionaryEnumType;


/**
 * 新字典表
 * Class NewDictionaryPage
 * @package app\admin\page\system\NewDictionaryPage
 */
class NewDictionaryPage extends BasePage
{
    /**
     * 获取创建列表table的数据
     * @return PageData
     * @throws \app\common\SdException
     */
    public function listPageData(): PageData
    {
        $table = PageData::create([
            Column::checkbox(),
            Column::normal('类型', 'type'),
            Column::normal('字典标识ID', 'sign'),
            Column::normal('字典名称', 'name'),
            Column::normal('图片', 'image')->showImage(),
            Column::normal('简介', 'introduce'),
            Column::normal('修改时间', 'update_time'),
        ]);

        $table->addEvent()->setNormalBtn('字典内容', 'set')
            ->setJs(EventHandle::openPage([url('system.DictionaryContent/index'), 'id'], '「{name}」字典内容')->popUps());

        return $table;
    }

    /**
     * 生成表单的数据
     * @param string $scene
     * @param array $default_data
     * @return Form
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/11/26
     */
    public function formPageData(string $scene, array $default_data = []): Form
    {
        empty($default_data['customize']) or $default_data['customize'] = json_decode($default_data['customize'], true);

        $unit = [
            FormUnit::hidden('id'),
            FormUnit::group(
                FormUnit::text('sign', '字典标识ID')->placeholder('用于取值，例：status'),
                FormUnit::text('name', '字典名称'),
            ),
            FormUnit::radio('type', '类型')->defaultValue(1)->options(NewDictionaryEnumType::getMap(true)),
            FormUnit::image('image', '图片')->showWhere('type', 2),
            FormUnit::textarea('introduce', '简介')->showWhere('type', 2),
            FormUnit::auxTitle('定制字段')->showWhere('type', 2),
            FormUnit::table('customize')->addChildrenItem(
                FormUnit::text('d_key', '字段'),
                FormUnit::text('d_title', '名称'),
                FormUnit::select('d_type', '表单类型')->options([
                    'text'      => '文本',
                    'checkbox'  => '多选',
                    'radio'     => '单选',
                    'textarea'  => '文本域',
                    'color'     => '颜色',
                    'uEditor'   => '百度富文本',
                    'select'    => '下拉',
                    'tag'       => '标签',
                    'time'      => '时间选择',
                    'image'     => '单图上传',
                    'images'    => '多图上传',
                    'video'     => '视频上传',
                ]),
                FormUnit::checkbox('d_search', '各允许一个')->options(['1' => '索引', '2' => '排序']),
                FormUnit::text('d_options', '可选项（可不填）')->placeholder('示例：1=正常,2=禁用,.....'),
            )->showWhere('type', 2)
        ];

        $form = Form::create($unit, $default_data)->setScene($scene);

        return $form;
    }




}
