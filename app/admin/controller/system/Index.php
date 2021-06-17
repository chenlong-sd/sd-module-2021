<?php
/**
 *
 * Index.php
 * User: ChenLong
 * DateTime: 2020/4/1 16:50
 */


namespace app\admin\controller\system;


use app\admin\model\system\Administrators as AdministratorsModel;
use app\admin\model\system\Route;
use app\admin\validate\system\Administrators as AdministratorsValidate;
use app\common\controller\Admin;
use app\common\middleware\admin\PowerAuth;
use app\common\ResponseJson;
use app\common\SdException;
use think\facade\Config;
use think\facade\Db;

/**
 * Class Index
 * @package app\admin\controller\system
 * @author chenlong <vip_chenlong@163.com>
 */
class Index extends Admin
{

    public function exceptMiddleware()
    {
        return [PowerAuth::class];
    }

    /**
     * 登录
     * @param AdministratorsModel $administrators
     * @return \think\response\Json|\think\response\View
     * @throws \Exception
     */
    public function login(AdministratorsModel $administrators)
    {
        if($this->request->isPost()){
            $data = $this->request->post();

            $this->validate($data, AdministratorsValidate::class . '.login');

            return ResponseJson::mixin($administrators->login(data_only($data, ['account', 'password'])));
        }

        return $this->fetch('login');
    }

    /**
     * 开放登录
     * @param AdministratorsModel $administrators
     * @return \think\response\Json|\think\response\View
     * @throws SdException
     * @author chenlong <vip_chenlong@163.com>
     * @date 2021/6/10
     */
    public function openLogin(AdministratorsModel $administrators)
    {
        if ($this->request->isPost()) {
            $table      = $this->request->param('name');
            $open_table = Config::get('admin.open_login_table', []);
            if (!isset($open_table[$table])){
                throw new SdException('账号或密码错误，请确认');
            }
            $data = $this->request->post();
            $this->validate($data, AdministratorsValidate::class . '.login');

            $data['table']      = $table;
            $data['table_info'] = $open_table[$table];

            $administrators->openLogin(data_only($data, ['password', 'account', 'table', 'table_info']));

            return ResponseJson::success();
        }

        return $this->fetch('login');
    }

    /**
     * 框架主体
     * @param Route $route
     * @return \think\response\View
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function main(Route $route)
    {
        return view('index_1', [
            'menu' => $route->getMenu(),
        ]);
    }


    /**
     * 主页
     * @param Route $route
     * @return \think\response\View
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function home(Route $route): \think\response\View
    {
        $route_data = array_filter($route->getMenuRoute(), function ($v) {
            return ($v['pid'] != 0);
        });
        return view('', compact('route_data'));
    }

    /**
     * @param string $table
     * @return \think\response\Json
     */
    public function dataAuth(string $table = '')
    {
        $data_auth = array_column(config('admin.data_auth'), null, 'table');
        if (empty($data_auth[$table])){
            return ResponseJson::success();
        }

        try {
            $data = Db::name($table)->field("id value, {$data_auth[$table]['field']} name")->select();
        } catch (\Exception $exception) {
            $data = [];
        }

        return ResponseJson::success($data);
    }

    /**
     * 退出登录
     * @return \think\response\Redirect|void
     */
    public function loginOut(): \think\response\Redirect
    {
        $open = admin_session('table') ?: '';
        $open and $open = "/" . $open;
        session(null);
        return redirect(admin_url('login' . $open));
    }
}
