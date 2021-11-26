<?php
/**
 * 
 * Route.php
 * User: ChenLong
 * DateTime: 2020-04-12 23:07
 */

namespace app\admin\controller\system;

use app\admin\model\system\Route as RouteModel;
use app\admin\page\system\RoutePage as RoutePage;
use app\admin\service\system\RouteService;
use app\admin\validate\system\Route as RouteValidate;
use app\common\controller\Admin;
use app\common\ResponseJson;
use app\common\SdException;
use sdModule\common\Sc;
use think\facade\View;

/**
 * Class Route
 * @package app\admin\controller\system
 * @author chenlong <vip_chenlong@163.com>
 */
class Route extends Admin
{
    /**
     * @title('路由列表')
     * @param RouteService $service
     * @param RouteModel $model
     * @param RoutePage $page
     * @return \think\response\Json|\think\response\View
     * @throws SdException
     * @throws \ReflectionException
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/11/9
     */
    public function index(RouteService $service, RouteModel $model, RoutePage $page)
    {
        return parent::index_($service, $model, $page);
    }

    /**
     * @title('删除路由')
     * @param RouteService $service
     * @param RouteModel $model
     * @return \think\response\Json
     * @throws SdException
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/11/9
     */
    public function delete(RouteService $service, RouteModel $model): \think\response\Json
    {
        return parent::delete_($service, $model);
    }

    /**
     * @title('创建路由')
     * @param RouteService $service
     * @param RouteModel $model
     * @param RoutePage $page
     * @return \think\response\Json|\think\response\View
     * @throws SdException
     * @throws \ReflectionException
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/11/9
     */
    public function create(RouteService $service, RouteModel $model, RoutePage $page)
    {
        if (!$this->request->isAjax()) {
            $page->form_template = 'add';
            View::assign('type_data', RouteModel::getType());
        }

        return parent::create_($service, $model, $page, RouteValidate::class);
    }

    /**
     * @title('更新路由')
     * @param RouteService $service
     * @param RouteModel $model
     * @param RoutePage $page
     * @return \think\response\Json|\think\response\View
     * @throws SdException
     * @throws \ReflectionException
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/11/9
     */
    public function update(RouteService $service, RouteModel $model, RoutePage $page)
    {
        if (!$this->request->isAjax()) {
            $page->form_template = 'edit';
            View::assign('type_data', RouteModel::getType());
            View::assign('data', $model->findOrEmpty($this->request->get('id'))->getData());
        }

        return parent::update_($service, $model, $page, RouteValidate::class);
    }

    /**
     * @title("获取节点")
     * @param RouteModel $route
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getNode(RouteModel $route): \think\response\Json
    {
        return ResponseJson::mixin($route->getNode());
    }

    /**
     * @title('自动检测地址')
     * @param RouteService $service
     * @param RouteModel $route
     * @return \think\response\Json|\think\response\View
     * @throws SdException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/11/12
     */
    public function automaticDetection(RouteService $service, RouteModel $route)
    {
        if ($this->request->isAjax()) {

            $service->saveAutomaticDetectionRoute($this->request->post('new_route', []), $this->request->post('parent', []), $this->request->post('controller', []));

            return ResponseJson::success();
        }

        $accessible = $service->automaticDetection();
        $parentNode = $route->field('id value,title name,pid')->select()->toArray();
        $parentNode = Sc::infinite($parentNode)->setPrimaryKye('value')->setSeries(2, 'del')->handle();

        return \view('', compact('accessible', 'parentNode'));
    }
}