<?php
/**
 * 登录验证过滤
 * LoginMiddleware.php
 * User: ChenLong
 * DateTime: 2020/4/1 16:05
 */


namespace app\common\middleware\admin;


use app\admin\controller\system\Administrators;
use think\Request;
use think\Response;

/**
 * 登录验证
 * Class LoginMiddleware
 * @package app\middleware\admin
 * @author chenlong <vip_chenlong@163.com>
 */
class LoginMiddleware
{
    /** @var array 无需登录的请求地址 */
    const EXCEPT_PATH = ['System.Index/login'];

    /**
     * @param Request  $request
     * @param \Closure $closure
     * @return Response
     */
    public function handle(Request $request, \Closure $closure):Response
    {
        $request = $this->requestAction($request);
        // TODO 路径加密 ---
        if (!Administrators::LoginCheck() && !in_array($request->middleware('route_path'), self::EXCEPT_PATH)) {
            return redirect(admin_url('login'));
        }

        return $closure($request);
    }


    /**
     * @param Request $request
     * @return Request
     */
    private function requestAction(Request $request)
    {
        $requestPath = implode('/', [
            $request->controller(),
            $request->action()
        ]);

        $request->withMiddleware(['route_path' => parse_name($requestPath, 1)]);
        return $request;
    }
}
