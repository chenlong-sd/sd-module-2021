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
        <div class="layui-col-md6" style="padding: 20px;max-height: 700px;overflow: auto;">
            <div class="layui-card">
                <div class="layui-card-header">更新记录，
                    <span style="color: #00a2d4">节省后台常规页面的一切HTML代码，重复代码。</span>
                </div>
                <div class="layui-card-body">
                    <ul class="layui-timeline">
                        <li class="layui-timeline-item">
                            <i class="layui-icon layui-timeline-axis">&#xe63f;</i>
                            <div class="layui-timeline-content layui-text">
                                <h3 class="layui-timeline-title">Version 4.0 </h3>
                                <p>2021-12-12</p>
                                <ul>
                                    <li>详情查看 <a target="_blank" href="https://www.kancloud.cn/chenlon-sd/sd-module-2021/2566301"></a></li>
                                </ul>
                            </div>
                        </li>
                        <li class="layui-timeline-item">
                            <i class="layui-icon layui-timeline-axis">&#xe63f;</i>
                            <div class="layui-timeline-content layui-text">
                                <h3 class="layui-timeline-title">Version 3.3 </h3>
                                <p>2021-06-07</p>
                                <ul>
                                    <li> 优化更新表单组件，精准控制每一个html元素 </li>
                                    <li> 优化列表页初始界面操作 </li>
                                    <li> layui更新到2.6.8 </li>
                                    <li> 修复数据库备份含json数据是的bug，以及其他已知bug </li>
                                    <li> <span style="color: red">增加</span>开放后台登录administrators表限制，现可配置表登录后台</li>
                                    <li> <span style="color: red">增加</span>列表页行统计 </li>
                                    <li> <span style="color: red">增加</span>列表页的模板输出 </li>
                                    <li> <span style="color: red">增加</span>列表页开关操作 </li>
                                    <li> <span style="color: red">增加</span>列表页开字段合并  </li>
                                    <li> <span style="color: red">增加</span>滑块表单  </li>
                                    <li> <span style="color: red">增加</span>颜色选择器表单  </li>
                                    <li> <span style="color: red">增加</span>单个配置值的设置功能（基础信息配置），用于解决若干个单项无关联的设置  </li>
                                    <li> <span style="color: red">增加</span>字典管理，用于解决用户自定义配置分类管理的时候  </li>
                                    <li> <span style="color: red">增加</span>列表页事件的 prompt 弹窗 </li>
                                    <li> <span style="color: red">增加</span>基于swoole的定时任务 [extend/sdModule/timedTask/start.php]</li>
                                    <li> <span style="color: red">增加</span>列表页操作下拉菜单模式</li>
                                    <li> <span style="color: red">增加</span>微信APIV3接口退款</li>
                                </ul>
                            </div>
                        </li>
                        <li class="layui-timeline-item">
                            <i class="layui-icon layui-timeline-axis">&#xe63f;</i>
                            <div class="layui-timeline-content layui-text">
                                <h3 class="layui-timeline-title">Version 3.2 </h3>
                                <ul>
                                    <li> 优化更新详情页的代码编写 </li>
                                    <li> layui更新到2.6 </li>
                                    <li> 修复邮件发送自带的QQ的BUG </li>
                                    <li> 增加Tab页面和弹窗的互相切换 </li>
                                    <li> 微信相关功能的配置优化，可以支持多个微信账号。增加小程序的登录 </li>
                                </ul>
                            </div>
                        </li>
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
                                    <li> 修改TablePage页面事件添加方式</li>
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