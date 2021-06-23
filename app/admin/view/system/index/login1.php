<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>登录</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta http-equiv="Access-Control-Allow-Origin" content="*">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="format-detection" content="telephone=no">
    <link rel="stylesheet" href="__PUBLIC__/admin_static/layui/css/layui.css" media="all">
    <!--[if lt IE 9]>

    <![endif]-->
    <style>
        html, body {width: 100%;height: 100%;overflow: hidden}
        body {background: #1E9FFF;}
        body:after {content:'';background-repeat:no-repeat;background-size:cover;-webkit-filter:blur(3px);-moz-filter:blur(3px);-o-filter:blur(3px);-ms-filter:blur(3px);filter:blur(3px);position:absolute;top:0;left:0;right:0;bottom:0;z-index:-1;}
        .layui-container {width: 100%;height: 100%;overflow: hidden}
        .admin-login-background {width:360px;height:300px;position:absolute;left:50%;top:30%;margin-left:-180px;margin-top:-100px;}
        .logo-title {text-align:center;letter-spacing:2px;padding:14px 0;}
        .logo-title h1 {color:#1E9FFF;font-size:25px;font-weight:bold;}
        .login-form {background-color:#fff;border:1px solid #fff;border-radius:3px;padding:14px 20px;box-shadow:0 0 8px #eeeeee;}
        .login-form .layui-form-item {position:relative;}
        .login-form .layui-form-item label {position:absolute;left:1px;top:1px;width:38px;line-height:36px;text-align:center;color:#d2d2d2;}
        .login-form .layui-form-item input {padding-left:36px;}
        .captcha {width:60%;display:inline-block;}
        .captcha-img {display:inline-block;width:34%;float:right;}
        .captcha-img img {height:34px;border:1px solid #e6e6e6;height:36px;width:100%;}
    </style>
    {include file='predefine'/}
<body>
<div class="layui-container">
    <canvas class="pg-canvas" width="1890" height="937"></canvas>
    <div class="admin-login-background">
        <div class="layui-form login-form">
            <div class="layui-form" action="">
                <div class="layui-form-item logo-title">
                    <h1><?= env('company') ?: 'sd-module' ?></h1>
                </div>
                <div class="layui-form-item">
                    <label class="layui-icon layui-icon-username" for="username"></label>
                    <input type="text" name="account" lay-verify="required" placeholder="{:lang('administrator.account')}" autocomplete="off" class="layui-input" value="<?= env('APP_DEBUG') ? 'admin' : '' ?>">
                </div>
                <div class="layui-form-item">
                    <label class="layui-icon layui-icon-password" for="password"></label>
                    <input type="password" name="password" lay-verify="required" placeholder="{:lang('administrator.password')}" autocomplete="off" class="layui-input" value="<?= env('APP_DEBUG') ? '123456' : '' ?>">
                </div>
                <div class="layui-form-item">
                    <label class="layui-icon layui-icon-vercode" for="captcha"></label>
                    <input type="text" name="captcha" id="LAY-user-login-vercode" lay-verify="required" placeholder="{:lang('administrator.captcha')}" autocomplete="off" class="layui-input verification captcha" value="">
                    <div class="captcha-img">
                		<img  src="{:admin_url('captcha')}" onclick="this.src='{:admin_url(\'captcha\')}?' + Math.random()" id="LAY-user-get-vercode">
                    </div>
                </div>
                <div class="layui-form-item">
                    <input type="checkbox" name="remember" value="1" lay-skin="primary" title="记住密码">
                    <div class="layui-unselect layui-form-checkbox" lay-skin="primary">
                        <span>记住密码</span><i class="layui-icon layui-icon-ok"></i>
                    </div>
                </div>
                <div class="layui-form-item">
                    <button class="layui-btn layui-btn layui-btn-normal layui-btn-fluid" lay-submit="" lay-filter="LAY-user-login-submit">{:lang('sign in')}</button>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="__PUBLIC__/admin_static/layui/layui.js" charset="utf-8"></script>
<script src="__PUBLIC__/admin_static/js/custom.js" charset="utf-8"></script>
<script>
    let jQuery = $ = layui.jquery;
</script>
<script src="__PUBLIC__/admin_static/js/jquery.particleground.min.js" charset="utf-8"></script>
<script>

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
    // 粒子线条背景
    $(document).ready(function(){
        $('.layui-container').particleground({
            dotColor:'#7ec7fd',
            lineColor:'#7ec7fd'
        });
    });

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

    if (localStorage.getItem('login_account')) {
        $('input[name=account]').val(localStorage.getItem('login_account'));
    }
    if (localStorage.getItem('login_pwd')) {
        let pwd = localStorage.getItem('login_pwd');
        pwd.padEnd(pwd.length + (pwd.length % 4), '=');
        pwd = window.atob(pwd);
        $('input[name=password]').val(pwd);
        $('input[name=remember]').prop('checked', 'checked');
    }

    layui.form.on('submit(LAY-user-login-submit)', function (obj) {
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
                    if (obj.field.remember === '1') {
                        localStorage.setItem('login_account', obj.field.account);
                        localStorage.setItem('login_pwd', window.btoa(obj.field.password).replace(/=/g, ''));
                    }else{
                        localStorage.removeItem('login_account');
                        localStorage.removeItem('login_pwd');
                    }
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
</script>

</body>
</html>