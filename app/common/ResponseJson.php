<?php
/**
 *
 * Response.php
 * User: ChenLong
 * DateTime: 2020/4/29 12:55
 */


namespace app\common;

use think\Response;

/**
 * json 数据返回格式定义
 * Class ResponseJson
 * @package app
 * @author chenlong <vip_chenlong@163.com>
 */
class ResponseJson
{
    private const SUCCESS_CODE = 200;
    private const FAIL_CODE = 202;

    private const SUCCESS_MSG = 'success';
    private const FAIL_MSG = 'fail';
    /**
     * @var string[]
     */
    private static $TEMPLATE = ['code', 'data', 'msg'];

    /**
     * @var array 额外参数
     */
    private static $param = [];

    /**
     * @param array $data
     * @return ResponseJson
     */
    public static function param(array $data)
    {
        self::$param = $data;
        return new self();
    }


    /**
     * 成功返回！
     * @param null   $data
     * @param string $msg
     * @param int    $code
     * @return \think\response\Json
     */
    public static function success($data = null, $msg = self::SUCCESS_MSG, $code = self::SUCCESS_CODE)
    {
        return self::returnHandle(compact(...self::$TEMPLATE));
    }

    /**
     * 失败返回
     * @param string $msg
     * @param int    $code
     * @param null   $data
     * @return \think\response\Json
     */
    public static function fail($msg = self::FAIL_MSG, int $code = self::FAIL_CODE, $data = null)
    {
        return self::returnHandle(compact(...self::$TEMPLATE));
    }

    /**
     * 混合模式，状态码只有内置的 200 和 202 其他请调用 fail()
     * @param null|mixed $mixin 有效字符串反回失败，其余全部成功！
     * @return \think\response\Json
     */
    public static function mixin($mixin = null)
    {
        return is_string($mixin) && $mixin ? self::fail($mixin) : self::success($mixin);
    }

    public static function status404()
    {
        return Response::create(null,'html',404);
    }

    /**
     * 自定义
     * @param mixed     $data
     * @return \think\response\Json
     */
    public static function custom($data)
    {
        return self::returnHandle($data);
    }

    /**
     * 最终返回处理
     * @param array $data
     * @return \think\response\Json
     */
    private static function returnHandle(array $data)
    {
        $data['msg'] = lang($data['msg']);
        return json(array_merge($data, self::$param));
    }
}

