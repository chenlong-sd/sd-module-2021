{extend name="frame"}

{block name="head"}
<style>
    .layui-form-label {
        padding: 5px 15px;
    }
    .layui-input, .layui-select, .layui-textarea {
        height: 30px;
        line-height: 30px\9;
    }
    .layui-form-select dl dd, .layui-form-select dl dt {
        line-height: 30px;
    }
</style>

{/block}


{block name="body"}
<div class="layui-card">
    <div class="layui-card-header">{:lang('route.route_m')}
        【<span style="color: red">{:lang('route.route_tip')}</span>】
    </div>
    <div class="layui-card-body">
        <table class="layui-hide" id="test" lay-filter="test"></table>
    </div>
</div>
{/block}

{block name="js"}

<!-- 表格头部工具栏 -->
<script type="text/html" id="tableHead">
    <button type="button" lay-event="add" class="layui-btn layui-btn-sm"><i class="layui-icon layui-icon-add-1"></i>{:lang('add')}</button>
    <button type="button" lay-event="expandAll" class="layui-btn layui-btn-sm">{:lang('expand all')}</button>
    <button type="button" lay-event="foldAll" class="layui-btn layui-btn-sm">{:lang('collapse all')}</button>

    <form class="layui-form layui-inline" action="">
        <div class="layui-inline">
            <div class="layui-input-inline">
                <input type="text" name="title" required  lay-verify="required" placeholder="{:lang('please enter')}" autocomplete="off" class="layui-input">
            </div>
        </div>
        <button type="submit" lay-submit="" lay-filter="demo1" class="layui-btn  layui-btn-sm">
            <i class="layui-icon layui-icon-search"></i>
        </button>
    </form>
</script>

<!-- 行操作 -->
<script type="text/html" id="handle">
    <button type="button" lay-event="del" class="layui-btn layui-btn-xs layui-btn-danger"><i class="layui-icon layui-icon-delete"></i>{:lang('delete')}</button>
    <button type="button" lay-event="edit" class="layui-btn  layui-btn-xs"><i class="layui-icon layui-icon-edit"></i>{:lang('edit')}</button>
</script>


<script>

    layui.use(['table', 'jquery', 'form', 'notice', 'treetable'], function() {
        var $ = layui.jquery, form = layui.form,
            treeGrid = layui.treetable,table = layui.table;// 很重要
        // 代码地址 https://gitee.com/whvse/treetable-lay
        // 演示地址 https://whvse.gitee.io/treetable-lay/index.html
        let tableRender = function(){
            treeGrid.render({
                elem: '#test'
                , toolbar: '#tableHead'
                ,treeColIndex: 1,
                treeSpid: 0
                ,cellMinWidth: 80 //全局定义常规单元格的最小宽度，layui 2.2.1 新增
                ,treeIdName:'id'//树形id字段名称
                ,treePidName:'pid'//树形父id字段名称
                ,treeShowName:'title'//以树形式显示的字段
                ,treeDefaultClose:true
                ,where:{'sort':'weigh'}
                ,cols: [[
                    {field:'id', width:80, title: 'ID'}
                    ,{field:'title', title: '{:lang("route.route_title")}'}
                    ,{field:'parent', title: '{:lang("route.route_parent")}', templet:function (data) {
                            return data.parent ? data.parent : '——'
                        }}
                    ,{field:'type', title: '{:lang("route.route_type")}',}
                    ,{field:'route', title: '{:lang("route.route_route")}'}
                    ,{field:'weigh', title: '{:lang("route.route_weigh")}'}
                    ,{width:150, title: '{:lang("operating")}',templet:'#handle',fixed:'right'}
                ]],
                done:function (res) {

                }
            });
        };
        window.tables = tableRender;
        tableRender();


        table.on('toolbar(test)', function (obj) {
            if (obj.event == 'add') {
                custom.frame('{:url("create")}', '{:lang("add")}{:lang("route.route_name")}');
            }else if (obj.event == 'expandAll') {
                treeGrid.expandAll('#test');
            }else if (obj.event == 'foldAll') {
                treeGrid.foldAll('#test');
            }
        });

        table.on('tool(test)', function (obj) {
            if (obj.event == 'del') {
                layer.confirm('{:lang("route.delete tip")}', {icon:3,title:'{:lang("warning")}',btn:['{:lang("confirm")}', '{:lang("cancel")}']}, function (index) {
                    let load = custom.loading();
                    $.ajax({
                        url: '{:url("del")}'
                        , type: 'post'
                        , data: {id:obj.data.id}
                        , success:function (res) {
                            layer.close(load);
                            if (res.code === 200) {
                                layNotice.success('{:lang("success")}');
                                tableRender();
                            }else{
                                layNotice.error('{:lang("fail")}');
                            }
                        }
                        , error:function (err) {
                            console.log(err);
                        }
                    });
                })
            }else if(obj.event == 'edit'){
                custom.frame('{:url("update")}?id=' + obj.data.id, '{:lang("edit")}{:lang("route.route_name")}');
            }
        });

        form.on('submit(demo1)', function (obj) {
            var keyword = obj.field.title;
            var searchCount = 0;
            $('#test').next('.treeTable').find('.layui-table-body tbody tr td').each(function () {
                $(this).css('background-color', 'transparent');
                var text = $(this).text();
                if (keyword != '' && text.indexOf(keyword) >= 0) {
                    $(this).css('background-color', 'rgba(250,230,160,0.5)');
                    if (searchCount == 0) {
                        treeGrid.expandAll('#test');
                        $('html,body').stop(true);
                        $('html,body').animate({scrollTop: $(this).offset().top - 150}, 500);
                    }
                    searchCount++;
                }
            });
            if (keyword == '') {
                layer.msg("{:lang('route.Enter search content')}", {icon: 5});
            } else if (searchCount == 0) {
                layer.msg("{:lang('route.No match')}", {icon: 5});
            }
            return false;
        })

    });

</script>



{/block}