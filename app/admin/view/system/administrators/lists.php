{extend name="frame"}
{block name="body"}
<!-- 表格 -->
<div class="layui-card">
    <div class="layui-card-header">{:lang('administrator.administrator')}</div>
    <div class="layui-card-body">
        {:html_entity_decode($search ?? '')}
        <table class="layui-hide" id="test" lay-filter="test"></table>
    </div>
</div>
{/block}

{block name="js"}

<!-- 表格头部工具栏 -->
<script type="text/html" id="tableHead">
    <button type="button" lay-event="add" class="layui-btn layui-btn-sm"><i class="layui-icon layui-icon-add-1"></i>{:lang('add')}</button>
    <button type="button" lay-event="del" class="layui-btn layui-btn-sm layui-btn-danger"><i class="layui-icon layui-icon-delete"></i>{:lang('batch deletion')}</button>
    <button type="button" lay-event="search" class="layui-btn layui-btn-sm layui-btn-normal">
        <i class="layui-icon layui-icon-search"></i>{:lang('more search')}</button>
    <div class="layui-inline"><input style="height: 30px" id="quick-search" type="text" autocomplete="off"  placeholder="{$quick_search_word}" class="layui-input"></div>
</script>

<!-- 行操作 -->
<script type="text/html" id="handle">
    <button type="button" lay-event="del" class="layui-btn layui-btn-xs layui-btn-danger"><i class="layui-icon layui-icon-delete"></i>{:lang('delete')}</button>
    <button type="button" lay-event="edit" class="layui-btn  layui-btn-xs"><i class="layui-icon layui-icon-edit"></i>{:lang('edit')}</button>
</script>


<script>
    let tableUrl = "{:url('indexData')}";
    let primary = "{$primary ?: 'id'}";

    layui.use(['table', 'jquery', 'form', 'notice'], function() {
        var $ = layui.jquery, form = layui.form,table = layui.table;//很重要

        function del(id){
            layer.confirm('{:lang("confirm delete")}？', {icon:3,title:'{:lang("warning")}',btn:['{:lang("confirm")}', '{:lang("cancel")}']}, function (index) {
                let load = custom.loading();
                $.ajax({
                    url: '{:url("del")}'
                    , type: 'post'
                    , data: {id:id}
                    , success:function (res) {
                        layer.close(load);
                        if (res.code === 200) {
                            layNotice.success('{:lang("success")}');
                            table.reload('test');
                        }else{
                            layNotice.warning(res.msg);
                        }
                    }
                    , error:function (err) {
                        console.log(err);
                    }
                });
            })
        }
        table.render({
            elem: '#test'
            ,url: "{:url('index')}"
            , toolbar: '#tableHead'
            ,cellMinWidth: 80 //全局定义常规单元格的最小宽度，layui 2.2.1 新增
            ,page:true
            ,autoSort:false
            ,cols: [[
                {type:'checkbox'}
                ,{field:'id', width:80, title: 'ID',sort:true}
                ,{field:'account', title: '{:lang("administrator.account")}'}
                ,{field:'name', title: '{:lang("administrator.name")}'}
                ,{field:'role', title: '{:lang("administrator.role")}'}
                ,{field:'lately_time', title: '{:lang("administrator.Recently logged in")}'}
                ,{field:'status', title: '{:lang("administrator.status")}'}
                ,{field:'avatar', title: '{:lang("administrator.avatar")}',templet:function (obj) {
                        return  obj.avatar ? '<div class="layer-photos-demo">\n' +
                            '  <img layer-pid="" layer-src="__PUBLIC__/'+obj.avatar+'" src="__PUBLIC__/'+obj.avatar+'" alt="'+obj.name+'">\n' +
                            '</div>' : '——';
                    }}
                ,{field:'create_time', title: '{:lang("create_time")}'}
                ,{width:150, title: '{:lang("operating")}',templet:'#handle',fixed:'right'}
            ]],
            done:function (res) {
                custom.enlarge(layer, $, '.layer-photos-demo');
                window.table = table;
            }
        });

        table.on('toolbar(test)', function (obj, ss) {
            if (obj.event === 'add') {
                custom.frame('{:url("create")}', '{:lang("add")} {:lang("administrator.administrator")}');
            }else if(obj.event === 'del'){

                let checkStatus = table.checkStatus('test');
                if (checkStatus.data.length) {
                    let id = [];
                    for (let i in checkStatus.data) {
                        if (checkStatus.data.hasOwnProperty(i) && checkStatus.data[i].hasOwnProperty(primary)) {
                            id.push(checkStatus.data[i][primary])
                        }
                    }
                    del(id);
                }
            }else if(obj.event === 'search'){
                $('#search-sd').toggleClass('layui-hide')
            }
        });

        table.on('tool(test)', function (obj) {
            if (obj.event === 'del') {
                del(obj.data[primary]);
            }else if(obj.event === 'edit'){
                custom.frame('{:url("update")}?id=' + obj.data.id, '{:lang("modify")} {:lang("administrator.administrator")}');
            }
        });

        document.onkeyup = (e) => {
            if (e.key === 'Enter') {
                let search = $('#quick-search').val();
                table.reload('test', {
                    where:{quick_search:search}
                    ,page:{
                        curr:1
                    }
                });
                $('#quick-search').val(search).focus();
            }
        };


        form.on('submit(search)', function (object) {
            table.reload('test', {
                where:{search:object.field}
                ,page:{
                    curr:1
                }
            });
            return false;
        });

        table.on('sort(test)', function(obj) {
            let search = $('#quick-search').val();
            table.reload('test', {
                initSort: obj
                , where: {
                    sort: obj.field + ',' + obj.type
                }
            });

            $('#quick-search').val(search).focus();
        });
    });

</script>



{/block}