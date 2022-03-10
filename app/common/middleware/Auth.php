<?php

namespace app\common\middleware;

use app\common\ResponseJson;
use think\Request;

class Auth
{
    public function handle(Request $request, callable $callable)
    {

        return ResponseJson::status404();


        return $callable($request);
    }
}
