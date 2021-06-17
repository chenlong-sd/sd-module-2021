<?php
/**
 *
 * AdminMiddleware.php
 * User: ChenLong
 * DateTime: 2020/4/9 18:32
 */


namespace app\common\traits\admin;

/**
 * 后台中间件处理
 * Trait AdminMiddleware
 * @method array onlyMiddleware()       只使用的中间件
 * @method array exceptMiddleware()     排除的中间件
 * @method array customMiddleware()     自定义的中间件，如某个中间件需要排除某个方法或 只要某个方法或者新增一个
 * @package app\common\controller
 * @author chenlong <vip_chenlong@163.com>
 */
trait AdminMiddleware
{
    private $onlyMiddleware = [];
    private $exceptMiddleware = [];
    private $customMiddleware = [];

    /**
     * 注册中间件
     * @return array|void
     */
    private function registerMiddleware()
    {
        $this->loadControllerSetMiddleware();

        if ($this->onlyMiddleware){
            return $this->middleware = (array)$this->onlyMiddleware;
        }

        $middleware = config('admin.middleware') ?: [];

        if ($this->exceptMiddleware) {
            $middleware = array_diff($middleware, $this->exceptMiddleware);
        }
        if ($this->customMiddleware) {
            $middleware = array_diff($middleware, array_keys($this->customMiddleware));
            $middleware = array_merge($middleware, $this->customMiddleware);
        }
        $this->middleware = $middleware;
    }

    /**
     * 加载controller里面设置的中间件
     */
    private function loadControllerSetMiddleware()
    {
        if (method_exists($this, 'onlyMiddleware')) {
            $this->onlyMiddleware = $this->onlyMiddleware();
        }
        if (method_exists($this, 'exceptMiddleware')) {
            $this->exceptMiddleware = $this->exceptMiddleware();
        }
        if (method_exists($this, 'customMiddleware')) {
            $this->customMiddleware = $this->customMiddleware();
        }
    }
}

