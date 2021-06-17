<?php
/**
 *
 * Administrators.php
 * User: ChenLong
 * DateTime: 2020/4/2 13:33
 */


namespace app\admin\model\system;

use app\common\BaseModel;
use app\common\SdException;
use app\common\middleware\admin\SinglePoint;
use sdModule\common\Sc;
use sdModule\layui\Layui;
use think\facade\Db;
use think\facade\Env;
use think\helper\Str;

/**
 * Class Administrators
 * @package app\admin\model\system
 * @author chenlong <vip_chenlong@163.com>
 */
class Administrators extends BaseModel
{

    protected $defaultSoftDelete = 0;

    private const LOGIN_SESSION_KEY = 'Administrators__Sd__';

    const STATUS_NORMAL = 1;    // 正常
    const STATUS_FROZEN = 2;    // 冻结

    protected $schema = [
        'id' => 'int',
        'name' => 'varchar',
        'account' => 'varchar',
        'password' => 'varchar',
        'error_number' => 'tinyint',
        'lately_time' => 'datetime',
        'error_date' => 'date',
        'role_id' => 'int',
        'status' => 'tinyint',
        'create_id' => 'int',
        'create_time' => 'datetime',
        'update_time' => 'datetime',
        'delete_time' => 'int',
    ];

    /**
     * @var bool 维护模式
     */
    private $maintain = false;

    /**
     * 分类值展示处理
     * @param $value
     * @return string
     */
    public function getStatusAttr($value)
    {
        $field = [
            self::STATUS_NORMAL => Layui::tag()->black('normal'),
            self::STATUS_FROZEN => Layui::tag()->red('disable'),
        ];

        return $field[$value] ?? $value;
    }

    /**
     * 获取状态信息
     * @return array
     */
    public static function getStatusSc()
    {
        return [
            self::STATUS_NORMAL => lang('normal'),
            self::STATUS_FROZEN => lang('disable'),
        ];
    }

    /**
     * 设置管理session员信息
     * @param $data
     * @return mixed
     */
    private static function setSession($data)
    {
        return session(self::LOGIN_SESSION_KEY, $data);
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
     * 登录
     * @param $data
     * @return bool
     * @throws SdException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function login($data)
    {
        if (Env::get('MAINTAIN')) {
            $data = $this->maintainLoginDataHandle($data);
        }

        $administrators = $this->where(['account' => $data['account']])->allowEmpty(true)->find();

        if ($administrators->isEmpty()) {
            throw new SdException('administrator.password error');
        }

        if ($administrators['error_number'] >= config('admin.max_error_password_number')
            && $administrators['error_date'] === date('Y-m-d')) {
            throw new SdException('administrator.password error max');
        }

        if (!Sc::password()->verify($data['password'], $administrators['password'])) {
            $this->passwordError($administrators);
            throw new SdException('administrator.password error');
        }

        if($administrators->getData('status') === self::STATUS_FROZEN){
            throw new SdException('administrator.account disable');
        }

        if ($this->update(['error_number' => 0, 'lately_time' => date('Y-m-d H:i:s')],
            ['id' => $administrators['id']])) {

            $administrators->set('maintain', $this->maintain);
            $administrators->set('is_admin', true);// 是否是管理员账号登录
            $administrators->set('route', Power::where(['role_id' => explode(',', $administrators['role_id'])])->column('route_id'));

            self::setSession(data_only($administrators->toArray(),
                ['id', 'name', 'account', 'maintain', 'role_id', 'route', 'is_admin']));
            Route::cacheAllRoute();
            SinglePoint::setSinglePoint();

            return true;
        }

        throw new SdException('administrator.login fail');
    }

    /**
     * 维护时的登录处理
     * @param $data
     * @return mixed
     * @throws SdException
     */
    private function maintainLoginDataHandle($data)
    {
        $account  = config('admin.maintain_admin_rule.account', '/__mt$/');
        $password = config('admin.maintain_admin_rule.password', '/^__mt/');
        if (!preg_match($account, $data['account']) || !preg_match($password, $data['password'])){
            throw new SdException('administrator.maintain');
        }

        $this->maintain   = true;
        $data['account']  = preg_replace($account, '', $data['account']);
        $data['password'] = preg_replace($password, '', $data['password']);
        return $data;
    }

    /**
     * @param $administrators
     */
    private function passwordError($administrators)
    {
        $this->where(['id' => $administrators['id']])->inc('error_number')
            ->update(['error_date' => date('Y-m-d')]);
    }

    /**
     * 数据权限设置
     * @param int $id
     * @param array $request
     * @throws SdException
     */
    public static function dataAuthSet(int $id, array $request)
    {
        $data = [];
        foreach ($request as $name => $value) {
            if (preg_match('/^data_auth_table_/', $name)){
                $table = strtr($name, ['data_auth_table_' => '']);
                $data[$table] = [
                    'administrators_id' => $id,
                    'table_names'       => $table,
                    'auth_id'           => $value,
                    'create_time'       => datetime(),
                    'update_time'       => datetime(),
                ];
            }
        }

        $have = DataAuth::where(['administrators_id' => $id])->column('table_names', 'id');
        if (($update = data_only($data, $have))){
            foreach ($update as $name => $value){
                if (!DataAuth::update($value, ['id' => array_search($name,  $have)])){
                    throw new SdException('权限更新失败');
                }
            }
        }

        if (($insert_into = data_except($data, $have)) && !DataAuth::insertAll($insert_into)) {
            throw new SdException('权限新增失败！');
        }
    }

    /**
     * 开放登录
     * @param array $data
     * @author chenlong <vip_chenlong@163.com>
     * @date 2021/6/10
     */
    public function openLogin(array $data)
    {
        $model_class = '\\app\\common\\model\\' . Str::studly($data['table']);
        /** @var BaseModel $model */
        $model = Sc::reflex()->getInstance($model_class);
        if (!$login_data = $model->where($data['table_info']['account'], $data['account'])->find()) {
            throw new SdException('账号或密码错误');
        }

        if (!Sc::password()->verify($data['password'], $login_data[$data['table_info']['password']])) {
            throw new SdException('账号或密码错误.');
        }

        // 账号状态判断
        if (!empty($data['table_info']['status'])) {
            $status_field = array_key_first($data['table_info']['status']);
            if ($login_data->getData($status_field) != $data['table_info']['status'][$status_field]) {
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
    }
}