<?php
/**
 * Dictionary.php
 * User: ChenLong
 * DateTime: 2021-05-06 21:52:58
 */

namespace app\admin\controller\system;

use app\admin\model\system\Dictionary as DictionaryModel;
use app\admin\page\system\DictionaryPage as DictionaryPage;
use app\admin\service\system\DictionaryService;
use app\admin\validate\system\Dictionary as DictionaryValidate;
use \app\common\controller\Admin;
use app\common\ResponseJson;


/**
 * Class Dictionary
 * @package app\admin\controller\system\Dictionary
 * @author chenlong <vip_chenlong@163.com>
 */
class Dictionary extends Admin
{
    /**
     * @title('所有字典')
     * @param DictionaryService $service
     * @param DictionaryModel $model
     * @param DictionaryPage $page
     * @return \think\response\Json|\think\response\View
     * @throws \ReflectionException
     * @throws \app\common\SdException
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/11/8
     */
    public function index(DictionaryService $service, DictionaryModel $model, DictionaryPage $page)
    {
        return parent::index_($service, $model, $page);
    }

    /**
     * @title("新增字典")
     * @param DictionaryService $service
     * @param DictionaryModel $model
     * @param DictionaryPage $page
     * @return \think\response\Json|\think\response\View
     * @throws \ReflectionException
     * @throws \app\common\SdException
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/11/8
     */
    public function create(DictionaryService $service, DictionaryModel $model, DictionaryPage $page)
    {
        return parent::create_($service, $model, $page, DictionaryValidate::class);
    }

    /**
     * @title('修改字典')
     * @param DictionaryService $service
     * @param DictionaryModel $model
     * @param DictionaryPage $page
     * @return \think\response\Json|\think\response\View
     * @throws \ReflectionException
     * @throws \app\common\SdException
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/11/8
     */
    public function update(DictionaryService $service, DictionaryModel $model, DictionaryPage $page)
    {
        return parent::update_($service, $model, $page, DictionaryValidate::class);
    }


    /**
     * @title('字典删除')
     * @param DictionaryService $service
     * @param DictionaryModel $model
     * @return \think\response\Json
     * @throws \app\common\SdException
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/11/9
     */
    public function delete(DictionaryService $service, DictionaryModel $model): \think\response\Json
    {
        return parent::delete_($service, $model);
    }

    /**
     * @title('字典配置页面')
     * @param DictionaryService $service
     * @param DictionaryPage $page
     * @return \think\response\Json|\think\response\View
     * @throws \ReflectionException
     * @throws \app\common\SdException
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/11/8
     */
    public function dictionary(DictionaryService $service, DictionaryPage $page)
    {
        if ($this->request->isAjax()) {
            return $service->dictionaryConfigListData();
        }

        return view('common/list_page_3_5', [
            'table'     => $page->getDictionaryPageData(),
            'search'    => $page->dictionarySearchFormData(),
            'page_name' => "字典配置",
        ]);
    }

    /**
     * @title('字典值新增')
     * @param DictionaryService $service
     * @param DictionaryPage $page
     * @return \think\response\Json|\think\response\View
     * @throws \ReflectionException
     * @throws \app\common\SdException
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/11/8
     */
    public function dictionaryAdd(DictionaryService $service, DictionaryPage $page)
    {
        if ($this->request->isAjax()) {
            $this->validate($data = data_filter($this->request->post()), DictionaryValidate::class . '.value_add');

            $service->dictionaryValueAdd($data);

            return ResponseJson::success();
        }

        return view('common/save_page', [
            'form' => $page->formPageData('value_add')
        ]);
    }

    /**
     * @title('字典值修改')
     * @param DictionaryService $service
     * @param DictionaryPage $page
     * @param int $id
     * @return \think\response\Json|\think\response\View
     * @throws \ReflectionException
     * @throws \app\common\SdException
     */
    public function dictionaryEdit(DictionaryService $service, DictionaryPage $page, int $id = 0)
    {
        if ($this->request->isAjax()) {
            $this->validate($data = data_filter($this->request->post()), DictionaryValidate::class . '.value_edit');

            $service->dictionaryValueUpdate($data, $id);

            return ResponseJson::success();
        }

        $defaultValue = DictionaryModel::findOrEmpty($id)->getData();

        return view('common/save_page', [
            'form' => $page->formPageData('value_edit', $defaultValue)
        ]);
    }

}
