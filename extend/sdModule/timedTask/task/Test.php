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
        $str = strip_tags(file_get_contents('https://filfox.info/zh/address/f0690459'));
        if (preg_match('/(lastSeenTimestamp:([0-9]|e)+,balance:")([0-9]+)"/', $str, $match)){
            timer_log($match[0], 'test.log');
            timer_log("123123123", 'test.log');
        }
        timer_log("test asdasdas", 'test.log');
    }

}
