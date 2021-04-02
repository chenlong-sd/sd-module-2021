<?php
/**
 *
 * RequestMonitoring.php
 * User: ChenLong <vip_chenlong@163.com>
 * DateTime: 2020/7/15 10:53
 */


namespace app\common\middleware;


use app\common\SdException;
use think\facade\Config;
use think\facade\Event;
use think\facade\Log;
use think\Request;
use think\Response;

/**
 * 请求监听
 * Class RequestListen
 * @package app\middleware
 */
class RequestListen
{
    /**
     * 时间
     * @param Request  $request
     * @param \Closure $closure
     * @param array    $time
     *  指定时间      ['2020-10-11 11:11:11']
     *  指定当天范围   ['2020-10-11 11:11:11','2020-10-11 11:20:11', true]
     *  指定范围      ['2020-10-11 11:11:11','2020-10-12 11:20:11']
     * @return mixed
     */
    public function handle(Request $request, \Closure $closure, array $time = [])
    {
        if (!$this->timeCheck($time)){
            return $closure($request);
        }

        $start_time = microtime(true);
        Log::info(sprintf("------------------------【START TIME:%s】------------------------", date('Y-m-d H:i:s')));
        Log::info(sprintf("URL:%s://%s%s", $request->server('REQUEST_SCHEME'), $request->server('SERVER_NAME'), $request->server('REQUEST_URI')));
        Log::info(sprintf("IP:%s   ", $request->server('REMOTE_ADDR')));
        Log::info(sprintf("TOKEN:%s", $request->server('HTTP_TOKEN')));
        Log::info(sprintf("PARAM:%s", var_export($request->param(), true)));

        $request->withMiddleware(['request_listen' => true]);
        $response = $closure($request);

        Log::info(sprintf("RESPONSE:%s  ", var_export($response->getData(), true)));
        Log::info(sprintf("TOTAL_TIME:%s", microtime(true) - $start_time));
        Log::info(sprintf("========================【END TIME:%s】========================", date('Y-m-d H:i:s')));

        return $response;
    }

    /**
     * @param array $time
     * @return bool
     */
    private function timeCheck(array $time)
    {
        if (empty($time)) return true;

        $current = time();
        if (($length = count($time)) >= 3) {
            $setting_start = strtotime(date('Y-m-d ' . $time[0]));
            $setting_end   = strtotime(date('Y-m-d ' . $time[1]));
            $today         = strtotime(date('Y-m-d'));
            return (($current - $today) >= ($setting_start - $today))
                && (($current - $today) <= ($setting_end - $today));
        }else if ($length == 2) {
            return $current >= strtotime(date($time[0]))
                && $current <= strtotime(date($time[1]));
        }

        return $current === strtotime($time[0]);
    }
}

