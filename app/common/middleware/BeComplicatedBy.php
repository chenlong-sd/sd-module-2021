<?php
/**
 * Date: 2020/8/11 17:45
 * User: chenlong <vip_chenlong@163.com>
 */

namespace app\common\middleware;


use app\Request;
use sdModule\common\Sc;

/**
 * 并发时加锁执行代码，或等待
 * Class BeComplicatedBy
 * @package app\middleware
 */
class BeComplicatedBy
{
    /**
     * @param Request $request
     * @param \Closure $closure
     * @param null $mode 为路由中间件时，模式取值： wait | lock
     * @return mixed|string|null
     * @throws \Throwable
     */
    public function handle(Request $request, \Closure $closure, $mode = null)
    {
        $BeComplicatedBy = $mode ?: env('BE_COMPLICATE_BY', '');

        /**
         * 默认 \app\common\controller\Api 类启用了此中间件，针对于post请求
         * 取消可设置 middleware 属性， get请求可设置路由中间件，加上mode参数即可
         */
        if (!$BeComplicatedBy || ($mode === null && !$request->isPost())) {
            return $closure($request);
        }

        if ($BeComplicatedBy === 'lock') {
            return Sc::redis()->lock(function () use($request, $closure){
                return $closure($request);
            });
        }

        if ($BeComplicatedBy == 'wait') {
            return Sc::redis()->wait(function () use($request, $closure){
                return $closure($request);
            });
        }

        return $closure($request);
    }
}


