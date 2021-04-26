<?php
/**
 * Date: 2020/11/25 14:44
 * User: chenlong <vip_chenlong@163.com>
 */

namespace app\admin\page\system;


use app\common\BasePage;
use sdModule\layui\form\Form as DefaultForm;
use sdModule\layui\TablePage;

class Route extends BasePage
{
    public string $list_template = 'lists';

    /**
     * 获取创建列表table的数据
     * @return TablePage
     * @throws \app\common\SdException
     */
    public function getTablePageData(): TablePage
    {
        return TablePage::create([]);
    }

    /**
     * 生成表单的数据
     * @param string $scene
     * @param array $default_data
     * @return DefaultForm
     */
    public function formData(string $scene, array $default_data = []): DefaultForm
    {
        return DefaultForm::create([]);
    }

    /**
     * 列表页面的名字
     * @return string
     */
    public function listPageName(): string
    {
        return $this->lang("route_m");
    }
}
