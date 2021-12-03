<?php
/**
 * QuickOperation.php
 * User: ChenLong
 * DateTime: 2021-12-03 21:05:04
 */

namespace app\admin\controller\system;

use app\admin\service\system\QuickOperationService;
use app\common\controller\Admin;
use app\common\ResponseJson;
use app\common\SdException;
use app\admin\service\system\QuickOperationService as MyService;
use app\admin\model\system\QuickOperation as MyModel;
use app\admin\page\system\QuickOperationPage as MyPage;

/**
 * 快捷操作 控制器
 * Class QuickOperation
 * @package app\admin\controller\system\QuickOperation
 * @author chenlong <vip_chenlong@163.com>
 */
class QuickOperation extends Admin
{

    /**
     * @title("快捷操作列表")
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
     * @title("快捷操作状态更新")
     * @param MyService $service
     * @param MyModel $model
     * @return \think\response\Json
     * @throws SdException
     */
    public function switchHandle(MyService $service, MyModel $model): \think\response\Json
    {
        $service->setIndexShow($this->request->post('id'));

        return ResponseJson::success();
    }

    /**
     * @title('更新首页节点坐标')
     * @param QuickOperationService $service
     * @return \think\response\Json
     * @throws SdException
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/12/3
     */
    public function indexCoordinateUpdate(QuickOperationService $service): \think\response\Json
    {
        $service->indexCoordinateUpdate($this->request->post('data'));

        return ResponseJson::success();
    }
}
