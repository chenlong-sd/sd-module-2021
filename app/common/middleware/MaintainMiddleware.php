<?php


namespace app\common\middleware;

use app\admin\AdminLoginSession;
use app\admin\service\system\AdministratorsService;
use app\common\ResponseJson;
use think\Response;
use think\Request;

/**
 * 维护模式请求处理
 * Class MaintainMiddleware
 * @package app\middleware
 * @author chenlong <vip_chenlong@163.com>
 */
class MaintainMiddleware
{

    /**
     * @param Request $request
     * @param \Closure $closure
     * @return mixed
     */
    public function handle(Request $request, \Closure $closure)
    {
        if(!env('MAINTAIN')){
            return $closure($request);
        }

        $appName = app('http')->getName();

        if ($appName === 'admin') {
            return $this->adminApp($request, $closure);
        }

        //
        // 如有其他应用自定义处理方式
        //

        return ResponseJson::fail(lang('maintain s'));
    }

    /**
     * 后台管理处理
     * @param Request $request
     * @param \Closure $closure
     * @return mixed|Response|\think\response\Json
     */
    private function adminApp(Request $request, \Closure $closure)
    {
        if (!(new class extends AdminLoginSession{public function loginCheck(): bool{return parent::loginCheck();}})->loginCheck() || AdminLoginSession::getMaintain() === true) {
            return $closure($request);
        }

        if ($request->isAjax()) {
            return ResponseJson::fail(lang('maintain s'));
        }else{
            return Response::create($this->html());
        }
    }

    /**
     * 维护html展示
     * @return string
     */
    private function html()
    {
        $lang = lang(lang('maintain s'));
        return <<<HTML
    <style>
        body{
            background: #efefef;
        }
        div{
            font-size: 50px;
            position: absolute;
            width:100%; 
            height: 100%;
            line-height: 100%;
        }
        p{
            position: absolute;
            top: 50%;
            left: 50%;
            height: 50px;
            text-align: center;
            font-weight: bold;
            width: 300px;
            color: red;
            margin: -25px 0 0 -150px;
            text-shadow: white -1px -1px, black 1px 1px;
        }
    </style>
    <div><p>{$lang}</p></div>
HTML;
    }
}
