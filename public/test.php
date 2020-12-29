<?php
set_time_limit(0);
ob_end_clean();
ob_end_flush();//关闭缓存
ob_implicit_flush(1);
//header("Content-type:text/html;charset=utf8");
//header('X-Accel-Buffering: no');
header('Content-Type: text/event-stream');
header('Cache-Control: no-cache');
if (ob_get_level() == 0) ob_start();
echo str_repeat(" ",5000);
for ($i = 1; $i < 5; $i ++) {

    echo $i . date('Y-m-d H:i:s') . '<br/>';
    ob_flush();
    flush();

    sleep(1);
//    file_get_contents('https://github.com/qianguyihao/Web');
}