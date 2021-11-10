<?php
/**
 * Date: 2020/11/25 12:36
 * User: chenlong <vip_chenlong@163.com>
 */

namespace app\common;

use app\common\traits\Lang;
use sdModule\layui\form\Form;
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
     * @return array
     * @throws SdException
     * @throws \ReflectionException
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/11/6
     */
    public function listPageData(): array
    {
        return [
            'table'  => PageData::create([]),
            'search' => Form::create([])->setSubmitHtml()->complete(),
        ];
    }

    /**
     * 生成表单的数据
     * @param string $scene 场景值
     * @param array $default_data 默认值
     * @return Form
     * @throws \ReflectionException
     */
    public function formPageData(string $scene, array $default_data = []): Form
    {
        return Form::create([])->complete();
    }

}
