<?php
/**
 * Test.php
 * User: ChenLong
 * DateTime: 2021-12-04 10:34:45
 */

namespace app\admin\controller;

use app\common\controller\Admin;
use app\common\ResponseJson;
use app\common\SdException;
use app\admin\service\TestService as MyService;
use app\admin\model\Test as MyModel;
use app\admin\page\TestPage as MyPage;
use app\common\validate\Test as MyValidate;
use think\response\Json;
use think\response\View;

/**
 * 测试表 控制器
 * Class Test
 * @package app\admin\controller\Test
 * @author chenlong <vip_chenlong@163.com>
 */
class Test extends Admin
{

    /**
     * @title("测试表列表")
     * @param MyService $service
     * @param MyModel $model
     * @param MyPage $page
     * @return Json|View
     * @throws SdException
     * @throws \ReflectionException
     */
    public function index(MyService $service, MyModel $model, MyPage $page)
    {
        return parent::index_($service, $model, $page);
    }
    
            
    /**
     * @title("新增测试表")
     * @param MyService $service
     * @param MyModel $model
     * @param MyPage $page
     * @return Json|View
     * @throws SdException
     * @throws \ReflectionException
     */
    public function create(MyService $service, MyModel $model, MyPage $page)
    {
        return parent::create_($service, $model, $page, MyValidate::class);
    }

            
    /**
     * @title("更新测试表")
     * @param MyService $service
     * @param MyModel $model
     * @param MyPage $page
     * @return Json|View
     * @throws SdException
     * @throws \ReflectionException
     */
    public function update(MyService $service, MyModel $model, MyPage $page)
    {
        return parent::update_($service, $model, $page, MyValidate::class);
    }

            
    /**
     * @title("删除测试表")
     * @param MyService $service
     * @param MyModel $model
     * @return Json
     * @throws SdException
     */
    public function delete(MyService $service, MyModel $model): Json
    {
        return parent::delete_($service, $model);
    }
            
    /**
     * @title("测试表状态更新")
     * @param MyService $service
     * @param MyModel $model
     * @return Json
     * @throws SdException
     */
    public function switchHandle(MyService $service, MyModel $model): Json
    {
        return parent::switchHandle_($service, $model);
    }

    /**
     * @title('测试弹窗表单')
     * @return Json
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/12/11
     */
    public function popups()
    {
        return ResponseJson::success();
    }
}
