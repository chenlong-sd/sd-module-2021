<?php
/**
 *
 * Install.php
 * User: ChenLong
 * DateTime: 2020/4/28 16:58
 */


namespace app\common\middleware;


use think\facade\App;
use think\Request;

/**
 * Class Install
 * @package app\middleware
 * @author chenlong <vip_chenlong@163.com>
 */
class Install
{
    public function handle(Request $request, \Closure $closure)
    {
        if (!file_exists(App::getRootPath() . '.env') && $request->isGet() && ($request->pathinfo() != 'admin/install.html')) {
            return redirect(admin_url('install'));
        }

        return $closure($request);
    }
}

