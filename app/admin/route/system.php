<?php
use think\facade\Route;
use sdModule\makeBaseCURD\CURD;
// ========================================================
// 系统默认路由
// ========================================================

// 后台首页主题框架
Route::get('/', 'system.Index/main'); /** @see \app\admin\controller\system\Index::main() */
// 验证码
Route::get('captcha', "\\think\\captcha\\CaptchaController@index");
// 安装
Route::any('install', 'system.Install/index');/** @see \app\admin\controller\system\Install::index() */
// 辅助
Route::any('aux', 'system.System/devAux');/** @see \app\admin\controller\system\System::devAux() */
// 文件创建
Route::any('file-make', fn() => CURD::work());
// 查询的数据字段
Route::any('field', 'system.System/field');
// 查询数据字段
Route::any('field-query', 'system.System/tableFieldQuery');/** @see \app\admin\controller\system\System::tableFieldQuery() */
// 接口
Route::any('api', "system.ApiModule/index");/** @see \app\admin\controller\system\ApiModule::index() */
// 本地图片上传
Route::post('image', 'app\\SystemUpload@imageUpload');/** @see \app\SystemUpload::imageUpload() */
// 本地上传
Route::post('file-upload', 'app\\SystemUpload@fileUpload');/** @see \app\SystemUpload::fileUpload() */
// 登录
Route::rule('login', 'system.Index/login', 'GET|POST'); /** @see \app\admin\controller\system\Index::login() */
// 主页
Route::get('home', 'system.Index/home');/** @see \app\admin\controller\system\Index::home() */
// 数据权限的数据
Route::get('data-auth', 'system.Index/dataAuth');/** @see \app\admin\controller\system\Index::dataAuth() */
// 退出登陆
Route::get('login-out', 'system.Index/loginOut');/** @see \app\admin\controller\system\Index::loginOut() */
// 数据 备份
Route::get('data-back-up', 'system.System/databaseBackUp');/** @see \app\admin\controller\system\System::databaseBackUp() */