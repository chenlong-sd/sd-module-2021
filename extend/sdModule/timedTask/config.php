<?php

use sdModule\timedTask\task\{Test, Test2};

return [
    // 任务类名 => 秒 分 时 日 月 年  ，* 表示不限制，即每次. 1-15/4, 表示值在1到15之间，每隔4个单位执行一次
    // 任务必须实现 ScTaskInterface  接口
    // 查看进程ID: ps -aux | grep sc-timed-task
    Test::class => '1 * * * * *',
    Test2::class => '0 * * * * *',
];

