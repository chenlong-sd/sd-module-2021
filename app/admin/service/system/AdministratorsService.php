<?php
/**
 * datetime: 2021/11/5 15:30
 * user    : chenlong<vip_chenlong@163.com>
 **/

namespace app\admin\service\system;

use app\admin\AdminBaseService;
use app\admin\enum\AdministratorsEnumStatus;
use app\admin\AdminLoginSession;
use app\admin\model\system\Administrators;
use app\admin\model\system\Administrators as MyModel;
use app\admin\model\system\AdministratorsRole;
use app\admin\model\system\Power;
use app\admin\model\system\Role as RoleModel;
use app\admin\model\system\Route;
use app\common\BaseQuery;
use app\common\middleware\admin\LoginMiddleware;
use app\common\middleware\admin\SinglePoint;
use app\common\SdException;
use app\common\service\BackstageListsService;
use sdModule\common\Sc;
use think\facade\Config;
use think\facade\Cookie;
use think\facade\Db;
use think\response\Json;

/**
 * 管理员
 * Class Administrators
 * @package app\admin\service\system
 * @author chenlong<vip_chenlong@163.com>
 * @date 2021/11/5
 */
class AdministratorsService extends AdminBaseService
{
    // 存session的key
    private const LOGIN_SESSION_KEY = 'Administrators__Sd__';

    /**
     * 列表数据返回
     * @param BackstageListsService $service
     * @return Json
     * @throws SdException
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/11/5
     */
    public function listData(BackstageListsService $service): Json
    {
        $model = Administrators::join('administrators_role ar', 'ar.administrators_id = i.id')
            ->join('role r', 'r.id = ar.role_id')
            ->field('i.id,i.name,i.account,i.status, i.status status_sc,GROUP_CONCAT(r.role) role,i.lately_time,i.create_time')
            ->group('i.id');

        return $service->setModel($model)->setCustomSearch(function (array $search, BaseQuery $model){
            if (isset($search['mode']) && $search['mode'] === 'all'){
                $all_role = RoleModel::field('id,pid,role,administrators_id')->select()->toArray();
                $mySubordinate = Sc::tree($all_role)->setInheritedChain('administrators_id')->getLineData();

                $model->whereIn('r.id', array_column(array_filter($mySubordinate, function ($v){
                    return in_array(AdminLoginSession::getId(), $v['_inherited_chain_']);
                }), 'id'));
            }else{
                $model->where('r.administrators_id', AdminLoginSession::getId());
            }

            return ['mode'];
        })->getListsData();
    }

    /**
     * 数据写入前的处理
     * @param array $data
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/11/5
     */
    protected function beforeWrite(array &$data)
    {
        $data = data_only($data, ['account', 'password', 'name', 'role_id', 'status', 'id']);
        !empty($data['password']) and $data['password'] = Sc::password()->encryption($data['password']);
    }

    /**
     * 数据写入之后的保存
     * @param string $save_type 保存类型
     * @param array $data 传入的数据
     * @throws SdException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/11/5
     */
    protected function afterWrite(string $save_type, array $data)
    {
        $administrators_id = $data['id'];

        $role_ids = explode(',', $data['role_id']);

        // 查出该用户之前的角色和现在的角色对比
        $administrators_role    = AdministratorsRole::withTrashed()->where(compact('administrators_id'))->select()->toArray();
        // 改管理员之前的角色id集合
        $administrators_role_id = array_column($administrators_role, 'role_id');
        // 要删除的之前的角色
        $delete_role = array_diff($administrators_role_id, $role_ids);
        // 新增的角色
        $add_role    = array_diff($role_ids, $administrators_role_id);

        $add_data = [];
        foreach ($add_role as $role_id) {
            $add_data[] = [
                'administrators_id' => $administrators_id,
                'role_id'           => $role_id,
                'create_time'       => datetime(),
                'update_time'       => datetime(),
            ];
        }

        // 有新增的角色，批量添加新增的角色数据
        if ($add_data && !AdministratorsRole::insertAll($add_data)){
            throw new SdException('administrators.failed to assign role');
        }

        // 恢复该角色的所有历史数据
        AdministratorsRole::withTrashed()->where(['administrators_id' => $administrators_id])->update(['delete_time' => 0]);
        // 删除现在不存在的角色
        AdministratorsRole::where(['role_id' => $delete_role, 'administrators_id' => $administrators_id])->update(['delete_time' => time()]);
    }

    /**
     * 修改密码
     * @param string $new_password 新密码
     * @param string $old_password 旧密码
     * @return int
     * @throws SdException
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/11/5
     */
    public function changePassword(string $new_password, string $old_password): int
    {
        // 验证新密码与原密码一致
        if ($new_password === $old_password) {
            throw new SdException(lang('administrator.password Unanimous'));
        }

        // 判断当前登录的是不是administrators 账号
        $table    = AdminLoginSession::isAdmin() ? 'administrators' : AdminLoginSession::getTable();
        // 查出原密码加密字符串
        $password = Db::name($table)->where(['id' => AdminLoginSession::getId()])->value('password');

        // 验证原密码错误
        if (!Sc::password()->verify($old_password, $password)){
            throw new SdException(lang('administrator.old password error'));
        }

        try {
            return Db::name($table)->where(['id' => AdminLoginSession::getId()])->update([
                'password'    => Sc::password()->encryption($new_password),
                'update_time' => date('Y-m-d H:i:s')
            ]);
        } catch (\Throwable $exception) {
            throw new SdException($exception->getMessage());
        }
    }

    /**
     * 登录
     * @param array $data 包含登陆信息的数据
     * @throws SdException
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/11/8
     */
    public function login(array $data)
    {
        try {
            $administrators = MyModel::where(['account' => $data['account']])->allowEmpty(true)->find();

            if ($administrators->isEmpty()) {
                throw new SdException('administrator.password error');
            }

            if ($administrators['error_number'] >= config('admin.max_error_password_number')
                && $administrators['error_date'] === date('Y-m-d')) {
                throw new SdException('administrator.password error max');
            }

            // 密码错误，错误次数加一
            if (!Sc::password()->verify($data['password'], $administrators['password'])) {
                $administrators->error_number++;
                $administrators->save();
                throw new SdException('administrator.password error');
            }

            if($administrators->getData('status') == AdministratorsEnumStatus::DISABLE){
                throw new SdException('administrator.account disable');
            }

            // 登录成功，清空错误次数和错误日期
            $administrators->error_number = 0;
            $administrators->lately_time = datetime();
            $administrators->save();

            $administrators->set('is_admin', true);// 是否是管理员账号登录
            $administrators->set('route', Power::where(['role_id' => explode(',', $administrators['role_id'])])->column('route_id'));

            // 设置登录的session值
            self::setSession(data_only($administrators->toArray(), ['id', 'name', 'account', 'role_id', 'route', 'is_admin']));
            // 缓存所有路由
            Route::cacheAllRoute();
            // 设置单点登录
            SinglePoint::setSinglePoint();

        } catch (\Throwable $exception) {
            throw new SdException($exception->getMessage());
        }
    }

    /**
     * 开放其他表登录
     * @param array $data
     * @throws SdException
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/11/5
     */
    public function openLogin(array $data)
    {
        try {

            if (!$login_data = Db::name($data['table'])->where($data['table_info']['account'], $data['account'])->find()) {
                throw new SdException('账号或密码错误');
            }

            if (!Sc::password()->verify($data['password'], $login_data[$data['table_info']['password']])) {
                throw new SdException('账号或密码错误.');
            }

            // 账号状态判断
            if (!empty($data['table_info']['status'])) {
                $status_field = array_key_first($data['table_info']['status']);
                if ($login_data[$status_field] != $data['table_info']['status'][$status_field]) {
                    throw new SdException('账号已被冻结，请联系相关管理人员解冻');
                }
            }

            // 默认存session的值
            $session_field = [
                'id'       => $login_data['id'],
                'role_id'  => $login_data['role_id'],
                'is_admin' => false,
                'table'    => $data['table'],
                'route'    => Power::where('role_id', $login_data['role_id'])->column('route_id')
            ];

            // 自定义的session存值处理
            if (!empty($data['table_info']['session'])) {
                $custom_session_field = array_flip($data['table_info']['session']);
                foreach ($custom_session_field as $field => $alias){
                    if (empty($value = $login_data[$field])) {
                        continue;
                    }
                    $field = is_numeric($alias) ? $field : $alias;
                    $session_field[$field] = $value;
                }
            }

            // 存session
            self::setSession($session_field);
            // 缓存所有路由节点
            Route::cacheAllRoute();
            // 设置单点登录的信息
            SinglePoint::setSinglePoint();
            // 记录登录的人的类型
            Cookie::set(LoginMiddleware::USER_TYPE_KEY, $data['table']);

        } catch (\Throwable $exception) {
            throw new SdException($exception->getMessage());
        }
    }


    /**
     * 设置管理session员信息
     * @param $data
     * @return void
     */
    private static function setSession($data): void
    {
        (new class extends AdminLoginSession{
            public function save(array $data)
            {
                return parent::save($data);
            }
        })->save($data);
    }

    /**
     * 获取管理员session信息
     * @param null $key 指定键值
     * @return mixed
     */
    public static function getSession($key = null)
    {
        return $key === null
            ? session(self::LOGIN_SESSION_KEY)
            : session(self::LOGIN_SESSION_KEY . '.' . $key);
    }


    /**
     * 超级管理员验证
     * @return bool
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/11/9
     */
    public static function isSuper(): bool
    {
        return AdminLoginSession::getId() === Config::get('admin.super', 1) && AdminLoginSession::isAdmin();
    }

}

