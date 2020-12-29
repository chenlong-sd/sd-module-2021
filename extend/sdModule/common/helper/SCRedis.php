<?php


namespace sdModule\common\helper;


use app\common\SdException;

class SCRedis
{
    /**
     * @var array 基础配置
     */
    private static array $option = [
        'host' => '127.0.0.1',
        'port' => 6379,
        'password' => '',
        'select' => 0,
        'timeout' => 0,
        'expire' => 0,
        'persistent' => false,
        'prefix' => '',
        'tag_prefix' => 'tag:',
        'serialize' => [],
    ];

    /**
     * @var \Redis
     */
    private $redis;

    /**
     * SCRedis constructor.
     */
    public function __construct()
    {
        $this->redis =\think\facade\Cache::store('redis')->handler();
    }

    /**
     * redis 执行加锁代码，执行的代码不要有 exit() | die() 等结束程序的代码
     * @param callable $callback 回调类型的执行的函数，匿名函数 或 数组（参数同 call_user_func()）
     * @param array $param 函数的参数
     * @param null $id 唯一标识，不传的时候默认为调用类名加方法
     * @param int $exp 最长锁时间
     * @param null $tip 有锁时（即锁尚未放开）的返回值
     * @return mixed|string|null
     * @throws \Throwable
     */
    public function lock(callable $callback, array $param = [], $id = null, int $exp = 3, $tip = null)
    {
        if ($id === null) {
            $id_info = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 2);
            $id = strtr($id_info[1]['class'], ['\\' => '_']) . '@' . $id_info[1]['function'];
        }

        if (!$this->redis->setnx($id, 1)) throw new SdException($tip ?: '操作太快了哟');

        $this->redis->expire($id, $exp);

        return self::run($callback, $param, $id);
    }

    /**
     * 等待执行 执行的代码不要有 exit() | die() 等结束程序的代码
     * @param callable $callable 回调类型的执行的函数，匿名函数 或 数组（参数同 call_user_func()）
     * @param array $param 函数的参数
     * @param string|null $id 唯一标识，不传的时候默认为调用类名加方法
     * @return mixed
     * @throws \Throwable
     */
    public function wait(callable $callable, array $param = [], string $id = null)
    {
        if ($id === null) {
            $id_info = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 2);
            $id = strtr($id_info[1]['class'], ['\\' => '_']) . '@' . $id_info[1]['function'];
        }

        while (!$this->redis->setnx($id, 1)) {
            usleep(10);
        }

        $this->redis->expire($id, 10);
        return self::run($callable, $param, $id);
    }

    /**
     * 执行代码
     * @param callable $closure
     * @param array $param
     * @param string|null $id
     * @return mixed
     * @throws \Throwable
     */
    private function run(callable $closure, array $param, string $id = null)
    {
        try {

            $result = call_user_func_array($closure, $param);

            $this->redis->del($id);

            return $result;
        } catch (\Throwable $throwable) {

            $this->redis->del($id);

            throw $throwable;
        }
    }
}

