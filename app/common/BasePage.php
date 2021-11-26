<?php
/**
 * Date: 2020/11/25 12:36
 * User: chenlong <vip_chenlong@163.com>
 */

namespace app\common;

use app\common\traits\Lang;
use sdModule\layui\form4\FormProxy as Form;
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
    public $form_template = 'common/save_page_4';


    /**
     * @return PageData
     * @throws SdException
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/11/6
     */
    public function listPageData(): PageData
    {
        return PageData::create([]);
    }

    /**
     * 生成表单的数据
     * @param string $scene 场景值
     * @param array $default_data 默认值
     * @return Form
     */
    public function formPageData(string $scene, array $default_data = []): Form
    {
        return Form::create([]);
    }

    /**
     * 创建列表搜索表单的数据
     * @return Form
     */
    public function  listSearchFormData(): Form
    {
        return Form::create([])->setSearchSubmitElement();
    }

}
