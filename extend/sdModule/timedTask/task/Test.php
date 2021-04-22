<?php
/**
 * Date: 2021/4/21 18:00
 * User: chenlong <vip_chenlong@163.com>
 */

namespace sdModule\timedTask\task;


use sdModule\timedTask\ScTaskInterface;

class Test implements ScTaskInterface
{
    public function handle()
    {
        timer_log("I im Test");
    }

}
