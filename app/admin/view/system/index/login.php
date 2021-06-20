<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

    <title>登录</title>
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport"
          content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <link rel="stylesheet" href="__PUBLIC__/admin_static/layui/css/layui.css" media="all">
    <link rel="stylesheet" href="__PUBLIC__/admin_static/css/admin.css" media="all">
    <link rel="stylesheet" href="__PUBLIC__/admin_static/css/login.css" media="all">
    {:token_meta()}
    {include file='predefine'/}
    <style>
        .layadmin-user-login-codeimg {
            width: 100%;
            height: 100%;
        }
    </style>
</head>
<body>

<div class="layadmin-user-login layadmin-user-display-show" id="LAY-user-login" style="display: none;">

    <div class="layadmin-user-login-main">
        <div class="layadmin-user-login-box layadmin-user-login-header">
            <h2><?= env('company') ?: 'sd-module' ?></h2>
        </div>
        <div class="layadmin-user-login-box layadmin-user-login-body layui-form">
            <div class="layui-form-item">
                <label class="layadmin-user-login-icon layui-icon layui-icon-username"
                       for="LAY-user-login-username"></label>
                <input type="text" name="account" id="LAY-user-login-username" lay-verify="required"
                       placeholder="{:lang('administrator.account')}"
                       class="layui-input">
            </div>
            <div class="layui-form-item">
                <label class="layadmin-user-login-icon layui-icon layui-icon-password"
                       for="LAY-user-login-password"></label>
                <input type="password" name="password" id="LAY-user-login-password" lay-verify="required"
                       placeholder="{:lang('administrator.password')}" class="layui-input">
            </div>
            <div class="layui-form-item">
                <div class="layui-row">
                    <div class="layui-col-xs7">
                        <label class="layadmin-user-login-icon layui-icon layui-icon-vercode"
                               for="LAY-user-login-vercode"></label>
                        <input type="text" name="captcha" id="LAY-user-login-vercode" lay-verify="required" autocomplete="off"
                               placeholder="{:lang('administrator.captcha')}" class="layui-input">
                    </div>
                    <div class="layui-col-xs5">
                        <div style="margin-left: 10px;height: 38px;padding: 1px 0;box-sizing: border-box">
                            <img src="{:admin_url('captcha')}"
                                 onclick="this.src='{:admin_url(\'captcha\')}?' + Math.random()"
                                 class="layadmin-user-login-codeimg"
                                 id="LAY-user-get-vercode">
                        </div>
                    </div>
                </div>
            </div>
            <div class="layui-form-item" style="margin-bottom: 20px;">
                <hr>
            </div>
            <div class="layui-form-item">
                <button class="layui-btn layui-btn-fluid" lay-submit="" lay-filter="LAY-user-login-submit">{:lang('sign in')}</button>
            </div>
        </div>
    </div>

    <div class="layui-trans layadmin-user-login-footer">
<!--        <a href="https://www.layui.com/" target="_blank">layui.com</a>-->
    </div>

</div>

<script src="__PUBLIC__/admin_static/layui/layui.js"></script>
<script type="text/javascript" src="__PUBLIC__/admin_static/js/custom.js"></script>

<script>
    console.log(window.location);
    let tip = "{$Request.get.tip}";
    if (self !== top) {
        top.window.location.href = window.location.href;
    } else {
        if (tip) {
            layer.alert(tip, function () {
                location.href = window.location.origin + window.location.pathname
            });
        }
    }

    document.onkeyup = (e) => {
        if (e.key === 'Enter') {
            if(tip) return location.href = window.location.origin + window.location.pathname;

            let s = document.getElementsByTagName('button');
            for (let i = 0; i < s.length; i++) {
                if (s[i].hasAttribute('lay-filter') && s[i].getAttribute('lay-filter') === 'LAY-user-login-submit') {
                    s[i].click();
                    break;
                }
            }
        }
    };


    layui.config({
        base: '__PUBLIC__/admin_static/' //静态资源所在路径
    }).extend({
        index: 'lib/index' //主入口模块
    }).use(['index', 'form'], function () {
        var $ = layui.$, form = layui.form;

        form.render();

        //提交
        form.on('submit(LAY-user-login-submit)', function (obj) {
            let load = custom.loading('{:lang("login load")}');
            $.ajax({
                type: 'post'
                , data: obj.field
                , headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
                , success: function (res) {
                    layer.close(load);
                    if (res.code === 200) {
                        layer.msg('{:lang("login success")}', {icon: 1}, function () {
                            location.href = "{:admin_url()}"
                        });
                    } else if (res.code === 203) {
                        layer.alert(res.msg, {icon: 0, anim: 6}, function () {
                            location.reload();
                        });
                    } else {
                        layer.msg(res.msg, {icon: 2, anim: 6});
                        document.getElementById('LAY-user-get-vercode').click();
                        $('#LAY-user-login-vercode').val('').focus();
                    }
                }, error: function (err) {
                    layer.close(load);
                    layer.msg('{:lang("Access error")}');
                }
            });


            return false;
        });
    });
</script>

<style id="LAY_layadmin_theme">.layui-side-menu, .layadmin-pagetabs .layui-tab-title li:after, .layadmin-pagetabs .layui-tab-title li.layui-this:after, .layui-layer-admin .layui-layer-title, .layadmin-side-shrink .layui-side-menu .layui-nav > .layui-nav-item > .layui-nav-child {
    background-color: #20222A !important;
}

.layui-nav-tree .layui-this, .layui-nav-tree .layui-this > a, .layui-nav-tree .layui-nav-child dd.layui-this, .layui-nav-tree .layui-nav-child dd.layui-this a {
    background-color: #009688 !important;
}

.layui-layout-admin .layui-logo {
    background-color: #20222A !important;
}</style>
<div class="layui-layer-move"></div>
</body>
</html>