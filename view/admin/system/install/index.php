{extend name="frame"}

{block name="body"}

<div class="layui-container layui-bg-gray">
    <div class="layui-row">
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
        <div class="layui-col-md6" style="padding: 20px">
            <div class="layui-card">
                <div class="layui-card-header">更新记录</div>
                <div class="layui-card-body">
                    <ul class="layui-timeline">
                        <li class="layui-timeline-item">
                            <i class="layui-icon layui-timeline-axis">&#xe63f;</i>
                            <div class="layui-timeline-content layui-text">
                                <h3 class="layui-timeline-title">Version 3.1 </h3>
                                <ul>
                                    <li> 取消快捷搜功能。 </li>
                                    <li> 软删除取消自定义的逻辑，采用TP自带的软删除，所有查询都不需要考虑软删除了。 </li>
                                    <li> 数据列表查询方式更新,取消采用trait，采取依赖注入的方式，避免所有请求都加载对应内容。 </li>
                                    <li> 弃用全部TablePage废弃的方法函数, 列表页的搜索表单采用 defaultForm 类，弃用原来的 searchForm 冗余模块</li>
                                    <li> TablePage页面的js代码传递方式更新，更新函数：TableAux::openPage(), TableAux::openTabs(), TableAux::ajax(),TableAux::batchAjax() </li>
                                    <li> 增加数据库备份与恢复操作</li>
                                </ul>
                            </div>
                        </li>
                        <li class="layui-timeline-item">
                            <i class="layui-icon layui-timeline-axis">&#xe63f;</i>
                            <div class="layui-timeline-content layui-text">
                                <div class="layui-timeline-title">过去</div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>

        </div>
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