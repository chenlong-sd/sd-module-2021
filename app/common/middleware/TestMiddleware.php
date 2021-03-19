<?php
/**
 *
 * TestMiddleware.php
 * User: ChenLong
 * DateTime: 2020/3/31 13:59
 */


namespace app\common\middleware;


use think\Request;

class TestMiddleware
{
    public function handle(Request $request, \Closure $closure)
    {

        return var_dump(app('http')->getName());
        return json(['code' => 200,  'msg' => '看看亲不行']);
    }
}

