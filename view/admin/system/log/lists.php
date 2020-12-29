{extend name="frame"}

{block name="title"}{:lang('log.lists title')}{/block}
{block name="meta"}{:token_meta()}{/block}

{block name="body"}

<div class="layui-card">
    <div class="layui-card-header">{$page_name ?: lang('List data')}</div>
    <div class="layui-card-body">
        {:html_entity_decode($search ?? '')}
        <table class="layui-hide" id="test" lay-filter="test"></table>
    </div>
</div>

{/block}
{block name="js"}


<!-- table_head 模板-->
<script id='table_head' type='text/html'>
    <button type="button" lay-event="search" class="layui-btn layui-btn-sm layui-btn-normal">
        <i class="layui-icon layui-icon-search"></i>{:lang('more search')}</button>
    <div class="layui-inline">
        <input style="height: 30px" id="quick-search" type="text" autocomplete="off" placeholder="{$quick_search_word}" class="layui-input">
    </div>
</script>

<script>
    
    let primary = "{$primary ?: 'id'}";
    layui.use(['form', 'jquery', 'table'], function () {
        var form = layui.form, $ = layui.jquery, table = layui.table;

        
        table.render({
            elem: '#test'
            ,url: "{:url('index')}"
            ,toolbar: '#table_head'
            ,cellMinWidth: 80 
            ,page:true
            ,autoSort: false 
            , title: '{$page_name ?: lang("List data")}'
            ,limits:[10,20,30,40,50,100,200,1000]
            ,cols: [[
                {field:'id', title: 'ID'}
                ,{field:'method', title: '{:lang("log.request type")}'}
                ,{field:'route_title', title: '{:lang("log.route title")}',templet:function (d) {
                        return d.route_title ? d.route_title : '——';
                    }}
                ,{field:'administrators_id', title: '{:lang("log.administrator")}'}
                ,{field:'param', title: '{:lang("log.request param")}'}
                ,{field:'route', title: '{:lang("log.request url")}'}
                ,{field:'create_time', title: '{:lang("create_time")}'}
            ]],
            done:function (res) {
                custom.enlarge(layer, $, '.layer-photos-demo');
                window.table = table;
            }
        });

        
        table.on('toolbar(test)', function (obj) {
           if(obj.event === 'search'){
                $('#search-sd').toggleClass('layui-hide')  
            } 
        });

        document.onkeyup = (e) => {
            if (e.key === 'Enter') {
                let search = $('#quick-search').val();
                table.reload('test', {
                    where:{
                        quick_search:search
                    }
                    ,page:{
                        curr:1
                    }
                });
                $('#quick-search').val(search).focus();
            }
        };


        form.on('submit(search)', function (object) {
            table.reload('test', {
                where:{
                    search:object.field
                },
                  page:{
                    curr:1
                }
            });
            return false;
        });


    });
</script>
{/block}