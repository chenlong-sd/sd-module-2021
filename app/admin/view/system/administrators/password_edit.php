{extend name="frame"}
{block name="meta"}{:token_meta()}{/block}
{block name="body"}

<!-- 导航面包屑 -->

<div class="layui-container">
    <div class="layui-row">
        <div class="layui-col-md6">
            <form class="layui-form" action="" lay-filter="sd">
                <div class="layui-form-item">
                    <label class="layui-form-label">{:lang("administrator.old password")}</label>
                    <div class="layui-input-inline">
                        <input type="password" name="password_old" maxlength="32" lay-verify="required" placeholder="{:lang('please enter')}" autocomplete="off" class="layui-input">
                    </div>
                    <div class="layui-form-mid layui-word-aux">{:lang("administrator.6-16 digit password")}</div>
                </div>
                    <div class="layui-form-item">
                    <label class="layui-form-label">{:lang("administrator.new password")}</label>
                    <div class="layui-input-inline">
                        <input type="password" name="password" maxlength="32" lay-verify="required" placeholder="{:lang('please enter')}" autocomplete="off" class="layui-input">
                    </div>
                    <div class="layui-form-mid layui-word-aux">{:lang("administrator.6-16 digit password")}</div>
                </div>
                    <div class="layui-form-item">
                    <label class="layui-form-label">{:lang("administrator.password confirm")}</label>
                    <div class="layui-input-inline">
                        <input type="password" name="password_confirm" maxlength="32" lay-verify="required" placeholder="{:lang('please enter')}" autocomplete="off" class="layui-input">
                    </div>
                    <div class="layui-form-mid layui-word-aux">{:lang("administrator.6-16 digit password")}</div>
                </div>
                <div class="layui-form-item">
                    <div class="layui-input-block">
                        <button class="layui-btn" lay-submit lay-filter="formDemo">{:lang("submit")}</button>
                        <button type="reset" class="layui-btn layui-btn-primary">{:lang('reset')}</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>


{/block}

{block name="js"}
<script>

    layui.use(['form', 'jquery'], function(){
        var form = layui.form,$ = layui.jquery;

        form.on('submit(formDemo)', function (data) {
            let load = custom.loading();
            $.ajax({
                url: '{:url("passwordUpdate")}'
                , type: 'post'
                ,headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
                , data: data.field
                , success:function (res) {
                    layer.close(load);
                    if (res.code === 200) {
                        notice.success('修改成功，请重新登录。', function (){
                            top.window.location.href = "{:admin_url('login-out')}";
                        });
                        document.getElementsByTagName('form')[0].reset();
                    }else{
                        notice.warning(res.msg);
                    }
                },
                error:function (err) {
                    layer.close(load);
                }
            });

            return false;
        })
    });

</script>
{/block}