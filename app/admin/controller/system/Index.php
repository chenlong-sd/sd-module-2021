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
use sdModule\layui\Dom;
use sdModule\layui\form\Form;
use sdModule\layui\form\FormSc;
use sdModule\layui\form\FormUnit;
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
     * @return string
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
    public function home(Route $route)
    {
        $form = FormSc::create([
            FormUnit::inline('asd', 'asdasd')->setChildrenItem(
                FormUnit::text('test1', )->placeholder('asdasd'),
                FormUnit::text('test', )->placeholder('asdasd')->defaultValue('asdad'),
                FormUnit::checkbox('cd', )->options([
                    '1' => 'kaishi',
                    '2' => 'jieshu',
                ])->defaultValue([1,2]),
            ),
            FormUnit::radio('cd23','asd')->options([
                '1' => 'kaishi',
                '2' => 'jieshu',
            ])->defaultValue(2),
            FormUnit::select('cd23','asd')->options([
                '1' => 'kaishi',
                '2' => 'jieshu',
                '3' => 'asdadas',
                '4' => 'cccccc',
                'asd' => [
                    '5' => 'asdadas',
                    '6' => 'cccccc',
                ]
            ])->defaultValue(5),
            FormUnit::time('time', '时间')->setTime('datetime', true),
            FormUnit::hidden('timess', '时间')->defaultValue('111'),
            FormUnit::password('pas', '密码')->defaultValue('111'),
            FormUnit::textarea('texss', '文本域')->defaultValue('111')
            ->inputAttr(['-' => [
                'style' => 'height:500px;'
            ]]),
            FormUnit::tag('taf', '标签')->defaultValue('asd'),
            FormUnit::selects('cd23dd','asd')->options([
                '1' => 'kaishi',
                '2' => 'jieshu',
                '3' => 'asdadas',
                '4' => 'cccccc',
            ])->defaultValue('1,4'),
        ]);
        $form->complete();
        return $this->fetch('common/save_page', [
            'form' => $form
        ]);
        $route_data = array_filter($route->getMenuRoute(), fn($v) => ($v['pid'] != 0));
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
    public function loginOut()
    {
        session(null);
        return redirect(admin_url('login'));
    }
}
