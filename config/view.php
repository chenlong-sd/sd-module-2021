<?php
// +----------------------------------------------------------------------
// | 模板设置
// +----------------------------------------------------------------------

return [
    // 模板引擎类型使用Think
    'type'         => 'Think',
    // 默认模板渲染规则 1 解析为小写+下划线 2 全部转换小写 3 保持操作方法
    'auto_rule'    => 1,
    // 模板基础路径
    'view_base'    => '',
    // 模板路径
    'view_path'    => '',
    // 模板后缀
    'view_suffix'  => 'php',
    // 模板文件名分隔符
    'view_depr'    => DIRECTORY_SEPARATOR,
    // 模板引擎普通标签开始标记
    'tpl_begin'    => '{',
    // 模板引擎普通标签结束标记
    'tpl_end'      => '}',
    // 标签库标签开始标记
    'taglib_begin' => '{',
    // 标签库标签结束标记
    'taglib_end'   => '}',
    // 是否开启模板缓存
    'tpl_cache' => false,
    // 输出替换
    'tpl_replace_string'  =>  [
        '__PUBLIC__'=> strtr(\think\facade\Request::root(), ['index.php' => '']),
        '__JS__' => '/static/javascript',
    ]
];
