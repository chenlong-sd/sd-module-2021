<?php

function task()
{
    // 自动加载文件引入
    include __DIR__ . '/../../../vendor/autoload.php';

    $taskArr = loadTask();

    while (true) {
        $date = new DateTime('2021-04-18');
        var_dump($date->format('w'));
        var_dump($date->format('W'));
        var_dump($date->format('d'));
        var_dump($date->format('H'));
        var_dump($date->format('i'));
        foreach ($taskArr as $value){
            call_user_func([$value['task'], 'handle']);
        }
        Swoole\Coroutine\System::sleep(60);
        break;
    }
}


$process = new Swoole\Process('task', '', 0, true);

$process->name('sc-timed-task');

\Swoole\Process::daemon();
$pid = $process->start();

if (preg_match('/^[4-9]\.[5-9]\.[0-9]/', SWOOLE_VERSION)) {
    \Swoole\Coroutine\run(function () use ($pid){
        $status = \Swoole\Coroutine\System::waitPid($pid) ?: [];
        timer_log(json_encode($status));
        timer_log(111);
    });
}else{
    $status = \Swoole\Process::wait(true) ?: [];
    timer_log(json_encode($status));
    timer_log(222);
}

/**
 * @param $data
 */
function timer_log(string $data)
{
    $data = "[" . date('Y-m-d H:i:s') . "] " . $data . "\r\n";

    file_put_contents(__DIR__ . '/stop.log',  $data, FILE_APPEND);
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
        if (!$taskClass instanceof \sdModule\timedTask\ScTaskInterface){
            unset($taskClass);
            continue;
        }
        $timer = explode(' ', $crontab);
        $taskArr[$task] = [
            'crontab' => $timer,
            'task' => $taskClass,
        ];
    }
    return $taskArr;
}

