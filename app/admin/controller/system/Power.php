<?php


namespace app\admin\controller\system;


use app\common\controller\Admin;
use app\common\ResponseJson;
use app\common\SdException;
use sdModule\common\Sc;
use think\facade\Config;

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

        $tree_data = (admin_session('id') === Config::get('admin.super', 1) && admin_session('is_admin'))
            ? $route::alias('i')->field($field)->select()->toArray()
            : $route::alias('i')->where(['p.role_id' => explode(',', admin_session('role_id'))], 'i')
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
     * @throws SdException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author chenlong <vip_chenlong@163.com>
     * @date 2021/6/10
     */
    public function set(\app\admin\model\system\Power $power, $role_id = 0): \think\response\Json
    {
        $data = $this->request->post('set', []);
        $data and $data = Sc::infinite($data)->reveres();

        if (admin_session('id') === Config::get('admin.super', 1) && admin_session('is_admin')) goto set;

        if ($data && array_diff(array_column($data, 'id'), admin_session('route'))) {
            return ResponseJson::fail('权限错误！');
        }

        if (admin_session('is_admin') && \app\admin\model\system\Role::getCreateAdministrators($role_id) !== admin_session('id')){
            return ResponseJson::fail('该角色不是由你创建，无法操作！');
        }

        // 开放表操作判断
        if(!admin_session('is_admin') && $table = admin_session('table')){
            if (!$role = \app\admin\model\system\Role::find($role_id)) {
                throw new SdException('角色信息错误');
            }
            if ($role->open_table != $table || $role->open_id != admin_session('id')) {
                throw new SdException('该角色不是由你创建，无法操作！');
            }
        }

        set:

        return ResponseJson::success($power->setPower($data, $role_id));
    }
}
