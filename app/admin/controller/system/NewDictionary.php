<?php
/**
 * NewDictionary.php
 * User: ChenLong
 * DateTime: 2021-11-24 23:14:44
 */

namespace app\admin\controller\system;

use app\common\controller\Admin;
use app\common\SdException;
use app\admin\service\system\NewDictionaryService as MyService;
use app\admin\model\system\NewDictionary as MyModel;
use app\admin\page\system\NewDictionaryPage as MyPage;
use app\admin\validate\system\NewDictionary as MyValidate;

/**
 * 新字典表 控制器
 * Class NewDictionary
 * @package app\admin\controller\system\NewDictionary
 * @author chenlong <vip_chenlong@163.com>
 */
class NewDictionary extends Admin
{

    /**
     * @title("列表数据")
     * @param MyService $service
     * @param MyModel $model
     * @param MyPage $page
     * @return \think\response\Json|\think\response\View
     * @throws SdException
     * @throws \ReflectionException
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
     */
    public function delete(MyService $service, MyModel $model): \think\response\Json
    {
        return parent::delete_($service, $model);
    }

}
