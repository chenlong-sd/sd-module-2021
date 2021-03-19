<?php

use app\common\middleware\admin\{LoginMiddleware, SinglePoint, PowerAuth, Log, FormTokenVerify};

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
        Log::class
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

    // 软删除
    'soft_delete' => [
        //  字段
        'field' => 'delete_time',
        //  默认值
        'default' => 0,
        // 删除后的值，timestamp(时间戳)|mixed
        'value' => 'timestamp',
    ],
    // 表时间字段处理：type 可取  datetime | timestamp
    'time_field' => [
        'create_time' => [
            'field' => 'create_time',
            'type' => 'datetime'
        ],
        'update_time' => [
            'field' => 'update_time',
            'type' => 'datetime'
        ],
    ],

    // table list 的表格默认配置，参考layui的表格基础参数配置

    'layui_config' => [
        'skin' => 'nob',
//        'size' => 'lg',
        'even' =>  true
    ],

    // 数据权限配置
    'data_auth' => [
        ['table' => 'role', 'field' => 'role', 'remark' => '角色', ],
        ['table' => 'test', 'field' => 'title', 'remark' => '测试', ],
        ['table' => 'administrators', 'field' => 'name', 'remark' => '管理员', ],
    ],

    // 权限访问控制， 开启后由page生成的按钮，权限不够将隐藏该按钮
    'access_control' => false,
];

