<?php
// 全局中间件定义文件
return [
    // 全局请求缓存
//     \think\middleware\CheckRequestCache::class,
    // 多语言加载
     \think\middleware\LoadLangPack::class,
    // Session初始化
     \think\middleware\SessionInit::class,
    // 系统维护
    \app\common\middleware\MaintainMiddleware::class,
    // 跨域设置
    \app\common\middleware\CrossDomain::class,
    \app\common\middleware\Auth::class,
//    安装
//   \app\common\middleware\Install::class,
];
