<?php
/**
 * 管理员
 * Admin.php
 * User: ChenLong
 * DateTime: 2020/3/31 15:00
 */


namespace app\admin\controller\system;


use app\admin\page\system\AdministratorsPage as MyPage;
use app\admin\service\system\AdministratorsService as MyService;
use app\admin\validate\system\Administrators as MyValidate;
use app\common\controller\Admin;
use app\common\ResponseJson;
use app\common\SdException;
use app\admin\model\system\Administrators as MyModel;

/**
 * Class Administrators
 * @package app\admin\controller\system
 * @author chenlong <vip_chenlong@163.com>
 */
class Administrators extends Admin
{
    /**
     * @title("列表数据")
     * @param MyService $service
     * @param MyModel $model
     * @param MyPage $page
     * @return \think\response\Json|\think\response\View
     * @throws SdException
     * @throws \ReflectionException
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/11/5
     */
    public function index(MyService $service, MyModel $model, MyPage $page)
    {
        return parent::index_($service, $model, $page);
    }

    /**
     * @title("数据创建")
     * @param MyService $service
     * @param MyModel $model
     * @param MyPage $page
     * @return \think\response\Json|\think\response\View
     * @throws SdException
     * @throws \ReflectionException
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/11/5
     */
    public function create(MyService $service, MyModel $model, MyPage $page)
    {
        return parent::create_($service, $model, $page, MyValidate::class);
    }

    /**
     * @title("数据更新")
     * @param MyService $service
     * @param MyModel $model
     * @param MyPage $page
     * @return \think\response\Json|\think\response\View
     * @throws SdException
     * @throws \ReflectionException
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/11/5
     */
    public function update(MyService $service, MyModel $model, MyPage $page)
    {
        return parent::update_($service, $model, $page, MyValidate::class);
    }

    /**
     * @title("数据删除")
     * @param MyService $service
     * @param MyModel $model
     * @return \think\response\Json
     * @throws SdException
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/11/5
     */
    public function delete(MyService $service, MyModel $model): \think\response\Json
    {
        return parent::delete_($service, $model);
    }

    /**
     * @title("状态更新")
     * @param MyService $service
     * @param MyModel $model
     * @return \think\response\Json
     * @throws SdException
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/11/5
     */
    public function switchHandle(MyService $service, MyModel $model): \think\response\Json
    {
        return parent::switchHandle_($service, $model);
    }

    /**
     * @title("修改密码")
     * @param MyService $administrators
     * @return \think\response\Json|\think\response\View
     * @throws SdException
     * @throws \think\db\exception\DbException
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/11/5
     */
    public function passwordUpdate(MyService $administrators, MyPage $page)
    {
        if ($this->request->isPost()) {
            // 密码数据基本验证
            $data = data_filter($this->request->post());
            $this->validate($data, MyValidate::class . '.password');
            // 执行修改密码
            $result = $administrators->changePassword($data['password'], $data['password_old']);
            return $result ? ResponseJson::success() : ResponseJson::fail(lang('fail'));
        }

        return view($page->form_template, [
            'form' => $page->updatePassword()
        ]);
    }

}

