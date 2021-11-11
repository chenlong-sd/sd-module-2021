<?php
/**
 * 后台操作日志
 * Log.php
 * User: ChenLong
 * DateTime: 2020-05-14 14:04
 */

namespace app\admin\controller\system;

use app\admin\model\system\Log as LogModel;
use app\admin\page\system\LogPage as LogPage;
use app\admin\service\system\LogService;
use app\common\controller\Admin;

/**
 * Class Log
 * @package app\admin\controller\system
 * @author chenlong <vip_chenlong@163.com>
 */
class Log extends Admin
{
    /**
     * @title('日志列表')
     * @param LogService $service
     * @param LogModel $model
     * @param LogPage $page
     * @return \think\response\Json|\think\response\View
     * @throws \ReflectionException
     * @throws \app\common\SdException
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/11/9
     */
    public function index(LogService $service, LogModel $model, LogPage $page)
    {
        return parent::index_($service, $model, $page);
    }


}