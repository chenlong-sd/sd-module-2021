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
        if ($this->request->isPost()) {
            halt($this->request->post());
        }
        $form = Form::create([
            FormUnit::inline('asd', 'asdasd')->setChildrenItem(
                FormUnit::text('text1', )->placeholder('文本1'),
                FormUnit::text('text2', )->placeholder('文本2')->defaultValue('自带默认值'),
            ),
            FormUnit::checkbox('checkbox', "多选")->options([
                '1' => '多选1，自带默认',
                '2' => '多选2',
            ])->defaultValue(1),
            FormUnit::radio('radio','单选')->options([
                '1' => '单选1，自带默认',
                '2' => '单选2',
            ])->defaultValue(1),
            FormUnit::select('select','下拉')->options([
                '1' => '下拉1 ，自带默认',
                '2' => 'jieshu',
                '3' => 'asdadas',
                '4' => 'cccccc',
                'asd' => [
                    '5' => 'asdadas',
                    '6' => 'cccccc',
                ]
            ])->defaultValue(1),
            FormUnit::time('time', '时间')->setTime('datetime', true)->defaultValue(datetime()),
            FormUnit::hidden('hidden', '隐藏')->defaultValue('隐藏'),
            FormUnit::password('password', '密码')->defaultValue('123456'),
            FormUnit::textarea('textarea', '文本域')->defaultValue('textarea')
            ->inputAttr(['-' => [
                'style' => 'height:500px;'
            ]]),
            FormUnit::tag('tag', '标签')->defaultValue('tag'),
            FormUnit::selects('cd23dd','asd')->options([
                '1' => '自带默认',
                '2' => '自带默认',
                '3' => '数据默认',
                '4' => '数据默认',
            ])->defaultValue('1,2'),
            FormUnit::auxTitle('你很好看呀'),
            FormUnit::custom('自定义')->customHtml(Dom::create()->addContent('自定义')),
            FormUnit::switchSc('switch', '开关')->options([
                1 => 'ON', 2 => 'OFF'
            ])->defaultValue(1),
            FormUnit::video('videos', '视频')->defaultValue('upload_resource/20210602/3d0f3ab90d3e811dc456a81d30ddaefd.mp4'),
            FormUnit::image('image', '图片')->defaultValue('a.jpg'),
            FormUnit::images('images', '多图片')->defaultValue('a.jpg,b.jpg'),
            FormUnit::uEditor('ued', '富文本')->defaultValue('自带默认'),
        ]);
        $form->setSkinToPane()->setDefaultData([
            'text1' => 'ccccccccccccccc',
            'text2' => '1,2',
            'checkbox' => '1,2',
            'radio' => '2',
            'select' => '2',
            'time' => '2020-02-02:00:00:00',
            'hidden' => '4444444',
            'password' => '44444444444',
            'textarea' => 'ccccccc',
            'tag' => 'aasd,asdasd',
            'cd23dd' => '3,4',
            'switch' => '2',
            'videos' => 'asdasdas.mp4',
            'image' => 'yyy.jpg',
            'images' => 'xxx.jpg',
            'ued' => '我是百度',
        ])->complete();

        $form->unit[0]->addAttr('style', 'color:red;');

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
