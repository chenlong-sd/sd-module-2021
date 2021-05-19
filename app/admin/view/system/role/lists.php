{extend name="common/list_page"}

{block name="custom"}


<!-- table_head 模板-->
<script id='table_head' type='text/html'>
    <button type="button" lay-event="create" class="layui-btn layui-btn-sm"><i class="layui-icon layui-icon-add-1"></i>{:lang('add')}</button>
    <button type="button" lay-event="delete" class="layui-btn layui-btn-sm layui-btn-danger"><i class="layui-icon layui-icon-delete"></i>{:lang('batch deletion')}</button>
    
    <button type="button" lay-event="search" class="layui-btn layui-btn-sm layui-btn-normal">
        <i class="layui-icon layui-icon-search"></i>{:lang('more search')}</button>
    <div class="layui-inline"><input style="height: 30px" id="quick-search" type="text" autocomplete="off"  placeholder="{$quick_search_word}" class="layui-input"></div>
</script>

<!-- table_line 模板-->
<script id='table_line' type='text/html'>
    <button type="button" lay-event="delete" class="layui-btn layui-btn-xs layui-btn-danger"><i class="layui-icon layui-icon-delete"></i>{:lang("delete")}</button>
    <button type="button" lay-event="update" class="layui-btn layui-btn-xs"><i class="layui-icon layui-icon-edit"></i>{:lang('edit')}</button>
    <button type="button" lay-event="power" class="layui-btn layui-btn-xs layui-btn-normal"><i class="layui-icon layui-icon-password"></i>{:lang("role.Permission settings")}</button>
</script>
{/block}
{block name="js_custom"}
<script>

    table_page.tool_event.power = function(obj){
        custom.frame('{:url("system.power/power")}?role_id=' + obj.data[primary], '【'+obj.data.role+'】{:lang("role.Permission settings")}');
    }

</script>
{/block}