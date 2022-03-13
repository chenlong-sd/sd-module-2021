<?php

use app\common\middleware\admin\{Log, LoginMiddleware, PowerAuth, SinglePoint};

return [
    // 维护模式后台账号登录的规则
    'maintain_admin_rule' => [
        'account' => '/__mt$/',
        'password' => '/^__mt/'
    ],
    // 后台登录密码最大错误次数
    'max_error_password_number' => 10,
    // 缓存全部路由的键
    'route_cache' => 'route_sd_cache_2_0',
    // 后台中间件注册
    'middleware'  => [
        // 登录验证， 必须
        LoginMiddleware::class,
        // 单点登陆
        SinglePoint::class,
        // 权限验证
        PowerAuth::class,
        // 日志记录
        Log::class,
        // 表单token验证
//        FormTokenVerify::class
    ],

    // 日志写入的请求方式,开启日志有效
//    'log_write' => ['GET', 'POST'],
    'log_write' => ['POST'],

    // 表主键
    'primary_key' => 'id',
//    百度编辑器上传地址
    'editor_upload' => '',

    // 数据权限配置
    'data_auth' => [
//        ['table' => '表名', 'field' => '选取时展示字段', 'remark' => '该权限名字', ],
//        ['table' => 'test', 'show_field' => 'title', 'remark' => '测试',],

    ],

    // 开放登录后台的表设置，设置后该表账号可以登录后台
    // ====== 表必须拥有的字段：id, role_id ==== //
    // ====== 需在角色里面分配对应的角色及权限  ==== //
   'open_login_table' => [
       // 可用角色数据获取 \app\admin\model\system\Role::selectData($table)
//       'table，不含表前缀的表名' => [
//           'name'     => '账号类型名字，例：公司账号',
//           'account'  => '账号字段, 例：account',
//           // 密码字段使用 \sdModule\common\Sc::password()->encryption($password) 加密
//           'password' => '密码字段, 例：password',
//           'status'   => [
//               // '状态字段' => '允许登录的值', 不设置不限制
//               'status' => 1,
//           ],
//           'session'  => [
//               'name'
//               // 不设置不额外存数据
//               // session存储数据, example: 'filed_alias' => 'field', 'field', '字段别名' => '字段', '字段'
//               // 必带字段 name, 没有该字段请改别名为name ，例： 'name' => 'title',
//               // 系统自带 id, role_id, route, table, is_admin, 如有冲突请取别名
//           ],
//       ],
       // 例
       'user' => [
           'name'     => '用户',
           'account'  => 'account',
           'password' => 'password',
           'session'  => ['name']
       ],
   ]
];

