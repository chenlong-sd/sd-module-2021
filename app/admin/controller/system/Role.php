<?php
/**
 *
 * Role.php
 * User: ChenLong
 * DateTime: 2020-04-12 22:22
 */

namespace app\admin\controller\system;

use app\admin\model\system\Role as RoleModel;
use app\common\BaseQuery;
use app\common\service\BackstageListsService;
use sdModule\common\Sc;

/**
 * Class Role
 * @package app\admin\controller\system
 * @author chenlong <vip_chenlong@163.com>
 */
class Role extends \app\common\controller\Admin
{

    /**
     * @return array|\Closure|mixed|string|\think\Collection|\think\response\Json
     * @throws \app\common\SdException
     */
    public function listData(BackstageListsService $service)
    {
        $model = RoleModel::join('administrators', 'i.administrators_id = administrators.id', 'left')
            ->join('role ip', 'i.pid = ip.id', 'left')
            ->field('i.id,i.id role_id,i.role,i.pid,administrators.name administrators_id,i.create_time,ip.role parent_role');

        return $service->setModel($model)->setCustomSearch([$this, 'listSearchParamHandle'])->getListsData();
    }

    /**
     * @param array $data
     * @return false|void
     * @author chenlong <vip_chenlong@163.com>
     * @date 2021/6/10
     */
    protected function beforeWrite(array &$data)
    {
        if (!empty($data['id'])) return false;

        if (admin_session('is_admin')) {
            $data['administrators_id'] = admin_session('id');
        }else{
            $data['open_table'] = admin_session('table');
            $data['open_id']    = admin_session('id');
        }
        $data['pid'] = admin_session('role_id', 0);
    }


    protected function afterWrite($id, $data)
    {
        if (env('APP.DATA_AUTH')){
            RoleModel::dataAuthSet($id, $this->request->post());
        }
    }

    /**
     * @param array $search
     * @param BaseQuery $model
     * @return string[]
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/6/23
     */
    public function listSearchParamHandle(array $search, BaseQuery $model): array
    {
        if (isset($search['mode']) && $search['mode'] === 'all'){
            if (admin_session('is_admin')) {
                $all_role = RoleModel::field('id,pid,role,administrators_id')->select()->toArray();
            }else{
                $all_role = RoleModel::field('id,pid,role,open_id administrators_id')
                    ->where('open_table', admin_session('table'))->select()->toArray();
            }
            $mySubordinate = Sc::infinite($all_role)->handle(['administrators_id' => admin_session('id')], true);

            $model->whereIn('i.id', array_column($mySubordinate, 'id'));
        }else{
            if (admin_session('is_admin')) {
                $model->where('i.administrators_id', admin_session('id'));
            } else {
                $model->where([
                    'i.open_table' => admin_session('table'),
                    'i.open_id'    => admin_session('id')
                ]);
            }
        }
        return ['mode'];
    }
}
