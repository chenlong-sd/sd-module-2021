<?php

\think\facade\Route::group('', function () {
//    \think\facade\Route::any('test', 'index/test');

//    ===============================route end=================================   //
});

\think\facade\Route::get('xt', 'index/xt')->middleware(\app\middleware\RequestListen::class, []);

\think\facade\Route::post('up', function () {
    return (new \app\SystemUpload())->base64ImageUpload();
});