<?php


namespace app\middleware\admin;


use think\facade\Session;
use think\Request;

/**
 * 单点登录判断
 * Class SinglePoint
 * @package app\middleware\admin
 * @author chenlong <vip_chenlong@163.com>
 */
class SinglePoint
{
    private const SINGLE_POINT_PRE = "__Sd__single";

    /**
     * @param Request  $request
     * @param \Closure $closure
     * @return mixed|\think\response\Redirect|void
     * @throws \Exception
     */
    public function handle(Request $request, \Closure $closure)
    {
        if (!env('APP_DEBUG', false) && !$this->verifySinglePoint()) {
            session(null);
            return redirect(admin_url('login', [
                'tip' => lang('login single point')
            ]));
        }
        return $closure($request);
    }

    /**
     * 验证单点登录
     * @return bool
     */
    private function verifySinglePoint()
    {
        $singPoint = cache(self::SINGLE_POINT_PRE . admin_session('id'));
        return !$singPoint || $singPoint == Session::getId();
    }

    /**
     * 设置单点登录
     */
    public static function setSinglePoint()
    {
        cache(self::SINGLE_POINT_PRE . admin_session('id'), Session::getId(), config('session.expire'));
    }
}
