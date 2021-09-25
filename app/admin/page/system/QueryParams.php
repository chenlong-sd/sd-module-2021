<?php
/**
 * QueryParams.php
 * Date: 2020-12-11 11:10:24
 * User: chenlong <vip_chenlong@163.com>
 */

namespace app\admin\page\system;

use app\common\BasePage;
use sdModule\layui\form\Form as DefaultForm;
use sdModule\layui\lists\PageData;
use sdModule\layui\tablePage\module\TableAux;

/**
 * Class QueryParams
 * @package app\admin\page
 */
class QueryParams extends BasePage
{
    /**
     * 获取创建列表table的数据
     * @return PageData
     */
    public function getTablePageData(): PageData
    {
        $table = PageData::create([
            TableAux::column()->checkbox(),
            TableAux::column('id', ''),
            TableAux::column('method', '请求参数类型'),
            TableAux::column('param_type', '参数类型'),
            TableAux::column('name', '参数名'),
            TableAux::column('test_value', '测试值'),
            TableAux::column('describe', '描述'),
            TableAux::column('update_time', '修改时间'),
            TableAux::column('delete_time', '删除时间'),
        ]);

        return $table;
    }

    /**
     * 生成表单的数据
     * @param string $scene
     * @param array $default_data
     * @return DefaultForm
     * @throws \ReflectionException
     * @throws \app\common\SdException
     */
    public function formData(string $scene, array $default_data = []): DefaultForm
    {
        $unit = [];

        $form = DefaultForm::create($unit);

        return $form->complete();
    }


}
