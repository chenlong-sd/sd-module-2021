<?php
/**
 *
 * HeaderAllow.php
 * User: ChenLong
 * DateTime: 2020/4/14 15:16
 */


namespace app\middleware;


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
        if (in_array(app('http')->getName(), $this->allowApp())) {

            $Origin = env('APP_DEBUG') ? '*' : env('CROSS_DOMAIN.ALLOW_DOMAIN', '*');

            header('Access-Control-Allow-Origin:' . $Origin);
            header('Access-Control-Allow-Methods:' . env('CROSS_DOMAIN.ALLOW_METHOD', 'POST,GET,OPTIONS'));
            header('Access-Control-Allow-Headers:' . implode(',', $this->allowParam()));
            // 更多....

        }

        return $closure($request);
    }
}

