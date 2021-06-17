<?php

use sdModule\timedTask\ScTaskInterface;
use Swoole\Coroutine;
use Swoole\Coroutine\System;
use Swoole\Process;
use Swoole\Timer;
use function Swoole\Coroutine\run;

function task()
{
    // 自动加载文件引入
    include __DIR__ . '/../../../vendor/autoload.php';
    timer_log('asdsd');
    $date = new DateTime();
    System::sleep(60 - $date->format('s'));
    $taskArr = loadTask();
    Timer::tick(60000, function () use ($taskArr){
        $date = new DateTime();
        foreach ($taskArr as $value) {
            $crontab = timeAnalysis($value['crontab']);

            /**
             * 月份判断
             */
            if (!empty($crontab[4]) && !timeCheck($crontab[4], $date->format('m'))) {
                continue;
            }

            /**
             * 日期判断
             */
            if (!empty($crontab[3]) && !timeCheck($crontab[3], $date->format('d'))) {
                continue;
            }

            /**
             * 小时判断
             */
            if (!empty($crontab[2]) && !timeCheck($crontab[2], $date->format('H'))) {
                continue;
            }

            /**
             * 分钟判断
             */
            if (!empty($crontab[1]) && !timeCheck($crontab[1], $date->format('i'))) {
                continue;
            }

            /**
             * 创建协程执行任务
             */
            Coroutine::create(function () use ($value, $crontab) {
                performTask($crontab[0], $value['task']);
            });
        }
    });
}


$process = new Swoole\Process('task', '', 0, true);

/**
 * 设置进程名
 */
$process->name('sc-timed-task');

/**
 * 开启守护模式
 */
//\Swoole\Process::daemon();

/**
 * 开启进程
 */
$pid = $process->start();

if (preg_match('/^[4-9]\.[5-9]\.[0-9]/', SWOOLE_VERSION)) {
    /**
     * 协程方式回收子进程
     */
    run(function () use ($pid){
        $status = System::waitPid($pid) ?: [];
        timer_log(json_encode($status));
    });
}else{
    /**
     * 回收子进程
     */
    $status = Process::wait(true) ?: [];
    timer_log(json_encode($status));
}

/**
 * 日志记录
 * @param string $data
 * @param string $filename
 */
function timer_log(string $data, string $filename = 'stop.log')
{
    $data = "[" . date('Y-m-d H:i:s') . "] " . $data . "\r\n";

    if (!is_dir(__DIR__ . '/log')) {
        mkdir(__DIR__ . '/log', 0755);
    }
    file_put_contents(__DIR__ . '/log/' . $filename,  $data, FILE_APPEND);
}


/**
 * 获取执行的任务文件
 * @return array
 */
function loadTask(): array
{
    $config  = include __DIR__ . "/config.php";

    $taskArr = [];
    foreach ($config as $task => $crontab) {
        $taskClass = new $task();
        if (!$taskClass instanceof ScTaskInterface){
            unset($taskClass);
            continue;
        }
        $taskArr[$task] = [
            'crontab' => $crontab,
            'task' => $taskClass,
        ];
    }
    unset($taskClass);
    return $taskArr;
}

/**
 * 时间解析
 * @param string $timer
 * @return false|string[]
 */
function timeAnalysis(string $timer)
{
    /**
     * 拆分配置时间，同时过滤多于空格
     */
    $timer = array_filter(explode(' ', $timer), function ($v){
        return $v || $v === 0 || $v === '0';
    });

    foreach ($timer as &$item){
        /**
         * 拆分 范围限制 和 执行间隔
         */
        $time = explode('/', $item);

        /**
         * 确定是否有范围限制
         */
        $range = strpos($time[0], '-') ? explode('-', $time[0]) : $time[0];
        $item = [$range];
        empty($time[1]) or $item[] = $time[1];
    }
    unset($item);
    return $timer;
}

/**
 * 时间检查,是否满足执行脚本
 * @param array $time
 * @param int $current
 * @return bool
 */
function timeCheck(array $time, int $current): bool
{
    /**
     * "*" 表示不限制
     */
    if ($time[0] == '*') return true;

    /**
     * $time[0] 为数组 ，表示有范围限制 即配置为 a-b
     */
    if (is_array($time[0])){

        /**
         * 没有第二个参数，没有间隔时间执行，直接匹配
         */
        if (empty($time[1])){
            return $current >= $time[0][0] && $current <= $time[0][1];
        }

        /**
         * 有第二个参数，每间隔多少单位时间执行
         */
        return !(($current - $time[0][0]) % $time[1]);
    }

    /**
     * $time[0] 为数字 同时没有第二个参数，直接匹配
     */
    if (empty($time[1])){
        return $time[0] == $current;
    }

    /**
     * $time[0] 为数字有第二个参数，间隔多少单位时间执行
     */
    return !(($current - $time[0]) % $time[1]);
}


/**
 * 执行任务
 * @param array $crontab
 * @param ScTaskInterface $task
 */
function performTask(array $crontab, ScTaskInterface $task)
{
    /**
     * 没有间隔执行
     */
    if (empty($crontab[1])) {
        is_numeric($crontab[0]) or $crontab[0] = 0;

        /**
         * 根据时间判断是否是立即执行还是延后执行
         */
        if ($crontab[0] == 0){
            $task->handle();
        }else{
            $tick = $crontab[0] > 59 ? 59 : ($crontab[0] < 1 ? 1 : $crontab[0]);
            Timer::after($tick * 1000, function () use ($task){
                $task->handle();
            });
        }
    }else{
        /**
         * 每次间隔秒
         */
        $tick = $crontab[1] > 59 ? 59 : ($crontab[1] < 1 ? 1 : $crontab[1]);

        /**
         * 开始秒
         */
        $timer = is_array($crontab[0]) ? $crontab[0][0] : $crontab[0];
        $timer = $timer > 59 ? 59 : ($timer < 0 ? 0 : $timer);

        /**
         * 最大秒
         */
        $max = is_array($crontab[0]) ? $crontab[0][1] : 59;
        $max = $max > 59 ? 59 : ($max < 0 ? 0 : $max);


        /**
         * 定时间隔执行的函数
         * @param ScTaskInterface $task
         * @param int $timer
         * @param int $tick
         * @param int $max
         */
        $tickFun = function (ScTaskInterface $task, int &$timer, int $tick, int $max) {
            Coroutine::create(function () use ($task) {
                return $task->handle();
            });
            Timer::tick($tick * 1000, function ($timer_id) use ($task, &$timer, $tick, $max){
                $timer += $tick;
                if ($timer > $max){
                    Timer::clear($timer_id);
                }else{
                    $task->handle();
                }
            });
        };

        /**
         * 若果开始秒等于 0 直接开始执行
         * 开始秒不等于 0， 则延后对应的秒数开始执行
         */
        if ($timer == 0){
            $tickFun($task, $timer, $tick, $max);
        }else{
            Timer::after($timer * 1000, function ()  use ($tickFun, $task, &$timer, $tick, $max) {
                $tickFun($task, $timer, $tick, $max);
            });
        }
    }
}