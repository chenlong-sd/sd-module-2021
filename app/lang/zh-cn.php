<?php
// ==================================================================
// 中文语言
// ==================================================================

$module = lang_load(__DIR__, basename(__FILE__));

$base = [

//    TOKEN 提示
    'token Expired' => '登录已过期，请重新登录',
    'token error' => 'TOKEN 数据验证失败！错误码：',
    'success' => '请求成功！',
    'fail' => '请求失败！',
    ''
];

return array_merge($base, $module);
