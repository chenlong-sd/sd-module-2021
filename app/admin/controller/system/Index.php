<?php
/**
 *
 * Index.php
 * User: ChenLong
 * DateTime: 2020/4/1 16:50
 */


namespace app\admin\controller\system;


use app\admin\enum\RouteEnumType;
use app\admin\model\system\Route;
use app\admin\service\system\AdministratorsService as AdministratorsService;
use app\admin\validate\system\Administrators as AdministratorsValidate;
use app\common\controller\Admin;
use app\common\middleware\admin\PowerAuth;
use app\common\ResponseJson;
use app\common\SdException;
use app\common\service\DictionaryService;
use sdModule\layui\Dom;
use sdModule\layui\form4\FormProxy;
use sdModule\layui\form4\FormUnit;
use sdModule\layui\form4\formUnit\unitProxy\TextProxy;
use think\facade\Config;
use think\facade\Db;
use think\Request;
use think\route\dispatch\Url;
use think\route\RuleGroup;

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
     * @return \think\response\Json|\think\response\View
     * @throws SdException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/11/5
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
     * @return \think\response\Json|\think\response\View
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
     * @return \think\response\View
     * @throws SdException
     */
    public function main(Route $route): \think\response\View
    {
        return view('index_1', [
            'menu' => $route->getMenu(),
        ]);
    }


    /**
     * 主页
     * @param Route $route
     * @return \think\response\View
     * @throws SdException
     */
    public function home(Route $route): \think\response\View
    {
        dump('权限BUG, 推荐');
        dump('跳转tab');
        dump('生成代码调整');
        dump('系统资源BUG');
        dump('日志查看优化');
        dump('数据库检查');
        $route_data = array_filter($route->getRouteFromType(RouteEnumType::create(RouteEnumType::LEFT_MENU)), function ($v) {
            return ($v['pid'] != 0);
        });
        $unit = [
            FormUnit::text('test1', '测试一')->required()->defaultValue(1),
            FormUnit::text('test2', '测试二')->showWhere('test1', 2),
            FormUnit::password('test3', '密码')->showWhere('test4', [1, 2]),
            FormUnit::checkbox('test4', '多选')->options([1 => '测试一', 2 => '测试二'])->defaultValue('1,2'),
            FormUnit::image('test6', '图片')->closeSystemResource()->showWhere('test4', 1)->defaultValue('upload_resource/20211119/693791ea144dda3b1bdced0b12262ed2.jpg'),
            FormUnit::group(
                FormUnit::text('test7', '组合1'),
                FormUnit::text('test8', '组合2'),
            )->showWhere('test4', 2),
            FormUnit::table('table')->addChildrenItem(
                FormUnit::text('test10', '表格1'),
                FormUnit::time('test11', '表格2'),
                FormUnit::checkbox('test12', '表格3')->options(['1' => '开', '2' => '关']),
                FormUnit::select('test144', '表格34')->options(['1' => '开', '2' => '关']),
            )->showWhere('test4', '1'),
            FormUnit::select('test13', '下拉')->defaultValue(1)->options(['1' => '选项一', '2' => '选项二', ]),
            FormUnit::select('test14', '下拉2')->defaultValue(1)->options([
                ['value' => 1, 'title' => '选项一', 'parent_value' => 1],
                ['value' => 2, 'title' => '选项二', 'parent_value' => 2],
            ], 'test13'),
            FormUnit::select('test15', '下拉3')->defaultValue(1)->options([
                ['value' => 1, 'title' => '选项一', 'parent_value' => 1],
                ['value' => 2, 'title' => '选项二', 'parent_value' => 2],
                ['value' => 3, 'title' => '选项三', 'parent_value' => 2],
            ], 'test14'),
            FormUnit::radio('test17', '单选')->defaultValue(1)->options(['1' => '选项一', '2' => '选项二', ]),
            FormUnit::selects('test20', '下拉多选')->defaultValue(1)->options(['1' => '选项一', '2' => '选项二', ]),
            FormUnit::slider('test21', '滑块')->defaultValue(1),
            FormUnit::color('test18', '颜色')->defaultValue(1)->format('rgb'),
            FormUnit::tag('test22', '标签')->defaultValue(['asd']),
            FormUnit::upload('test23', '文件上传')->shortTip(';asdasdasd'),
            FormUnit::video('test24', '视频上传')->shortTip(';asdasdasd'),
            FormUnit::auxTitle('辅助标题')->line()->showWhere('test4', 1),
            FormUnit::customize(Dom::create()->addContent('asdsadsad'))->showWhere('tes4', 1),
            FormUnit::hidden('test18', )->defaultValue(1),
            FormUnit::images('test19', '多图')->showWhere('test4', 1),
            FormUnit::textarea('test16', '文本域')->showWhere('test4', 1)->inputAttr('test', ['style' => 'width:500px']),
            FormUnit::time('test9', '时间')->showWhere('test17', 2)->shortTip('asdsa')->dateType('datetime')->jsConfig(['range' => true]),
            FormUnit::uEditor('test5', '富文本')->defaultValue('asdsadasdsa')->showWhere('test4', 1),
        ];


        $f = FormProxy::create($unit,  ['test1' => 'asdasdsa', 'test4' => [1]])->setScene('test')->setPane()
            ;
        $f->getCss();
        return view('common/save_page_4', ['form' => $f]);
        halt("左右滑动的页面", $f->getHtml());

        return view('', compact('route_data'));
    }

    /**
     * @param string $table
     * @return \think\response\Json
     */
    public function dataAuth(string $table = ''): \think\response\Json
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

    /**
     * 休息一下
     * @return \think\response\View
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/11/16
     */
    public function game(): \think\response\View
    {
        return view();
    }
}
