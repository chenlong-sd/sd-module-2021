<?php
/**
 *
 * Index.php
 * User: ChenLong
 * DateTime: 2020/4/1 16:50
 */


namespace app\admin\controller\system;


use app\admin\AdminLoginSession;
use app\admin\model\system\Route;
use app\admin\service\system\AdministratorsService as AdministratorsService;
use app\admin\service\system\QuickOperationService;
use app\admin\validate\system\Administrators as AdministratorsValidate;
use app\common\controller\Admin;
use app\common\middleware\admin\PowerAuth;
use app\common\ResponseJson;
use app\common\SdException;
use think\facade\Config;
use think\facade\Db;
use think\response\Json;
use think\response\Redirect;
use think\response\View;

/**
 * Class Index
 * @package app\admin\controller\system
 * @author chenlong <vip_chenlong@163.com>
 */
class Index extends Admin
{
    /**
     * 初始化配置
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/11/5
     */
    protected function initialize()
    {
        parent::initialize();
        $this->middleware = array_diff($this->middleware, [PowerAuth::class]);
    }

    /**
     * 登录
     * @param AdministratorsService $administrators
     * @return Json|View
     * @throws SdException
     * @author chenlong<vip_chenlong@163.com>
     * @date 2022/1/19
     */
    public function login(AdministratorsService $administrators)
    {
        if($this->request->isPost()){
            $data = $this->request->post();

            // 数据验证
            $this->validate($data, AdministratorsValidate::class . '.login');

            // 登录数据操作
            $administrators->login(data_only($data, ['account', 'password']));

            return ResponseJson::success();
        }

        return view('login1');
    }

    /**
     * 开放登录
     * @param AdministratorsService $administrators
     * @return Json|View
     * @throws SdException
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/11/5
     */
    public function openLogin(AdministratorsService $administrators)
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

        return view('login');
    }

    /**
     * 框架主体
     * @param Route $route
     * @return View
     * @throws SdException
     */
    public function main(Route $route): View
    {
        return view('index_1', [
            'menu' => $route->getMenu(),
        ]);
    }


    /**
     * 主页
     * @param QuickOperationService $routeService
     * @return View
     */
    public function home(QuickOperationService $routeService): View
    {
        $route_data = $routeService->indexShowNode();
        return view('', compact('route_data'));
    }

    /**
     * @param string $table
     * @return Json
     */
    public function dataAuth(string $table = ''): Json
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
     * @return Redirect|void
     */
    public function loginOut(): Redirect
    {
        $open = AdminLoginSession::getTable('');
        $open and $open = "/" . $open;
        session(null);
        return redirect(admin_url('login' . $open));
    }

    /**
     * 休息一下
     * @return View
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/11/16
     */
    public function game(): View
    {
        return view();
    }
}
