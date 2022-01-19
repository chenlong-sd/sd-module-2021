<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{:env('company', '')}后台管理</title>
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <link rel="stylesheet" href="__PUBLIC__/admin_static/layui/css/layui.css" media="all">
    <link rel="stylesheet" href="__PUBLIC__/admin_static/css/admin.css" media="all">
    <style>
        a:hover{
            cursor: pointer;
        }
    </style>
    {include file='predefine'/}
</head>
<?php
/**
 * 菜单html渲染
 * @param array $menu
 * @param bool $is_children
 * @return string
 */
function menu_render(array $menu, bool $is_children = false): string
{
    $menu_str = $is_children ? '<dl class="layui-nav-child">' : '' ;
    foreach ($menu as $item){
        // 弹出的路由信息
        $href = !empty($item['route']) && empty($item['children']) ? "lay-href=\"" . url($item['route']) . "\"" : '';
        // 子菜单html
        $children = empty($item['children']) ? '' : menu_render($item['children'], true);

        if ($is_children) {
            $html           = '<dd data-name="console" class="">%s</dd>';
            $content_html   = '<a %s>%s</a>%s';
            $content        = sprintf($content_html, $href, $item['title'], $children);
        }else{
            $html           = '<li data-name="home" class="layui-nav-item">%s</li>';

            $content_html   = '
                    <a href="javascript:;" %s lay-tips="%s" lay-direction="2"> 
                        <i class="layui-icon %s"></i>
                        <cite>%2$s</cite>
                    </a>%s';
            $content        = sprintf($content_html, $href, $item['title'], $item['icon'], $children);
        }

        $menu_str .= sprintf($html, $content);
    }

    return $is_children ? $menu_str . '</dl>' : $menu_str;
}
?>



<body class="layui-layout-body">
<div id="LAY_app">
    <div class="layui-layout layui-layout-admin">
        <div class="layui-header">
            <!-- 头部区域 -->
            <ul class="layui-nav layui-layout-left">


                <li class="layui-nav-item layadmin-flexible" lay-unselect>
                    <a href="javascript:;" layadmin-event="flexible" title="侧边伸缩">
                        <i class="layui-icon layui-icon-shrink-right" id="LAY_app_flexible"></i>
                    </a>
                </li>

                <li class="layui-nav-item layui-hide-xs" lay-unselect>
                    <a href="" target="_blank" title="前台">
                        <i class="layui-icon layui-icon-website"></i>
                    </a>
                </li>


                <li class="layui-nav-item" lay-unselect>
                    <a href="javascript:;" layadmin-event="refresh" title="刷新">
                        <i class="layui-icon layui-icon-refresh-3"></i>
                    </a>
                </li>


            </ul>
            <ul class="layui-nav layui-layout-right" lay-filter="layadmin-layout-right">
                <li class="layui-nav-item layui-hide-xs" lay-unselect>
                    <a href="javascript:;" layadmin-event="note">
                        <i class="layui-icon layui-icon-note"></i>
                    </a>
                </li>
                <li class="layui-nav-item layui-hide-xs"  id="small-part" lay-unselect>
                    <a href="javascript:;">
                        <i class="layui-icon layui-icon-component"></i> 小部件
                    </a>
                    <dl class="layui-nav-child">
                        <dd>
                            <a href="javascript:;" lay-event="surprised">休息一下</a>
                        </dd>
                        <dd>
                            <a href="javascript:;" layadmin-event="theme">皮肤设置</a>
                        </dd>
                        <?php  if (env('APP_DEBUG') && \app\admin\service\system\AdministratorsService::isSuper()) { ?>
                        <dd lay-href="<?= admin_url('data-back-up') ?>">
                            <a href="javascript:;">数据备份</a>
                        </dd>
                        <dd lay-href="<?= admin_url('api') ?>">
                            <a href="javascript:;" >接口管理</a>
                        </dd>
                        <dd lay-href="<?= admin_url('aux') ?>">
                            <a href="javascript:;" >开发工具</a>
                        </dd>
                        <dd>
                            <a target="_blank" href="https://www.kancloud.cn/chenlon-sd/sd-module-2021" >开发手册</a>
                        </dd>
                        <?php } ?>
                    </dl>
                </li>


                <li class="layui-nav-item" lay-unselect>
                    <a href="javascript:;">
                        <cite><?= \app\admin\AdminLoginSession::getName('') ?></cite>
                    </a>
                    <dl class="layui-nav-child">
                        <!--                        <dd><a lay-event="defend">基本资料</a></dd>-->
                        <dd><a lay-event="pwd">修改密码</a></dd>
                        <hr>
                        <dd style="text-align: center;">
                            <a href="<?= admin_url('login-out') ?>">退出</a>
                        </dd>
                    </dl>
                </li>

                <!-- 移动端显示-->
                <li class="layui-nav-item layui-show-xs-inline-block layui-hide-sm" lay-unselect>
                    <a href="javascript:;" layadmin-event="theme"><i class="layui-icon layui-icon-more-vertical"></i></a>
                </li>


            </ul>
        </div>
        <!-- 侧边菜单 -->
        <div class="layui-side layui-side-menu">
            <div class="layui-side-scroll">
                <div class="layui-logo" >
                    <span>{:env('company', '')}</span>
                </div>
                <ul class="layui-nav layui-nav-tree" lay-shrink="all" id="LAY-system-side-menu" lay-filter="layadmin-system-side-menu">
                    <li data-name="home" class="layui-nav-item">
                        <a href="javascript:;" lay-href="{:admin_url('home')}" lay-tips="主页" lay-direction="2">
                            <i class="layui-icon layui-icon-home"></i>
                            <cite>{:lang("home")}</cite>
                        </a>
                    </li>
                    <?= menu_render($menu) ?>
                </ul>
            </div>
        </div>
        <!-- 页面标签 -->


        <div class="layadmin-pagetabs" id="LAY_app_tabs">
            <div class="layui-icon layadmin-tabs-control layui-icon-prev" layadmin-event="leftPage"></div>
            <div class="layui-icon layadmin-tabs-control layui-icon-next" layadmin-event="rightPage"></div>
            <div class="layui-icon layadmin-tabs-control layui-icon-down">
                <ul class="layui-nav layadmin-tabs-select" lay-filter="layadmin-pagetabs-nav">
                    <li class="layui-nav-item" lay-unselect>
                        <a href="javascript:;"></a>
                        <dl class="layui-nav-child layui-anim-fadein">
                            <dd layadmin-event="closeThisTabs"><a href="javascript:;">关闭当前标签页</a></dd>
                            <dd layadmin-event="closeOtherTabs"><a href="javascript:;">关闭其它标签页</a></dd>
                            <dd layadmin-event="closeAllTabs"><a href="javascript:;">关闭全部标签页</a></dd>
                        </dl>
                    </li>
                </ul>
            </div>

            <!--默认无效-->
            <div class="layui-tab" lay-unauto lay-allowClose="true" lay-filter="layadmin-layout-tabs">
                <ul class="layui-tab-title" id="LAY_app_tabsheader">
                    <li lay-id="{:admin_url('home')}" class="layui-this"><i class="layui-icon layui-icon-home"></i></li>
                </ul>
            </div>


        </div>


        <!-- 主体内容 -->
        <div class="layui-body" id="LAY_app_body">
            <div class="layadmin-tabsbody-item layui-show">
                <iframe src="{:admin_url('home')}" frameborder="0" class="layadmin-iframe"></iframe>
            </div>
        </div>

        <div class="layadmin-body-shade" layadmin-event="shade"></div>
    </div>
</div>

<script src="__PUBLIC__/admin_static/layui/layui.js"></script>
<script src="__PUBLIC__/admin_static/js/custom.js"></script>
<script>
    layui.config({
        base: '__PUBLIC__/admin_static/' //静态资源所在路径
    }).extend({
        index: 'lib/index', //主入口模块
        notice:'layui/dist/notice'
    }).use('index');


    layui.use('notice',function () {
        window.layNotice = layui.notice;
    });

    let util = layui.util,layer = layui.layer,dropdown = layui.dropdown;
    layer.config({
        extend: 'black/style.css'
        ,skin:'demo-class'
    });
    util.event('lay-event', {
        'pwd':()=>{
            custom.frame("{:url('system.administrators/passwordUpdate')}", '修改密码', {area:['600px', "400px"]})
        },
        'defend':()=>{
            custom.frame("{:url('system.administrators/defend')}", '修改资料')
        },
        'surprised':()=>{
            custom.frame("{:url('system.index/game')}", '休息一下', {area:['880px', "890px"]})
        }
    });
    // tab 双击创建弹窗并关闭 tab,以及拉取
    let DY,DO = false,TMP_LAYER;
    layui.jquery(document).on('dblclick', '#LAY_app_tabsheader>li', function () {
        custom.frame(layui.jquery(this).attr('lay-id'), layui.jquery(this).find('span').text());
        layui.jquery(this).find('.layui-tab-close').click();
    }).on('dragstart', '#LAY_app_tabsheader>li', function (e) {
        DY = e.screenY;
    }).on('dragend', '#LAY_app_tabsheader>li', function (e) {
        if (DO){
            console.log(e.screenY - DY);
            e.screenY - DY < 50 ? layer.close(TMP_LAYER) : layui.jquery(this).find('.layui-tab-close').click();
        }
        DO = false;
    }).on('dragleave', '#LAY_app_tabsheader>li', function (e) {
        if (!DO) {
            TMP_LAYER = custom.frame(layui.jquery(this).attr('lay-id'), layui.jquery(this).find('span').text());
        }
        DO = true;
    });


</script>
</body>
</html>


