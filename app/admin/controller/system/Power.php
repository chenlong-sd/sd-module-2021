<?php


namespace app\admin\controller\system;


use app\common\controller\Admin;
use app\common\ResponseJson;
use sdModule\common\Sc;

/**
 * Class Power
 * @package app\admin\controller\system
 * @author chenlong <vip_chenlong@163.com>
 */
class Power extends Admin
{
    /**
     * 权限树形数据
     * @param \app\admin\model\system\Route $route
     * @param int $role_id
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function tree(\app\admin\model\system\Route $route, $role_id = 0)
    {
        $field = 'i.id,i.pid,i.title';

        $tree_data = admin_session('id') === config('admin.super', 1)
            ? $route::alias('i')->field($field)->select()->toArray()
            : $route::where(['p.role_id' => admin_session('role_id')], 'i')
                ->join('power p', 'p.route_id = i.id')
                ->field($field)->select()->toArray();

        $role_have_route = \app\admin\model\system\Power::where(['role_id' => $role_id])->column('route_id');


        $tree_data = Sc::infinite($tree_data)->setCall(function ($value) use ($role_have_route){
            if (in_array($value['id'], $role_have_route)) {
                empty($value['children']) and $value['checked'] = true;
                $value['spread'] = true;
            }
            return $value;
        }, true)->handle();

        return ResponseJson::success($tree_data);
    }

    /**
     * 设置权限
     * @param \app\admin\model\system\Power $power
     * @param int $role_id
     * @return \think\response\Json
     * @throws \app\common\SdException
     */
    public function set(\app\admin\model\system\Power $power, $role_id = 0)
    {
        $data = $this->request->post('set', []);
        $data and $data = Sc::infinite($data)->reveres();

        if (admin_session('id') === config('admin.super', 1)) goto set;

        $role_have_route = $power::where('role_id', $role_id)->column('route_id');

        if ($data && array_diff(array_column($data, 'id'), $role_have_route)) {
            return ResponseJson::fail('权限错误！');
        }

        if (\app\admin\model\system\Role::getCreateAdministrators($role_id) !== admin_session('id')){
            return ResponseJson::fail('该角色不是由你创建，无法操作！');
        }

        set:

        return ResponseJson::success($power->setPower($data, $role_id));
    }
}
