<?php
// ==================================================================
// 中文语言
// ==================================================================
\think\facade\Lang::load(__DIR__ . '/en.php', 'en');
$module = lang_load(__DIR__, basename(__FILE__));

$base = [

    // 公用
    'normal' => '正常',
    'disable' => '禁用',
    'delete' => '删除',
    'batch deletion' => '批量删除',
    'edit' => '编辑',
    'add' => '新增',
    'search' => '搜索',
    'confirm' => '确认',
    'clear' => '清空',
    'cancel' => '取消',
    'more search' => '更多搜索',
    'page_to' => '到第',
    'page_page' => '页',
    'page_total' => '共 1 条',
    'page_article' => '条/页',
    'confirm delete' => '确认删除吗',
    'warning' => '警告',
    'success' => '成功',
    'fail' => '失败',
    'modify' => '修改',
    'operating' => '操作',
    'backstage' => '后台',
    'Sign in' => '登 入',
    'management' => '管理',
    'Filter column' => '筛选列',
    'Export' => '导出',
    'print' => '打印',
    'reset' => '重置',
    'submit' => '立即提交',
    'close' => '关闭',
    'expand all' => '全部展开',
    'collapse all' => '全部折叠',
    'please enter' => '请输入',
    'login load' => '努力登录中，请稍候...',
    'login success' => '登录成功,页面跳转中，请稍候...',
    'Access error' => '访问错误',
    'Select Image' => '选择图片',
    'System picture' => '系统图片',
    'preview' => '预览',
    'List data' => '列表数据',
    'List' => '列表',
    'information' => '信息',
    'create_time' => '创建时间',
    'update_time' => '修改时间',
    'delete_time' => '删除时间',
    'Confirm this operation' => '确认此次操作？',
    'loading' => '操作可能需要一些时间，请稍候……',
    'or' => '或',
    'No access' => '无访问权限',
    'login single point' => '账号在其他地方登录，你被强制下线！如不是本人操作，请尽快更改密码！',
    'maintain s' => '系统维护中',
    'home' => '主页',
    'modify password' => '修改密码',
    'sign out' => '退出登录',
    'no' => '暂无',
    'Resource browsing' => '资源浏览',
    'Operation is undefined' => '操作未定义',
    'press enter after typing' => '输入后按回车键',
    'failed to delete' => '删除失败',
    'please select data' => '请选择数据',





    'layui upload_exception' => '请求上传接口出现异常',
    'layui upload_exception_1' => '获取上传后的响应信息出现异常',
    'layui upload_exception_json' => '请对上传接口返回有效JSON',
    'layui file_format_error' => '选择的文件中包含不支持的格式',
    'layui video_format_error' => '选择的视频中包含不支持的格式',
    'layui audio_format_error' => '选择的音频中包含不支持的格式',
    'layui image_format_error' => '选择的图片中包含不支持的格式',
    'layui max_upload' => '同时最多只能上传的数量为',
    'layui file_exceed' => '文件不能超过',
    'layui file_a' => '个文件',
    'layui shrink' => '收缩',
    'layui require' => '必填项不能为空',
    'layui phone' => '请输入正确的手机号',
    'layui email' => '邮箱格式不正确',
    'layui link' => '链接格式不正确',
    'layui number' => '只能填写数字',
    'layui date' => '日期格式不正确',
    'layui id_card' => '请输入正确的身份证号',
    'layui select' => '请选择',
    'layui unnamed' => '未命名',
    'layui no data' => '无数据',
    'layui No matching data' => '无匹配数据',
    'layui require exception' => '数据接口请求异常',
    'layui response error' => '返回的数据不符合规范，正确的成功状态码应为',
    'layui upload error' => '上传失败！',

//    TOKEN 提示
    'token Expired' => '登录已过期，请重新登录',
    'token error' => 'TOKEN 数据验证失败！错误码：',


];

return array_merge($base, $module);
