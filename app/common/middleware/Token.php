<?php
/**
 *
 * Token.php
 * User: ChenLong
 * DateTime: 2020/4/14 14:17
 */


namespace app\common\middleware;

use app\common\ResponseJson;
use sdModule\common\Sc;
use think\Request;
use think\Response;

/**
 * token 处理
 * 1.debug模式可在header里面传 .env文件里面的 CROSS_DOMAIN.No-Token 参数值按照规则为: key=value&key=value....可不用Token
 *      可能为 "b7c8bfe96d3877ec977c3008ec06f4af":"user_id=110&dd=3"
 * 2.Token 所有token相关的参数均用 header 传输
 * 3.header 携带 Refresh-Token 自动判断为刷新token，并返回新的token，不会请求数据，需要参数：Refresh-Token,Token
 * Class Token
 * @package app\middleware
 * @author chenlong <vip_chenlong@163.com>
 */
class Token
{
    /**
     * @param Request  $request
     * @param \Closure $closure
     * @return bool|mixed|Response|\think\response\Json
     */
    public function handle(Request $request, \Closure $closure)
    {
        if (env('APP_DEBUG') && ($no_token = env('CROSS_DOMAIN.NO_TOKEN'))
            && $request->header($no_token, '')){
            return $this->noTokenData($request, $closure);
        }
        // TODO 返回过期时间
        $before = $this->beforeHandle($request);

        if ($before !== true) return $before;

        $response = $closure($request);

        return $this->afterHandle($response, $request);
    }


    /**
     * 请求之后
     * @param Response $response
     * @param Request  $request
     * @return Response
     */
    private function afterHandle(Response $response, Request $request)
    {
//
        return $response;
    }


    /**
     * 请求之前
     * @param Request $request
     * @return bool|\think\response\Json
     */
    private function beforeHandle(Request $request)
    {
        $check = $request->header('Refresh-Token', '')
            ? $this->update($request->header('Refresh-Token', ''), $request->header('Token', ''))
            : $this->verify($request->header('Token', ''));

        if (is_string($check)) {
            return ResponseJson::fail($check, 203);
        } elseif ($check === false) {
            return ResponseJson::fail(lang('token Expired'), 205);
        }

        if (!empty($check['token'])) {
            return ResponseJson::success($check);
        }

        $this->paramDefine($request, $check);

        return true;
    }

    /**
     * token参数处理
     * @param Request $request
     * @param         $param
     */
    protected function paramDefine(Request $request, $param)
    {
        $with = [];
        foreach ($param as $key => $value){
            $with['token.' . $key] = $value;
        }

        $request->withMiddleware($with);
    }


    /**
     * @param $token
     * @return bool
     */
    private function verify($token)
    {
        try {
            return Sc::jwt()->tokenVerify($token);
        } catch (\Exception $exception) {
            return $exception->getMessage();
        }
    }

    /**
     * @param $refresh_token
     * @param $token
     * @return bool|mixed
     */
    private function update($refresh_token, $token)
    {
        try {
            return Sc::jwt()->refreshToken($refresh_token, $token);
        } catch (\Exception $exception) {
            return false;
        }
    }

    /**
     * 没有Token时的数据分析
     * @param Request  $request
     * @param \Closure $closure
     * @return bool|Request
     */
    public function noTokenData(Request $request, \Closure $closure)
    {
        $data = $request->header(env('CROSS_DOMAIN.NO_TOKEN'), '');

        if (!$data) return $closure($request);

        $no_token_data = [];

        foreach (explode('&', $data) as $item) {
            list($key, $value) = array_pad(explode('=', $item), 2, '');
            $no_token_data[$key] = $value;
        }

        $this->paramDefine($request, $no_token_data);

        return $closure($request);
    }
}

