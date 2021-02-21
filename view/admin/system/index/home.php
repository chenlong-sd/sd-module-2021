<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>首页</title>
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <link rel="stylesheet" href="__PUBLIC__/admin_static/layui/css/layui.css" media="all">
    <style>
        body{padding: 5px}
        .layui-row>div{padding: 5px}
        .i-sc {
            height: 45px;
            line-height: 45px;
            text-align: center;
            width: 170px;
            font-size: 15px;
            background: #efefef;
            display: inline-block;
            margin: 0 10px 10px 0;
            color: #000;
            transition: all .2s;
            box-shadow: 0 0 1px #000;
        }
        .i-sc:hover {
            cursor: pointer;
            background: #5FB878;
        }
    </style>
    {include file='predefine'/}
</head>
<body>


    <div class="layui-row">
        <div class="layui-col-md9">
            <div class="layui-card">
                <div class="layui-card-header">快捷入口</div>
                <div class="layui-card-body">
                    {foreach $route_data as $item}
                    <div class="i-sc" data-href="{:url($item.route)}">
                        {if $item.icon}<i class="layui-icon {$item.icon}"></i> {/if}{$item.title}</div>
                    {/foreach}
                </div>
            </div>
        </div>
        <div class="layui-col-md3">
            <div class="layui-card">
                <div class="layui-card-header">系统信息</div>
                <div class="layui-card-body">
                    <table class="layui-table">
                        <tr>
                            <td width="70">PHP 版本</td>
                            <td><?= phpversion() ?></td>
                        </tr>
                        <tr>
                            <td>mysql 版本</td>
                            <td><?= current(\think\facade\Db::query('SELECT VERSION() AS ver'))['ver'] ?></td>
                        </tr>
                        <tr>
                            <td>系统信息 </td>
                            <td><?= php_uname() ?></td>
                        </tr>
                        <tr>
                            <td>本机IP </td>
                            <td><?= $_SERVER['REMOTE_ADDR'] ?></td>
                        </tr>
                        <tr>
                            <td>上传限制 </td>
                            <td><?= ini_get('upload_max_filesize') ?></td>
                        </tr>
                        <tr>
                            <td>空间剩余 </td>
                            <td>
                                <?php $space = disk_free_space(".")/(1024*1024);
                                    echo $space > 1024 ? round($space / 1024, 3) . ' G' : $space . ' M'
                                ?>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>



</body>
<script type="text/javascript" src="__PUBLIC__/admin_static/layui/layui.all.js"></script>
<script type="text/javascript" src="__PUBLIC__/admin_static/js/custom.js"></script>

<script>
    let $ = layui.jquery;

    $('.i-sc').on('click', function () {
        let s = $(this).attr('data-href');
        parent.layui.jquery('a[lay-href="' + s + '"]').click()
            .parents('.layui-nav-item').addClass('layui-nav-itemed')
            .siblings('.layui-nav-itemed').removeClass('layui-nav-itemed');
    })
</script>

</html>