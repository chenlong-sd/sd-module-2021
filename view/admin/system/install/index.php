{extend name="frame"}

{block name="body"}

<div class="layui-container layui-bg-gray">
    <div class="layui-row">
        <div class="layui-col-md3"><br/></div>
        <div class="layui-col-md6">
            <h1 style="text-align: center;margin:50px 0;">数据库初始化</h1>
            <form class="layui-form" style="margin-bottom: 100px" action="">
                <div class="layui-form-item">
                    <label class="layui-form-label">type</label>
                    <div class="layui-input-block">
                        <input type="text" name="type" placeholder="Mysql" autocomplete="off" class="layui-input">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">host</label>
                    <div class="layui-input-block">
                        <input type="text" name="host" placeholder="127.0.0.1" autocomplete="off" class="layui-input">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">database</label>
                    <div class="layui-input-block">
                        <input type="text" name="database" required lay-verify="required" placeholder="请输入"
                               autocomplete="off" class="layui-input">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">username</label>
                    <div class="layui-input-block">
                        <input type="text" name="user" placeholder="root" autocomplete="off" class="layui-input">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">password</label>
                    <div class="layui-input-block">
                        <input type="text" name="password" placeholder="root" autocomplete="off" class="layui-input">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">prefix</label>
                    <div class="layui-input-block">
                        <input type="text" name="prefix" placeholder="sd_" autocomplete="off" class="layui-input">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">port</label>
                    <div class="layui-input-block">
                        <input type="text" name="port" placeholder="3306" autocomplete="off" class="layui-input">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">后台别名</label>
                    <div class="layui-input-block">
                        <input type="text" name="admin_alias" placeholder="admin" autocomplete="off" class="layui-input">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">公司简称</label>
                    <div class="layui-input-block">
                        <input type="text" name="company" placeholder="四字内" autocomplete="off" class="layui-input">
                    </div>
                </div>
                <div class="layui-form-item">
                    <div class="layui-input-block">
                        <button class="layui-btn" lay-submit lay-filter="formDemo">初始化</button>
                        <button type="reset" class="layui-btn layui-btn-primary">重置</button>
                    </div>
                </div>
            </form>
        </div>
        <div class="layui-col-md3"></div>
    </div>
</div>

{/block}

{block name="js"}
<script>
    layui.use(['form', 'jquery'], function () {
        var form = layui.form, $ = layui.jquery;
        form.on('submit(formDemo)', function (data) {
            let load = custom.loading();
            $.ajax({
                type: 'post'
                , data: data.field
                , success: function (res) {
                    layer.close(load);
                    if (res.code === 200) {
                        layer.alert('初始化成功！账号：admin，密码：123456', function () {
                            location.href = '{:url("/")}'
                        });
                    } else {
                        layer.msg(res.msg);
                    }
                }
            });
            return false;
        });
    });

</script>

{/block}