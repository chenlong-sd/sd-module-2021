<?php
/**
 *
 * Log.php
 * User: ChenLong
 * DateTime: 2020/5/12 16:55
 */


namespace app\middleware\admin;


use think\Request;

/**
 * Class Log
 * @package app\middleware\admin
 * @author chenlong <vip_chenlong@163.com>
 */
class Log
{
    /**
     * @param Request  $request
     * @param \Closure $closure
     * @return mixed
     * @throws \Exception
     */
    public function handle(Request $request, \Closure $closure)
    {
        if (in_array($request->method(), config('admin.log_write') ?: [])) {
            \app\admin\model\system\Log::create($this->logDataMake($request));
        }

        return $closure($request);
    }


    /**
     * 记录后台操作日志的数据
     * @param Request $request
     * @return array
     * @throws \Exception
     */
    private function logDataMake(Request $request)
    {
        $param = json_encode($this->param(), JSON_UNESCAPED_UNICODE);
        return [
            'method' => array_search($request->method(), \app\admin\model\system\Log::getMethodSc(false)) ?: 1,
            'route_id' => array_search($request->middleware('route_path'), cache(config('admin.route_cache')) ?: []) ?: 0,
            'administrators_id' => admin_session('id', 0),
            'param' => mb_strlen($param) >= 2048 ? mb_substr($param, 0, 2045) . '...' : $param,
            'route' => $request->middleware('route_path'),
            'create_time' => ($time = date('Y-m-d H:i:s')),
            'update_time' => $time,
        ];
    }

    /**
     * @param null $params
     * @return array
     */
    private function param($params = null)
    {
        $param = [];
        $params = $params === null ? \request()->param() : $params;
        foreach ($params as $item => $value) {
            if (is_array($value)) {
                $value = $this->param($value);
            }else if (strlen($value) > 32){
                $value = substr($value, 0, 29) . '...';
            }
            preg_match('/password|pwd/', $item) or $param[$item] = $value;
        }

        return $param;
    }
}
