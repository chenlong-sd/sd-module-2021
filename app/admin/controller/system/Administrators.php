<?php
/**
 * 管理员
 * Admin.php
 * User: ChenLong
 * DateTime: 2020/3/31 15:00
 */


namespace app\admin\controller\system;


use app\admin\model\system\AdministratorsRole;
use app\admin\model\system\Role as RoleModel;
use app\common\controller\Admin;
use app\common\ResponseJson;
use app\common\SdException;
use app\common\service\BackstageListsService;
use sdModule\common\Sc;
use app\admin\model\system\Administrators as MyModel;

/**
 * Class Administrators
 * @package app\admin\controller\system
 * @author chenlong <vip_chenlong@163.com>
 */
class Administrators extends Admin
{
    private const LOGIN_SESSION_KEY = 'Administrators__Sd__';

    /**
     * @param BackstageListsService $service
     * @return mixed|string|\think\Collection|\think\response\Json
     * @throws SdException
     */
    public function listData(BackstageListsService $service)
    {
        $model = $this->getModel()
            ->join('administrators_role ar', 'ar.administrators_id = i.id')
            ->join('role r', 'r.id = ar.role_id')
            ->field('i.id,i.name,i.account,i.status, i.status status_sc,GROUP_CONCAT(r.role) role,i.lately_time,i.create_time')
            ->group('i.id');

        return $service->setModel($model)->setListSearchParamHandle([$this, 'listSearchParamHandle'])->getListsData();
    }


    /**
     * 修改密码
     * @param MyModel $administrators
     * @return string
     * @throws \Exception
     */
    public function passwordUpdate(MyModel $administrators)
    {
        if ($this->request->isPost()) {
            $data = $this->verify('password');
            $password = $administrators::where(['id' => admin_session('id')])->value('password');

            if (!Sc::password()->verify($data['password_old'], $password)){
                return ResponseJson::fail(lang('administrator.old password error'));
            }
            if (Sc::password()->verify($data['password'], $password)) {
                return ResponseJson::fail(lang('administrator.password Unanimous'));
            }

            $result = $administrators::where(['id' => admin_session('id')])->update([
                'password' => Sc::password()->encryption($data['password']),
                'update_time' => date('Y-m-d H:i:s')
            ]);

            return $result ? ResponseJson::success() : ResponseJson::fail(lang('fail'));
        }

        return $this->fetch('password_edit');
    }


    protected function beforeWrite(array &$data)
    {
        $data = data_only($data, ['account', 'password', 'name', 'role_id', 'status', 'id']);
        !empty($data['password']) and $data['password'] = Sc::password()->encryption($data['password']);
    }

    protected function afterWrite($id, array $data)
    {
        if (env('APP.DATA_AUTH')){
            MyModel::dataAuthSet($id, $this->request->post());
        }

        $role_id = explode(',', $data['role_id']);
        $administrators_role    = AdministratorsRole::where(['administrators_id' => $id])->select()->toArray();
        $administrators_role_id = array_column($administrators_role, 'role_id');
        $delete_role = array_diff($administrators_role_id, $role_id);
        $add_role    = array_diff($role_id, $administrators_role_id);

        $add_data = [];
        foreach ($add_role as $value) {
            $add_data[] = [
                'administrators_id' => $id,
                'role_id'           => $value,
                'create_time'       => datetime(),
                'update_time'       => datetime(),
            ];
        }
        if ($add_data && !AdministratorsRole::insertAll($add_data)){
            throw new SdException('administrators.failed to assign role');
        }

        AdministratorsRole::update(['delete_time' => 0], ['administrators_id' => $id]);
        AdministratorsRole::update(['delete_time' => time()], ['role_id' => $delete_role, 'administrators_id' => $id]);
    }

    /**
     * 判断登录
     * @return mixed
     */
    public static function LoginCheck()
    {
        return session('?' . self::LOGIN_SESSION_KEY);
    }


    public function listSearchParamHandle($search)
    {
        if (isset($search['mode']) && $search['mode'] === 'all'){
            $all_role = RoleModel::field('id,pid,role,administrators_id')->select()->toArray();
            $mySubordinate = Sc::infinite($all_role)->handle(['administrators_id' => admin_session('id')], true);

            $search['r.id_I'] = array_column($mySubordinate, 'id');
        }else{
            $search['r.administrators_id'] = admin_session('id');
        }
        unset($search['mode']);
        return $search;
    }
}

