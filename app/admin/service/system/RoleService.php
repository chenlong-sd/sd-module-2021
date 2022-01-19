<?php
/**
 * datetime: 2021/11/9 0:13
 * user    : chenlong<vip_chenlong@163.com>
 **/

namespace app\admin\service\system;

use app\admin\AdminBaseService;
use app\admin\AdminLoginSession;
use app\admin\model\system\DataAuth;
use app\admin\model\system\Power;
use app\admin\model\system\Role as RoleModel;
use app\admin\model\system\Route;
use app\common\BaseQuery;
use app\common\SdException;
use app\common\service\BackstageListsService;
use sdModule\common\Sc;
use think\facade\Config;
use think\facade\Db;

class RoleService extends AdminBaseService
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
        $model = RoleModel::join('administrators', 'i.administrators_id = administrators.id', 'left')
            ->join('role ip', 'i.pid = ip.id', 'left')
            ->field('i.assign_table,i.id,i.id role_id,i.role,i.pid,administrators.name administrators_id,i.create_time,ip.role parent_role');

        $open_table = Config::get('admin.open_login_table', []);
        return $service->setModel($model)->setCustomSearch(function (array $search, BaseQuery $model) {
            if (isset($search['mode']) && $search['mode'] === 'all'){
                if (AdminLoginSession::isAdmin()) {
                    $all_role = RoleModel::field('id,pid,role,administrators_id')->select()->toArray();
                }else{
                    $all_role = RoleModel::field('id,pid,role,open_id administrators_id')
                        ->where('open_table', AdminLoginSession::getTable())->select()->toArray();
                }
                $mySubordinate = Sc::tree($all_role)->setInheritedChain('administrators_id')->getLineData();
                $model->whereIn('i.id', array_column(array_filter($mySubordinate, function ($v){
                    return in_array(AdminLoginSession::getId(), $v['_inherited_chain_']);
                }), 'id'));
            }else{
                if (AdminLoginSession::isAdmin()) {
                    $model->where('i.administrators_id', AdminLoginSession::getId());
                } else {
                    $model->where([
                        'i.open_table' => AdminLoginSession::getTable(),
                        'i.open_id'    => AdminLoginSession::getId()
                    ]);
                }
            }
            return ['mode'];
        })->setEach(function ($v) use ($open_table) {
                $v->assign_table = $v->assign_table ? ($open_table[$v->assign_table]['name'] ?? '未知') : '系统账号';
            })->getListsData();
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

        if (AdminLoginSession::isAdmin()) {
            $data['administrators_id'] = AdminLoginSession::getId();
        }else{
            $data['open_table'] = AdminLoginSession::getTable();
            $data['open_id']    = AdminLoginSession::getId();
        }
        $data['pid'] = AdminLoginSession::getRoleId(0);
    }

    /**
     * @param string $save_type
     * @param array $data
     * @throws \app\common\SdException
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/11/9
     */
    protected function afterWrite(string $save_type, array $data)
    {
        if (Config::get('admin.data_auth', '')){
            $auth_data = [];
            foreach (request()->post() as $name => $value) {
                if (preg_match('/^data_auth_table_/', $name)){
                    $table = strtr($name, ['data_auth_table_' => '']);
                    $auth_data[$table] = [
                        'role_id'     => $data['id'],
                        'table_names' => $table,
                        'auth_id'     => implode(',', $value),
                        'create_time' => datetime(),
                        'update_time' => datetime(),
                    ];
                }
            }

            $have = DataAuth::where(['role_id' => $data['id']])->column('table_names', 'id');
            // 需要更新的
            if (($update = data_only($auth_data, $have))){
                foreach ($update as $name => $value){
                    DataAuth::update($value, ['id' => array_search($name, $have)]);
                }
            }

            // 需要新增的
            if (($insert_into = data_except($auth_data, $have)) && !DataAuth::insertAll($insert_into)) {
                throw new SdException('权限新增失败！');
            }
        }
    }

    /**
     * 角色权限设置
     * @param int $role_id
     * @param array $data
     * @throws SdException
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/11/9
     */
    public function powerSet(int $role_id, array $data)
    {
        $data = Sc::tree($data, true)->getLineData();

        // 当前为超级管理员，不做此判断
        if (!AdministratorsService::isSuper()) {
            if ($data && array_diff(array_column($data, 'id'), AdminLoginSession::getRoute())) {
                throw new SdException('权限错误！');
            }

            $role = RoleModel::findOrEmpty($role_id);

            if (AdminLoginSession::isAdmin() && $role->administrators_id !== AdminLoginSession::getId()){
                throw new SdException('该角色不是由你创建，无法操作！');
            }

            // 开放表操作判断
            if(!AdminLoginSession::isAdmin() && $table = AdminLoginSession::getTable()){
                if ($role->isEmpty()) {
                    throw new SdException('角色信息错误');
                }
                if ($role->open_table != $table || $role->open_id != AdminLoginSession::getId()) {
                    throw new SdException('该角色不是由你创建，无法操作！');
                }
            }
        }

        $power_data = [];
        foreach ($data as $item) {
            $power_data[] = [
                'route_id'    => $item['id'],
                'role_id'     => $role_id,
                'create_time' => date('Y-m-d H:i:s'),
                'update_time' => date('Y-m-d H:i:s')
            ];
        }

        Db::startTrans();
        try {
            // 删除之前的角色权限
            Power::where('role_id', $role_id)->update([
                'delete_time' => time(),
            ]);

            // 重新设置现在的权限
            Power::insertAll($power_data);

            Db::commit();
        } catch (\Exception $exception) {
            Db::rollback();
            throw new SdException($exception->getMessage());
        }
    }

    /**
     * 角色权限树数据
     * @param int $role_id 角色id
     * @return array
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/11/9
     */
    public function powerTreeData(int $role_id): array
    {
        try {
            $field = 'i.id,i.pid,i.title';

            $tree_data = AdministratorsService::isSuper()
                ? Route::alias('i')->field($field)->select()->toArray()
                : Route::alias('i')->where(['p.role_id' => explode(',', AdminLoginSession::getRoleId())], 'i')
                    ->join('power p', 'p.route_id = i.id')
                    ->field($field)->select()->toArray();

            $role_have_route = Power::where(['role_id' => $role_id])->column('route_id');
        } catch (\Exception $exception) {
            $tree_data       = [];
            $role_have_route = [];
        }


        return Sc::tree($tree_data)->setEach(function ($value) use ($role_have_route) {
            if (in_array($value['id'], $role_have_route)) {
                // 没有子集的时候才给checked true属性, 避免重复抵消
                if (empty($value['children'])) {
                    $value['checked'] = true;
                }
                // 是否展开
                $value['spread'] = true;
            }
            return $value;
        })->getTreeData();
    }
}
