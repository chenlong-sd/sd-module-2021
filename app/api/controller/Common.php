<?php
/**
 * Date: 2020/11/9 9:34
 * User: chenlong <vip_chenlong@163.com>
 */

namespace app\api\controller;


use app\common\controller\Api;
use app\common\ResponseJson;
use app\common\traits\api\SelfCollection;
use app\common\middleware\Token;
use sdModule\common\Sc;
use think\facade\Log;

/**
 * 公共、杂项处理控制器
 * Class Common
 * @package app\api\controller
 */
class Common extends Api
{
    /**
     * @var array 默认是不需要token的，需要的话，修改此参数或注释
     */
    public $middleware = [
        Token::class => [
            'except' => ['getToken']
        ]
    ];

    use SelfCollection;

    /**
     * 传递给前端的表信息组
     * @return array|string[]
     */
    protected function provideTableInfo(): array
    {
        return ['test' => '测试信息', 'user' => '用户信息'];
    }

    protected function encrypt_prefix(): string
    {
        return 'smx';
    }

    public function getToken()
    {
//        Log::write('hahahah');
        Log::error('hahahah');
        Log::sql('asdasdasd');
        Log::info('asdasdasd');
        Log::alert('asdasdasd');
        return ResponseJson::success(Sc::jwt(['id' => 1])->getRefresh(10)->getToken());
    }
}
