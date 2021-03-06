<?php
/**
 * 
 * Route.php
 * User: ChenLong
 * DateTime: 2020-04-12 23:07
 */

namespace app\admin\controller\system;

use app\common\ResponseJson;
use app\common\SdException;
use app\common\service\BackstageListsService;

/**
 * Class Route
 * @package app\admin\controller\system
 * @author chenlong <vip_chenlong@163.com>
 */
class Route extends \app\common\controller\Admin
{

    /**
     * @param BackstageListsService $service
     * @return array|\Closure|mixed|string|\think\Collection|\think\response\Json
     * @throws SdException
     */
    public function listData(BackstageListsService $service)
    {
        $model = $this->getModel()->join('route', 'i.pid = route.id', 'left')
            ->field('i.id,i.title,i.route,i.pid,route.title parent,i.type,i.weigh,i.icon,i.create_time');

        return $service->setModel($model)->getListsData();
    }
    
   
    /**
     * @return array|mixed
     */
    public function add()
    {
        return view(__FUNCTION__, [
            'type_data' => \app\admin\model\system\Route::getType(),
        ]);
    }


    /**
     * @param int $id
     * @return array|\think\response\View
     * @throws SdException
     */
    public function edit($id = 0)
    {
        return view(__FUNCTION__, [
            'type_data' => \app\admin\model\system\Route::getType(),
            'data' => $this->getModel()->find($id)->getData(),
        ]);
    }

    public function beforeWrite(&$data)
    {
        if (!empty($data['route'])) {
            $data['route'] = strtr($data['route'], ['\\' => '/']);
        }
    }

    /**
     * 获取节点
     * @param \app\admin\model\system\Route $route
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getNode(\app\admin\model\system\Route $route)
    {
        return ResponseJson::mixin($route->getNode());
    }

    /**
     * @param $id
     * @return void
     * @throws \Throwable
     */
    public function delete($id)
    {
        (new \app\admin\model\system\Route())->deleteRoute($id);
    }
   
}