<?php
/**
 *
 */

namespace app\common\controller;


use app\BaseController;
use app\common\traits\admin\RequestMerge;

/**
 * Class Admin
 * @package app\common\controller
 * @author  chenlong <vip_chenlong@163.com>
 * @version 4.0
 */
abstract class Admin extends BaseController
{
    use RequestMerge;

    /**
     * 初始化代码执行
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/11/5
     */
    protected function initialize()
    {
        $this->middleware = config('admin.middleware') ?: [];
    }

}

