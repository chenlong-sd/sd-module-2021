<?php
/**
 * Date: 2021/4/21 17:57
 * User: chenlong <vip_chenlong@163.com>
 */

namespace sdModule\timedTask;

/**
 * 任务执行的接口
 * Interface ScTaskInterface
 * @package sdModule\timedTask
 * @author chenlong<vip_chenlong@163.com>
 * @date 2021/6/17
 */
interface ScTaskInterface
{
    /**
     * 执行任务的核心函数
     * @return mixed
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/6/17
     */
    public function handle();
}
