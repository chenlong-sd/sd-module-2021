<?php


namespace app\common\middleware\admin;


use app\admin\AdminLoginSession;
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
            $table = AdminLoginSession::getTable('');
            $table and $table = '/' . $table;
            session(null);
            return redirect(admin_url('login' . $table, [
                'tip' => lang('login single point')
            ]));
        }
        return $closure($request);
    }

    /**
     * 验证单点登录
     * @return bool
     * @author chenlong <vip_chenlong@163.com>
     * @date 2021/6/10
     */
    private function verifySinglePoint(): bool
    {
        $singPoint = cache(self::singlePointKey());
        return !$singPoint || $singPoint == Session::getId();
    }

    /**
     * 设置单点登录
     * @author chenlong <vip_chenlong@163.com>
     * @date 2021/6/10
     */
    public static function setSinglePoint()
    {
        cache(self::singlePointKey(), Session::getId(), config('session.expire'));
    }

    /**
     * 单点登录的键获取
     * @return string
     * @author chenlong <vip_chenlong@163.com>
     * @date 2021/6/10
     */
    private static function singlePointKey(): string
    {
        return self::SINGLE_POINT_PRE . (AdminLoginSession::isAdmin() ? '' : AdminLoginSession::getTable('')) . AdminLoginSession::getId();
    }
}
