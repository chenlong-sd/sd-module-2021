<?php
// ==========================================
// 系统内置路由
// ==========================================

use think\facade\Route;

// ----------- 自助获取表信息 --------------

// 获取表信息
Route::get('sc-table', 'Common/getTable'); /** @see \app\api\controller\Common::getTable() */
// 获取字段信息
Route::get('sc-field-info', 'Common/getFieldInfo'); /** @see \app\api\controller\Common::getFieldInfo() */
// 获取数据
Route::get('sc-data', 'Common/getData'); /** @see \app\api\controller\Common::getData() */

