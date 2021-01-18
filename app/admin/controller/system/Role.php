<?php
/**
 *
 * Role.php
 * User: ChenLong
 * DateTime: 2020-04-12 22:22
 */

namespace app\admin\controller\system;

use app\admin\model\system\Role as RoleModel;
use app\common\service\BackstageListService;
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
    public function listData(BackstageListService $service)
    {
        return $service->setModel(RoleModel::class)
            ->setJoin([
                ['administrators', 'i.administrators_id = administrators.id'],
                ['role ip', 'i.pid = ip.id', 'left']
            ])->setField('i.id,i.id role_id,i.role,i.pid,administrators.name administrators_id,i.create_time,ip.role parent_role')
            ->listSearchParamHandle([$this, 'listSearchParamHandle'])
            ->listsRequest();
    }

    protected function beforeWrite(&$data)
    {
        $data['administrators_id'] = admin_session('id');
        $data['pid'] = admin_session('role_id');
    }


    protected function afterWrite($id, $data)
    {
        if (env('APP.DATA_AUTH')){
            RoleModel::dataAuthSet($id, $this->request->post());
        }
    }

    public function listSearchParamHandle($search)
    {
        if (isset($search['mode']) && $search['mode'] === 'all'){
            $all_role = RoleModel::addSoftDelWhere()->field('id,pid,role,administrators_id')->select()->toArray();
            $mySubordinate = Sc::infinite($all_role)->handle(['administrators_id' => admin_session('id')], true);

            $search['id_I'] = array_column($mySubordinate, 'id');
        }else{
            $search['i.administrators_id'] = admin_session('id');
        }
        unset($search['mode']);
        return $search;
    }
}
