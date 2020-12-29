<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>layout 后台大布局 - Layui</title>
    <link rel="stylesheet" href="__PUBLIC__/admin_static/layui/css/layui.css">
    <style>
        .layui-icon{
            font-size: 14px!important;
            margin-left: 10px;
        }
        .sc-tab {
            line-height: 35px;
            overflow: hidden;
            position: relative;
            border-bottom: 1px solid #ddd;
            height: 35px;
        }
        .sc-tab-title {
            position: absolute;
            top:0;
            width: max-content;
            background-color: white;
            z-index: 998;
            transition: left .1s;
        }
        .sc-tab .sc-tab-title-item, .sc-tab .sc-tab-left, .sc-tab .sc-tab-right{
            padding: 0 12px;
            display: inline-block;
            box-sizing: border-box;
            color: grey;
            float: left;
            height: 35px;
            cursor: pointer;
        }
        .sc-tab .sc-tab-title-item.sc-tab-select{
            background-color: #f2f2f2;
            color: #23262E;
            border-bottom: 2px solid #23262E;
        }

        .sc-tab .sc-tab-left{
            position: absolute;
            left: 0;
            float: none;
            padding: 0;
            background-color: white;
            z-index: 999;
        }
        .sc-tab .sc-tab-right{
            position: absolute;
            right: 0;
            float: none;
            padding: 0;
            background-color: white;
            z-index: 999;
        }
        .sc-home{
            padding: 0 10px;
            display: inline-block;
        }
        .sc-home:last-child{
            padding: 0 10px 0 0;
        }
        .sc-page{
            padding: 0;
            width: 30px;
            display: inline-block;
        }
        .sc-tab .sc-tab-left span:hover,.sc-tab .sc-tab-right span:hover{
            background-color: #f2f2f2;
        }


    </style>
    {include file='predefine'}
</head>
<body class="layui-layout-body">
<div class="layui-layout layui-layout-admin">
    <div class="layui-header">
        <div class="layui-logo">layui 后台布局</div>
        <!-- 头部区域（可配合layui已有的水平导航） -->
        <ul class="layui-nav layui-layout-left">
            <li class="layui-nav-item"><a href="">控制台</a></li>
            <li class="layui-nav-item"><a href="">商品管理</a></li>
            <li class="layui-nav-item"><a href="">用户</a></li>
            <li class="layui-nav-item">
                <a href="javascript:;">其它系统</a>
                <dl class="layui-nav-child">
                    <dd><a href="">邮件管理</a></dd>
                    <dd><a href="">消息管理</a></dd>
                    <dd><a href="">授权管理</a></dd>
                </dl>
            </li>
        </ul>
        <ul class="layui-nav layui-layout-right">
            <li class="layui-nav-item">
                <a href="javascript:;">
                    <img src="http://t.cn/RCzsdCq" class="layui-nav-img">
                    贤心
                </a>
                <dl class="layui-nav-child">
                    <dd><a href="">基本资料</a></dd>
                    <dd><a href="">安全设置</a></dd>
                </dl>
            </li>
            <li class="layui-nav-item"><a href="">退了</a></li>
        </ul>
    </div>

    <div class="layui-side layui-bg-black">
        <div class="layui-side-scroll">
            <!-- 左侧导航区域（可配合layui已有的垂直导航） -->
            <ul class="layui-nav layui-nav-tree"  lay-filter="test">
                <li class="layui-nav-item layui-nav-itemed">
                    <a class="" href="javascript:;">所有商品</a>
                    <dl class="layui-nav-child">
                        <dd><a href="javascript:;">列表一</a></dd>
                        <dd><a href="javascript:;">列表二</a></dd>
                        <dd><a href="javascript:;">列表三</a></dd>
                        <dd><a href="">超链接</a></dd>
                    </dl>
                </li>
                <li class="layui-nav-item">
                    <a href="javascript:;">解决方案</a>
                    <dl class="layui-nav-child">
                        <dd><a href="javascript:;">列表一</a></dd>
                        <dd><a href="javascript:;">列表二</a></dd>
                        <dd><a href="">超链接</a></dd>
                    </dl>
                </li>
                <li class="layui-nav-item"><a href="">云市场</a></li>
                <li class="layui-nav-item"><a href="">发布商品</a></li>
            </ul>
        </div>
    </div>

    <div class="layui-body">
        <div class="sc-tab" >
            <div class="sc-tab-left">
                <span class="sc-page"><i class="layui-icon layui-icon-prev"></i></span>
                <span class="sc-home">主页</span>
            </div>
            <div class="sc-tab-title">
                <div class="sc-tab-title-item">内容主体区域1<i class="layui-icon layui-icon-close"></i></div>
                <div class="sc-tab-title-item sc-tab-select">内容主体区域2<i class="layui-icon layui-icon-close"></i></div>
                <div class="sc-tab-title-item">内容主体区域3<i class="layui-icon layui-icon-close"></i></div>
                <div class="sc-tab-title-item">内容主体区域4<i class="layui-icon layui-icon-close"></i></div>
                <div class="sc-tab-title-item">内容主体区域5<i class="layui-icon layui-icon-close"></i></div>
                <div class="sc-tab-title-item">内容主体区域6<i class="layui-icon layui-icon-close"></i></div>
                <div class="sc-tab-title-item">内容主体区域7<i class="layui-icon layui-icon-close"></i></div>
                <div class="sc-tab-title-item">内容主体区域8<i class="layui-icon layui-icon-close"></i></div>
                <div class="sc-tab-title-item">内容主体区域9<i class="layui-icon layui-icon-close"></i></div>
                <div class="sc-tab-title-item">内容主体区域10<i class="layui-icon layui-icon-close"></i></div>
                <div class="sc-tab-title-item">内容主体区域11<i class="layui-icon layui-icon-close"></i></div>
                <div class="sc-tab-title-item">内容主体区域12<i class="layui-icon layui-icon-close"></i></div>
            </div>
            <div class="sc-tab-right">
                <span class="sc-page"><i class="layui-icon layui-icon-next"></i></span>
                <span class="sc-home"><i class="layui-icon layui-icon-down"></i></span>
            </div>
        </div>
        <!-- 内容主体区域 -->
        <div style="padding: 15px;">内容主体区域</div>
    </div>

    <div class="layui-footer">
        <!-- 底部固定区域 -->
        © layui.com - 底部固定区域
    </div>
</div>
<script src="__PUBLIC__/admin_static/layui/layui.all.js"></script>
<script>
    var $ = layui.jquery,init_left = $('.sc-tab-left').css('width')
        ,init_right = $('.sc-tab-right').css('width'),
        all_width   = $('.sc-tab').css('width'),
        title_width   = $('.sc-tab>.sc-tab-title').css('width'),
        change      = parseInt(all_width) - parseInt(init_left) - parseInt(init_right) - 200;
    $('.sc-tab>.sc-tab-title').css({
        left:init_left
    })

    $('.sc-tab .sc-tab-title-item:not(.sc-tab-select)').hover(function () {
        $(this).addClass('sc-tab-select');
    },function (){
        $(this).removeClass('sc-tab-select');
    });
    $('.sc-tab-right .sc-page').on('click', function () {
        let cur = parseInt($('.sc-tab>.sc-tab-title').css('left'));
        let change_ = cur - change;
        $('.sc-tab>.sc-tab-title').css({
            left: (change_ < -parseInt(title_width) ? cur : change_)  + 'px'
        })
    });
    $('.sc-tab-left .sc-page').on('click', function () {
        let cur = parseInt($('.sc-tab>.sc-tab-title').css('left'));
        let change_ = cur + change;
        $('.sc-tab>.sc-tab-title').css({
            left:(change_ > parseInt(init_left) ? parseInt(init_left) : change_) + 'px'
        })
    });
</script>
</body>
</html>