{extend name="frame"}


{block name="meta"}{:token_meta()}{/block}


{block name="body"}
<!-- 导航面包屑 -->
<hr>
<div class="layui-container">
    <div class="layui-row">
        <div class="layui-col-md6">
            <form class="layui-form" action="">
                <div class="layui-form-item">
                    <label class="layui-form-label">{:lang('administrator.name')}</label>
                    <div class="layui-input-block">
                        <input type="text" name="name" required  lay-verify="required" placeholder="{:lang('please enter')}" autocomplete="off" class="layui-input">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">{:lang('administrator.account')}</label>
                    <div class="layui-input-inline">
                        <input type="text" name="account" required maxlength="32" lay-verify="required" placeholder="{:lang('please enter')}" autocomplete="off" class="layui-input">
                    </div>
                    <div class="layui-form-mid layui-word-aux">{:lang('administrator.login account')}</div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">{:lang('administrator.password')}</label>
                    <div class="layui-input-inline">
                        <input type="text" name="password" maxlength="32" lay-verify="required" placeholder="{:lang('please enter')}" autocomplete="off" class="layui-input">
                    </div>
                    <div class="layui-form-mid layui-word-aux">{:lang('administrator.6-16 digit password')}</div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">{:lang('administrator.password confirm')}</label>
                    <div class="layui-input-inline">
                        <input type="text" name="password_confirm" maxlength="32" lay-verify="required" placeholder="{:lang('please enter')}" autocomplete="off" class="layui-input">
                    </div>
                    <div class="layui-form-mid layui-word-aux">{:lang('administrator.6-16 digit password')}</div>
                </div>
                <div class="layui-form-item" id="pid">
                    <label class="layui-form-label">{:lang('administrator.role')}</label>
                    <div class="layui-input-block">
                        <select name="role_id" lay-search >
                            <option value=""></option>
                            {foreach $role as $id => $item}
                            <option value="{$id}">{$item}</option>
                            {/foreach}
                        </select>
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">{:lang('administrator.status')}</label>
                    <div class="layui-input-block">
                        <input type="radio" name="status" value="1" title="{:lang('normal')}" checked  autocomplete="off" class="layui-input">
                        <input type="radio" name="status" value="2" title="{:lang('disable')}"  autocomplete="off" class="layui-input">
                    </div>
                </div>
                <div class="layui-form-item">
                    <div class="layui-input-block">
                        <button class="layui-btn" lay-submit lay-filter="formDemo">{:lang('submit')}</button>
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
        var form = layui.form, $ = layui.jquery;

        form.on('submit(formDemo)', function (data) {
            layer.confirm('{:lang("administrator.add admin")}？',{title: "{:lang('information')}" ,btn:['{:lang("confirm")}', '{:lang("cancel")}']}, function () {
                let load = custom.loading();
                $.ajax({
                    type: 'post'
                    ,headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                    , data: data.field
                    , success:function (res) {
                        layer.close(load);
                        if (res.code === 200) {
                            parent.layer.closeAll();
                            window.parent.layNotice.success('{:lang("success")}');
                            window.parent.table.reload('test');
                        }else{
                            layNotice.warning(res.msg);
                        }
                    },
                    error:function (err) {
                        layer.close(load);
                        console.log(err);
                    }
                });
            });

            return false;
        })
    });

</script>
{/block}