<?php
/**
* QuickOperation.php
* DateTime: 2021-12-03 21:05:04
*/

namespace app\admin\service\system;

use app\admin\AdminBaseService;
use app\admin\enum\QuickOperationEnumIsShow;
use app\admin\AdminLoginSession;
use app\admin\model\system\QuickOperation;
use app\admin\model\system\Route;
use app\common\service\BackstageListsService;
use app\common\SdException;
use think\facade\Db;

/**
* 快捷操作 服务层
* Class QuickOperationService
* @package app\admin\service\system\QuickOperationService
*/
class QuickOperationService extends AdminBaseService
{
    /**
     * 列表数据
     * @param BackstageListsService $service
     * @return \think\response\Json
     * @throws \app\common\SdException
     */
    public function listData(BackstageListsService $service): \think\response\Json
    {
        $model = Route::alias('route')->order('route.weigh')
            ->join('quick_operation i', 'i.route_id = route.id and i.administrators_id = ' . AdminLoginSession::getId() . ' and i.open_table = "' . AdminLoginSession::getTable('') . '"', 'left')
            ->field('route.id,route.pid,route.title route_title,i.is_show is_show_true,route.route');

        if (!AdministratorsService::isSuper()) {
            $model->join('power p', 'p.route_id = route.id')
                ->where('p.role_id', AdminLoginSession::getRoleId());
        }

        return $service->setModel($model)->getListsData();
    }

    /**
     * 设置首页展示
     * @param int $id
     * @throws SdException
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/12/3
     */
    public function setIndexShow(int $id)
    {
        try {
            $quick = QuickOperation::where([
                'route_id'          => $id,
                'administrators_id' => AdminLoginSession::getId(),
                'open_table'        => AdminLoginSession::getTable('')
            ])->findOrEmpty();
            if ($quick->isEmpty()) {
                $quick->route_id          = $id;
                $quick->administrators_id = AdminLoginSession::getId();
                $quick->open_table        = AdminLoginSession::getTable('');
                $quick->create_time       = datetime();
                $quick->is_show           = QuickOperationEnumIsShow::NOT;
            }

            $quick->is_show     = $quick->getData('is_show') == QuickOperationEnumIsShow::NOT ? QuickOperationEnumIsShow::YES : QuickOperationEnumIsShow::NOT;
            $quick->update_time = datetime();
            $quick->coordinate  = 0;
            $quick->save();

        } catch (\Exception $exception) {
            throw new SdException($exception->getMessage());
        }
    }

    /**
     * 更新首页节点的坐标
     * @param array $data
     * @throws SdException
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/12/3
     */
    public function indexCoordinateUpdate(array $data)
    {
        Db::startTrans();
        try {
            foreach ($data as $datum) {
                if (empty($datum['id'])) {
                    continue;
                }

                $route = QuickOperation::findOrEmpty($datum['id']);
                $route->coordinate = $datum['coordinate'] ?? 0;
                $route->update_time = datetime();
                $route->save();
            }

            Db::commit();
        } catch (\Exception $exception) {
            Db::rollback();
            throw new SdException($exception->getMessage());
        }
    }


    /**
     * 获取首页展示的节点
     * @return array
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/12/3
     */
    public function indexShowNode(): array
    {
        try {
            $node = QuickOperation::alias('i')
                ->where([
                    'i.is_show'           => QuickOperationEnumIsShow::YES,
                    'i.administrators_id' => AdminLoginSession::getId(),
                    'i.open_table'        => AdminLoginSession::getTable('')
                ])
                ->join('route r', 'r.id = i.route_id')
                ->field('i.id,i.coordinate,r.title,r.route,r.icon');
            // 不是超管
            if (!AdministratorsService::isSuper()) {
                $node = $node->join('power p', 'p.route_id = i.route_id')
                    ->where('p.role_id', 'in', explode(',', AdminLoginSession::getRoleId()));
            }

            $node = $node->select()->toArray();

            $haveCoordinate = array_column(array_filter($node, function ($v) {
                return $v['coordinate'] > 0 && $v['route'];
            }), null, 'coordinate');

            $notCoordinate = array_values(array_filter($node, function ($v) {
                return $v['coordinate'] == 0 && $v['route'];
            }));

            return compact('haveCoordinate', 'notCoordinate');
        } catch (\Exception $exception) {
            return ['haveCoordinate' => [], 'notCoordinate' => []];
        }
    }

}
