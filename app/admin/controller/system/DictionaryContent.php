<?php
/**
 * DictionaryContent.php
 * User: ChenLong
 * DateTime: 2021-11-24 23:26:33
 */

namespace app\admin\controller\system;

use app\common\controller\Admin;
use app\common\ResponseJson;
use app\common\SdException;
use app\admin\service\system\DictionaryContentService as MyService;
use app\admin\model\system\DictionaryContent as MyModel;
use app\admin\page\system\DictionaryContentPage as MyPage;
use app\admin\validate\system\DictionaryContent as MyValidate;

/**
 * 字典内容 控制器
 * Class DictionaryContent
 * @package app\admin\controller\system\DictionaryContent
 * @author chenlong <vip_chenlong@163.com>
 */
class DictionaryContent extends Admin
{

    /**
     * @title("列表数据")
     * @param MyService $service
     * @param MyModel $model
     * @param MyPage $page
     * @return \think\response\Json|\think\response\View
     * @throws SdException
     * @throws \ReflectionException
     */
    public function index(MyService $service, MyModel $model, MyPage $page)
    {
        return parent::index_($service, $model, $page);
    }
    
            
    /**
     * @title("数据创建")
     * @param MyService $service
     * @param MyModel $model
     * @param MyPage $page
     * @return \think\response\Json|\think\response\View
     * @throws SdException
     * @throws \ReflectionException
     */
    public function create(MyService $service, MyModel $model, MyPage $page)
    {
        if ($this->request->isPost()) {
            $data = $service->fieldFilter($this->request->post());
            $this->validate($data, MyValidate::class . ".create");

            $service->dataSave($data, $model);

            return ResponseJson::success();
        }

        return parent::create_($service, $model, $page, MyValidate::class);
    }

            
    /**
     * @title("数据更新")
     * @param MyService $service
     * @param MyModel $model
     * @param MyPage $page
     * @return \think\response\Json|\think\response\View
     * @throws SdException
     * @throws \ReflectionException
     */
    public function update(MyService $service, MyModel $model, MyPage $page)
    {
        if ($this->request->isPost()) {
            $data = $service->fieldFilter($this->request->post());
            $this->validate($data, MyValidate::class . ".update");

            $service->dataSave($data, $model);

            return ResponseJson::success();
        }

        return parent::update_($service, $model, $page, MyValidate::class);
    }

            
    /**
     * @title("数据删除")
     * @param MyService $service
     * @param MyModel $model
     * @return \think\response\Json
     * @throws SdException
     */
    public function delete(MyService $service, MyModel $model): \think\response\Json
    {
        return parent::delete_($service, $model);
    }

}
