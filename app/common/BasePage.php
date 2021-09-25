<?php
/**
 * Date: 2020/11/25 12:36
 * User: chenlong <vip_chenlong@163.com>
 */

namespace app\common;

use app\common\traits\Lang;
use sdModule\layui\form\Form as DefaultForm;
use sdModule\layui\lists\PageData;

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
    public $list_template = 'common/list_page_3_5';

    /**
     * @var string 表单页面模板
     */
    public $form_template = 'common/save_page';


    /**
     * 获取创建列表table的数据
     * @return PageData
     */
    abstract public function getTablePageData():PageData;

    /**
     * 生成表单的数据
     * @param string $scene 场景值
     * @param array $default_data 默认值
     * @return DefaultForm
     */
    abstract public function formData(string $scene, array $default_data = []): DefaultForm;

    /**
     * 创建搜索表单的数据
     * @return DefaultForm
     * @throws \ReflectionException
     * @author chenlong <vip_chenlong@163.com>
     * @date 2021/6/2
     */
    public function searchFormData(): DefaultForm
    {
        return DefaultForm::create([])->setSubmitHtml()->complete();
    }

}
