<?php
/**
 * datetime: 2021/11/9 9:20
 * user    : chenlong<vip_chenlong@163.com>
 **/

namespace app\admin\service\system;

use app\admin\AdminBaseService;
use app\admin\model\system\Power;
use app\admin\model\system\Route;
use app\common\service\BackstageListsService;
use sdModule\common\Sc;

class RouteService extends AdminBaseService
{
    /**
     * @param BackstageListsService $service
     * @return \think\response\Json
     * @throws \app\common\SdException
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/11/9
     */
    public function listData(BackstageListsService $service): \think\response\Json
    {
        $model = Route::join('route', 'i.pid = route.id', 'left')
            ->field('i.id,i.title,i.route,i.pid,route.title parent,i.type,i.weigh,i.icon,i.create_time');

        return $service->setModel($model)->getListsData();
    }

    /**
     * 删除路由前的处理
     * @param $ids
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/11/9
     */
    protected function beforeDelete(&$ids)
    {
        $all = Route::field('id,pid')->select()->toArray();

        // 查找该路由的所有子权限
        $delArr = Sc::infinite($all)->handle(current($ids), true);
        $ids    = array_column($delArr, 'id');

        // 删除对应路由的权限
        Power::where(['route_id' => $ids])->update(['delete_time' => time()]);
    }

    /**
     * 存储数据之前处理反斜杠
     * @param array $data
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/11/9
     */
    protected function beforeWrite(array &$data)
    {
        if (!empty($data['route'])) {
            $data['route'] = strtr($data['route'], ['\\' => '/']);
        }
    }
}

