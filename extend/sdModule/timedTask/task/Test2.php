<?php
/**
 * Date: 2021/4/22 16:54
 * User: chenlong <vip_chenlong@163.com>
 */

namespace sdModule\timedTask\task;


use sdModule\timedTask\ScTaskInterface;

class Test2 implements ScTaskInterface
{

    public function handle()
    {
        $str = strip_tags(file_get_contents('https://filfox.info/zh/address/f0690459'));
        $balance = 0;
        if (preg_match('/账户余额( )+([0-9]|,|\.)+( )+FIL/', $str, $match)) {
            preg_match('/[0-9]+\.[0-9]+/', strtr($match[0], [',' => '', '，' => '']), $balance2);
            $balance = current($balance2);
        }
        timer_log($balance, 'test.log');
    }
}
