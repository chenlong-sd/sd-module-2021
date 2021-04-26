<?php
/**
 * Date: 2020/11/25 12:36
 * User: chenlong <vip_chenlong@163.com>
 */

namespace app\common;

use app\common\traits\Lang;
use sdModule\layui\form\Form as DefaultForm;
use sdModule\layui\TablePage;

/**
 * Class BasePage
 * @package app\common
 */
abstract class BasePage
{
    use Lang;

    /**
     * @var string 列表数据页面模板
     */
    public string $list_template = 'common/list_page';

    /**
     * @var string 表单页面模板
     */
    public string $form_template = 'common/save_page';


    /**
     * 获取创建列表table的数据
     * @return TablePage
     */
    abstract public function getTablePageData():TablePage;

    /**
     * 生成表单的数据
     * @param string $scene 场景值
     * @param array $default_data 默认值
     * @return DefaultForm
     */
    abstract public function formData(string $scene, array $default_data = []): DefaultForm;

    /**
     * 列表页面的名字
     * @return string
     */
    abstract public function listPageName():string;

    /**
     * 创建搜索表单的数据
     * @return DefaultForm
     * @throws SdException
     * @throws \ReflectionException
     */
    public function searchFormData(): DefaultForm
    {
        return DefaultForm::create([])->setNoSubmit()->complete();
    }

}
