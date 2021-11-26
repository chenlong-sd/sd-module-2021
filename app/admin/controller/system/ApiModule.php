<?php
/**
 * ApiModule.php
 * User: ChenLong
 * DateTime: 2020-12-11 11:08:36
 */

namespace app\admin\controller\system;

use app\admin\AdminBaseService;
use app\admin\model\system\ApiModule as ApiModuleModel;
use app\admin\page\system\ApiModulePage as ApiModulePage;
use app\admin\service\system\ApiService;
use app\admin\validate\system\ApiModule as ApiModuleValidate;
use \app\common\controller\Admin;
use app\common\service\BackstageListsService;


/**
 * Class ApiModule
 * @package app\admin\controller\ApiModule
 * @author chenlong <vip_chenlong@163.com>
 */
class ApiModule extends Admin
{
    /**
     * @title('api模块列表')
     * @param ApiService $service
     * @param ApiModulePage $page
     * @return \think\response\Json|\think\response\View
     * @throws \ReflectionException
     * @throws \app\common\SdException
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/11/9
     */
    public function index(ApiService $service, ApiModuleModel $mode, ApiModulePage $page)
    {
        if ($this->request->isAjax()) {
            return $service->apiModuleListData(new BackstageListsService());
        }

        return parent::index_($service, $mode, $page);
    }

    /**
     * @title('新增api模块')
     * @param ApiService $service
     * @param ApiModuleModel $model
     * @param ApiModulePage $page
     * @return \think\response\Json|\think\response\View
     * @throws \ReflectionException
     * @throws \app\common\SdException
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/11/9
     */
    public function create(AdminBaseService $service, ApiModuleModel $model, ApiModulePage $page)
    {
        return parent::create_($service, $model, $page, ApiModuleValidate::class);
    }

    /**
     * @title('修改api模块')
     * @param AdminBaseService $service
     * @param ApiModuleModel $model
     * @param ApiModulePage $page
     * @return \think\response\Json|\think\response\View
     * @throws \ReflectionException
     * @throws \app\common\SdException
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/11/9
     */
    public function update(AdminBaseService $service, ApiModuleModel $model, ApiModulePage $page)
    {
        return parent::update_($service, $model, $page, ApiModuleValidate::class);
    }

    /**
     * @title('删除api模块')
     * @param AdminBaseService $service
     * @param ApiModuleModel $model
     * @return \think\response\Json
     * @throws \app\common\SdException
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/11/9
     */
    public function delete(AdminBaseService $service, ApiModuleModel $model): \think\response\Json
    {
        return parent::delete_($service, $model);
    }
}
