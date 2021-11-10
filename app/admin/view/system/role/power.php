{extend name="frame"}
{block name="meta"}{:token_meta()}{/block}
{block name="head"}
<link rel="stylesheet" href="__PUBLIC__/admin_static/css/eleTree.css">
{/block}
{block name="body"}


<div class="layui-card">
    <div class="layui-card-header">{:lang('role.Permission settings')}</div>
    <div class="layui-card-body">
        <div id="test1"></div>
        <div class="eleTree ele" id="tree"></div>
    </div>
</div>

<button class="layui-btn" lay-event="trees" style="margin-left: 100px;">{:lang('role.Setting permissions')}</button>

{/block}

{block name="js"}

<script>
    layui.use(['table', 'jquery', 'form', 'tree'], function() {
        var table = layui.table,$ = layui.jquery, form = layui.form, tree = layui.tree,util = layui.util;

        let load = custom.loading('数据渲染中...');
        $.ajax({
            url: '{:url("getPowerTreeData")}?role_id={$Request.get.role_id ?: 0}'
            , success:function (res) {
                if (res.code === 200) {
                    tree.render({
                        elem: '#test1',
                        accordion: false,
                        showCheckbox:true,
                        data: res.data,
                        id:'test1'
                    });
                }
            },
            error:function (err) {
                console.log(err)
            },
            complete(){
                layer.close(load);
            }
        });


        util.event('lay-event', {
            trees:function (obj) {
                var checkData = tree.getChecked('test1');
                layer.confirm('{:lang("role.power confirm")}?', {icon:3, title:"{:lang('warning')}", btn:["{:lang('confirm')}", "{:lang('cancel')}"]}, function (index) {
                    layer.close(index);
                    let load = custom.loading();
                    $.ajax({
                        url: '{:url("")}'
                        , type: 'post'
                        ,headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                        , data: {set:checkData,  role_id:'{$Request.get.role_id ?: 0}'}
                        , success:function (res) {
                            layer.close(load);
                            if (res.code === 200) {
                                window.parent.layNotice.success('{:lang("success")}');
                                window.parent.layer.closeAll();
                            }else{
                                layNotice.warning(res.msg);
                            }
                        }
                        , error:function (err) {
                            console.log(err);
                        }
                    })
                });
            }
        })
    });

</script>



{/block}