<?php


namespace sdModule\common\helper;


use app\common\SdException;
use think\facade\Db;

class SCRedis
{
    /**
     * @var array 基础配置
     */
    private static $option = [
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
     * @var null 唯一标识，不传的时候默认为调用类名加方法
     */
    private $key = null;

    /**
     * @var int 最长锁时间
     */
    private $exp = 3;

    /**
     * @var string 有锁时（即锁尚未放开）的返回值
     */
    private $tip = '操作太快了哟';

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
     * @return mixed|string|null
     * @throws \Throwable
     */
    public function lock(callable $callback, ...$param)
    {
        if ($this->key === null) {
            $id_info = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 2);
            $this->key = strtr($id_info[1]['class'], ['\\' => '_']) . '@' . $id_info[1]['function'];
        }

        if (!$this->redis->setnx($this->key, 1)) throw new SdException($this->tip);

        $this->redis->expire($this->key, $this->exp);

        return self::run($callback, $param, $this->key);
    }

    /**
     * 等待执行 执行的代码不要有 exit() | die() 等结束程序的代码
     * @param callable $callable 回调类型的执行的函数，匿名函数 或 数组（参数同 call_user_func()）
     * @param array $param 函数的参数
     * @return mixed
     * @throws \Throwable
     */
    public function wait(callable $callable, ...$param)
    {
        if ($this->key === null) {
            $id_info = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 2);
            $this->key = strtr($id_info[1]['class'], ['\\' => '_']) . '@' . $id_info[1]['function'];
        }

        while (!$this->redis->setnx($this->key, 1)) {
            usleep(10);
        }

        $this->redis->expire($this->key, 10);
        return self::run($callable, $param, $this->key);
    }

    /**
     * 加锁并执行事务
     * @param callable $callback
     * @param array $param
     * @return mixed|string|null
     * @throws \Throwable
     */
    public function lockAndTransaction(callable $callback, ...$param)
    {
        return $this->lock(function () use ($callback, $param){
            return Db::transaction(function () use ($callback, $param) {
                return call_user_func($callback, ...$param);
            });
        }, []);
    }



    /**
     * 执行代码
     * @param callable $closure
     * @param array $param
     * @param string|null $key
     * @return mixed
     * @throws \Throwable
     */
    private function run(callable $closure, array $param, string $key = null)
    {
        try {

            $result = call_user_func_array($closure, $param);

            $this->redis->del($key);

            return $result;
        } catch (\Throwable $throwable) {

            $this->redis->del($key);

            throw $throwable;
        }
    }

    /**
     * @param string $tip
     * @return SCRedis
     */
    public function setTip(string $tip): SCRedis
    {
        $this->tip = $tip;
        return $this;
    }

    /**
     * @param int $exp
     * @return SCRedis
     */
    public function setExp(int $exp): SCRedis
    {
        $this->exp = $exp;
        return $this;
    }

    /**
     * @param null|string $key
     * @return SCRedis
     */
    public function setKey(?string $key): SCRedis
    {
        $this->key = $key;
        return $this;
    }

    /**
     * @return \Redis
     */
    public function getRedis(): ?\Redis
    {
        return $this->redis;
    }
}

