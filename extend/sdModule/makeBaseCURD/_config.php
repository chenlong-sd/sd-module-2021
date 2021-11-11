<?php
// ============================================
// 基本配置文件
// time 2020-09-14 22:28
// ============================================

$rootPath = \think\facade\App::getRootPath();
return [
    // 网络根路径
    'root_path' => strtr(dirname($_SERVER['SCRIPT_NAME']), ['\\' => '/']) ,
    // layui的文件路径
    'layui_dir' => strtr(strtr(dirname($_SERVER['SCRIPT_NAME']) .  '/admin_static/layui/', ['\\' => '']), ['//' => '/']),

    // 搜索是否需要label
    "list_search_label" => true,

    // form 表单模块
    'form_module' => [
        ['value' => 'text',     'class' => '',  'title' => '文本框',   ],
        ['value' => 'password', 'class' => '',  'title' => '密码框',   ],
        ['value' => 'select',   'class' => '',  'title' => '下拉选择',  ],
        ['value' => 'selects',  'class' => '',  'title' => '下拉多项选择',],
        ['value' => 'radio',    'class' => '',  'title' => '单选',    ],
        ['value' => 'checkbox', 'class' => '',  'title' => '多选',    ],
        ['value' => 'image',    'class' => '',  'title' => '单图',    ],
        ['value' => 'images',   'class' => '',  'title' => '多图',    ],
        ['value' => 'editor',   'class' => '',  'title' => '富文本',   ],
        ['value' => 'textarea', 'class' => '',  'title' => '文本域',   ],
        ['value' => 'tag',      'class' => '',  'title' => '标签输入',   ],
        ['value' => 'time',     'class' => '',  'title' => '时间选择',  ],
        ['value' => 'month',    'class' => '',  'title' => '月份选择',  ],
        ['value' => 'date',     'class' => '',  'title' => '日期选择',  ],
        ['value' => 'range',    'class' => '',  'title' => '日期范围选择',],
    ],

    // 创建项目
    'make_item' => [
        ['tag' => 1,    'title' => 'controller'],
        ['tag' => 2,    'title' => 'model'],
        ['tag' => 4,    'title' => 'validate'],
        ['tag' => 8,    'title' => 'page'],
        ['tag' => 16,   'title' => 'service'],
    ],

    // 文件路径，键对应创建项目的 tag
    'file_path' => [
        1   => $rootPath . '/app/admin/controller/{:class}.php',
        2   => [
            'common'    => $rootPath . '/app/common/model/{:class}.php',
            'admin'     => $rootPath . '/app/admin/model/{:class}.php'
        ],
        4   => $rootPath . '/app/common/validate/{:class}.php',
        8   => $rootPath . '/app/admin/page/{:class}Page.php',
        16  => $rootPath . '/app/admin/service/{:class}Service.php',

        'enum'  => $rootPath . '/app/common/enum/{:class}.php',

        // 测试路径
//        1   => __DIR__ . '/cache/admin/controller/{:class}.php',
//        2   => [
//            'common'    => __DIR__ . '/cache/common/model/{:class}.php',
//            'admin'     => __DIR__ . '/cache/admin/model/{:class}.php'
//        ],
//        4   => __DIR__ . '/cache/common/validate/{:class}.php',
    ],
    // 模板文件路径
    'template' => [
        'controller'    => __DIR__ . '/template/controller.php',
        'common_model'  => __DIR__ . '/template/common_model.php',
        'model'         => __DIR__ . '/template/model.php',
        'validate'      => __DIR__ . '/template/validate.php',
        'page'          => __DIR__ . '/template/page.php',
        'service'       => __DIR__ . '/template/service.php',
        'enum'       => __DIR__ . '/template/enum.php',

    ],
    // 类的命名空间
    'namespace' => [
        // controller 命名空间
        'controller'    => 'app\\admin\\controller',
        // model 命名空间
        'model'         => 'app\\admin\\model',
        // common/model 命名空间
        'common_model'  => 'app\\common\\model',
        // validate 命名空间
        'validate'      => 'app\\common\\validate',
        // page 命名空间
        'page'          => 'app\\admin\\page',
        // service
        'service'       => 'app\\admin\\service',
        // enum
        'enum'          => 'app\\common\\enum',
    ],
];

