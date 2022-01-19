<?php
/**
 *
 * PowerAuth.php
 * User: ChenLong
 * DateTime: 2020/4/26 19:38
 */


namespace app\common\middleware\admin;


use app\admin\AdminLoginSession;
use app\admin\service\system\AdministratorsService;
use app\common\ResponseJson;
use think\Request;

/**
 * 权限验证
 * Class PowerAuth
 * @package app\middleware\admin
 * @author chenlong <vip_chenlong@163.com>
 */
class PowerAuth
{

    /**
     * @param Request  $request
     * @param \Closure $closure
     * @return mixed|\think\Response|\think\response\Json
     * @throws \Exception
     */
    public function handle(Request $request, \Closure $closure)
    {
        // 超级管理员直接越过验证
        if (AdministratorsService::isSuper()) return $closure($request);

        // 获取当前路由的ID
        $route_id = array_search($request->middleware('route_path'), cache(config('admin.route_cache')) ?: []);

        // 判断权限
        if ( (empty($route_id) && !in_array($request->action(), ['create', 'update', 'del']))
            || ($route_id && in_array($route_id, AdminLoginSession::getRoute([])))
        ) {
            return $closure($request);
        }

        return $request->isAjax() ? ResponseJson::fail(lang('No access')) : response($this->htmlTip());
    }

    /**
     * 提示页面
     * @return string
     */
    private function htmlTip()
    {
        $lang = lang('No access');
        return <<<HTML
    <style>
        body{
            background: #efefef;
        }
        p{
            position: absolute;
            user-select: none;
            font-size: 50px;
            top: 50%;
            left: 50%;
            height: 50px;
            text-align: center;
            font-weight: bold;
            width: 300px;
            color: #1E9FFF;
            margin: -25px 0 0 -150px;
            text-shadow: white -1px -1px, black 1px 1px;
        }
    </style>
    <p>{$lang}</p>
HTML;
    }

}

