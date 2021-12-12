<?php
/**
 * DictionaryContent.php
 * Date: 2021-11-24 23:26:33
 */

namespace app\admin\page\system;

use app\admin\enum\NewDictionaryEnumType;
use app\admin\model\system\NewDictionary;
use app\common\BasePage;
use sdModule\layui\lists\module\Column;
use sdModule\layui\lists\module\EventHandle;
use sdModule\layui\lists\PageData;
use sdModule\layui\form4\FormProxy as Form;
use sdModule\layui\form4\FormUnit;


/**
 * 字典内容
 * Class DictionaryContentPage
 * @package app\admin\page\system\DictionaryContentPage
 */
class DictionaryContentPage extends BasePage
{
    /**
     * 获取创建列表table的数据
     * @return PageData
     * @throws \app\common\SdException
     */
    public function listPageData(): PageData
    {
        $column = [
            Column::checkbox(),
            Column::normal('KEY', 'value'),
            Column::normal('标题', 'name'),
            Column::normal('更新时间', 'update_time'),
        ];
        $dictionary_id = request()->get('id');
        $Dictionary    = NewDictionary::findOrEmpty($dictionary_id);
        // 有自定义字段
        if ($Dictionary->getData('type') == NewDictionaryEnumType::STRONG && $Dictionary->customize) {
            $column = [
                Column::checkbox(),
            ];
            $isSort = false; // 是否有排序字段了
            foreach (json_decode($Dictionary->customize, true) as $value){
                if (in_array($value['d_type'], ['images', 'uEditor', 'video'])) {
                    continue;
                }
                if (!empty($value['d_search']) && in_array(2, $value['d_search']) && !$isSort) {
                    $isSort = true;
                    $currentColumn = Column::normal($value['d_title'], 'sort')->addSort();
                }else{
                    $currentColumn = Column::normal($value['d_title'], $value['d_key']);
                }
                if ($value['d_type']  === 'image') $currentColumn->showImage();
                $column[] = $currentColumn;
            }
            $column[] = Column::normal('更新时间', 'update_time');
        }
        $table = PageData::create($column);

        $table->setConfig([
            'where' => [
                'search' => [
                    'new_dictionary_id' => $dictionary_id,
                ]
            ]
        ]);

        // 重新定义新增
        $table->addBarEvent('create')->setDefaultBtn('新增', 'add-1', 'sm')
            ->setJs(EventHandle::openPage(url('create?id=' . $dictionary_id), '新增')->popUps());


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
        $dictionary_id = $scene === 'create' ? request()->get('id') : $default_data['new_dictionary_id'];
        $Dictionary    = NewDictionary::findOrEmpty($dictionary_id);

        $unit = [
            FormUnit::hidden('id'),
            FormUnit::hidden('new_dictionary_id')->defaultValue($dictionary_id),
        ];
        if ($Dictionary->getData('type') == NewDictionaryEnumType::STRONG && $Dictionary->customize) {
            // 循环判定每一个表单
            foreach (json_decode($Dictionary->customize, true) as $value){
                $currentForm = call_user_func(FormUnit::class . "::{$value['d_type']}", $value['d_key'], $value['d_title']);
                if (!empty($value['d_options'])){
                    $options = array_map(function ($v) {
                        return explode('=', $v);
                    }, explode(',', strtr($value['d_options'], ['，' => ','])));
                    $currentForm->options(array_column($options, 1, 0));
                }
                $unit[] = $currentForm;
            }
        }else{
            $unit[] = FormUnit::text('value', 'KEY');
            $unit[] = FormUnit::text('name', '标题');
        }
        $default_data = $default_data ? array_merge($default_data, json_decode($default_data['dictionary_content'], true)) : [];

        $form = Form::create($unit, $default_data)->setScene($scene);

        return $form;
    }

    /**
     * @return Form
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/11/30
     */
    public function listSearchFormData(): Form
    {
        $dictionary_id = request()->get('id');
        $Dictionary    = NewDictionary::findOrEmpty($dictionary_id);
        // 有自定义字段
        if ($Dictionary->getData('type') == NewDictionaryEnumType::STRONG && $Dictionary->customize) {
            foreach (json_decode($Dictionary->customize, true) as $value){
                if (!empty($value['d_search']) && in_array(1, $value['d_search'])){
                    return Form::create([
                        FormUnit::group(
                            FormUnit::text('search%%')->placeholder($value['d_title'])
                        )
                    ])->setSearchSubmitElement();
                }
            }
        }

        return parent::listSearchFormData();
    }


}
