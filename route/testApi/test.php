<?php


\think\facade\Route::group('ss', function () {
    \think\facade\Route::get('testss', 'index/testss');

//    ===============================route end=================================   //
})->middleware(\app\common\middleware\TestMiddleware::class);