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

        .cross {
            background: #ddd;
            height: 42px;
            position: relative;
            width: 2px;
            left: 20px;
        }

        .cross:after {
            background: #ddd;
            content: "";
            height: 2px;
            left: -20px;
            position: absolute;
            top: 20px;
            width: 42px;
        }
        .cross_left:after{width: 22px;}
        .cross_right:after{width: 22px; left: 0;}
        .cross_up{height: 22px;}
        .cross_down{height: 22px;top:20px}
        .cross_down:after{top:0}
        .cross_down:before{top:-19px!important;}
        .cross_tag:before{
            background: #ddd;
            content: "";
            height: 10px;
            left: -4px;
            position: absolute;
            top: 16px;
            width: 10px;
        }
         .cross_select_black:before{
             background: #fff;
             content: "";
             height: 40px;
             left: -20px;
             position: absolute;
             z-index: 100;
             top: 1px;
             width: 40px;
             border-radius: 50%;
             box-shadow: inset 15px 15px 20px #000;
        }
        .cross_select_white:before{
             background: #fff;
             content: "";
             height: 40px;
             left: -20px;
             position: absolute;
             z-index: 100;
             top: 1px;
             width: 40px;
             border-radius: 50%;
             box-shadow: inset 5px 5px 20px #ddd;
        }
        .cross_box{
            display: inline-block;
            width: 42px;
            height: 42px;
            float: left;
        }
        .cross_box_s{
            overflow: hidden;
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
        <div style="padding: 15px;background-color: rgba(253,152,1,0.53);overflow:hidden;">

            <div class="cross_box_s"></div>

        </div>
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

    $(document).on('click', '.cross_box', function () {
        let white = $('.cross_select_white').length;
        let black = $('.cross_select_black').length;
        $(this).find('.cross').addClass((white + black) % 2 === 1 ? 'cross_select_white' : 'cross_select_black');
    })

    box_init(19, 19);

   function box_init(row, line)
   {
       let num = row * line,
           box      = "<div class=\"cross_box\"><div class=\":class\"></div></div>",
           box_html = '';
       for (let i = 1; i <= num; i++){
           let class_ = 'cross';
           if (i <= row) class_ += " cross_down";
           if (i % line === 1) class_ += " cross_right";
           if (i % line === 0) class_ += " cross_left";
           if (i > line * (row - 1)) class_ += " cross_up";
           if (i === Math.ceil(num/2)) class_ += " cross_tag";

           box_html += box.replace(':class', class_);
       }
       $('.cross_box_s').html(box_html).css({
           width:row * 42 + 'px',
           height:line * 42 + 'px',
       });
   }
</script>
</body>
</html>