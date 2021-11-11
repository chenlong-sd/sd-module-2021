<?php
/**
 *
 * Role.php
 * User: ChenLong
 * DateTime: 2020-04-12 22:22
 */

namespace app\admin\controller\system;

use app\admin\model\system\Role as RoleModel;
use app\admin\page\system\RolePage as RolePage;
use app\admin\service\system\RoleService;
use app\common\controller\Admin;
use app\common\ResponseJson;
use app\common\SdException;
use ReflectionException;
use think\response\Json;
use think\response\View;

/**
 * Class Role
 * @package app\admin\controller\system
 * @author chenlong <vip_chenlong@163.com>
 */
class Role extends Admin
{
    /**
     * @title('所有角色列表')
     * @param RoleService $service
     * @param RoleModel $model
     * @param RolePage $page
     * @return Json|View
     * @throws ReflectionException
     * @throws SdException
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/11/8
     */
    public function index(RoleService $service, RoleModel $model, RolePage $page)
    {
        return parent::index_($service, $model, $page);
    }

    /**
     * @title("新增角色")
     * @param RoleService $service
     * @param RoleModel $model
     * @param RolePage $page
     * @return Json|View
     * @throws ReflectionException
     * @throws SdException
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/11/8
     */
    public function create(RoleService $service, RoleModel $model, RolePage $page)
    {
        return parent::create_($service, $model, $page, \app\admin\validate\system\Role::class);
    }

    /**
     * @title('修改角色')
     * @param RoleService $service
     * @param RoleModel $model
     * @param RolePage $page
     * @return Json|View
     * @throws ReflectionException
     * @throws SdException
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/11/8
     */
    public function update(RoleService $service, RoleModel $model, RolePage $page)
    {
        return parent::update_($service, $model, $page, \app\admin\validate\system\Role::class);
    }

    /**
     * @title('删除角色')
     * @param RoleService $service
     * @param RoleModel $model
     * @return Json
     * @throws SdException
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/11/9
     */
    public function delete(RoleService $service, RoleModel $model): Json
    {
        return parent::delete_($service, $model);
    }

    /**
     * @title('权限设置')
     * @param RoleService $service
     * @return Json|View
     * @throws SdException
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/11/9
     */
    public function powerSet(RoleService $service)
    {
        if ($this->request->isPost()) {

            $service->powerSet($this->request->post('role_id'), $this->request->post('set', []));

            return ResponseJson::success();
        }

        return \view('power');
    }

    /**
     * 获取角色权限数据
     * @param RoleService $service
     * @param int $role_id
     * @return Json
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/11/9
     */
    public function getPowerTreeData(RoleService $service, int $role_id = 0): Json
    {
        return ResponseJson::success($service->powerTreeData($role_id));
    }
}
