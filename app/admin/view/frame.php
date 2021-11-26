<!DOCTYPE html>
<html lang="zh-cn">
<head>
    <meta charset="UTF-8">
    <title>{block name="title"}{:lang('home')}{/block}</title>
    <link rel="stylesheet" href="__PUBLIC__/admin_static/layui/css/layui.css" media="all" />
<!--    head 部分-->

    {block name="meta"}{/block}
    <style>
        body {
            padding: 10px
        }
        #sc-4-tab{
            display: none;
            margin: 0;
        }
    </style>
    {include file="predefine"/}

    {block name="head"}{/block}
</head>
<body>
<div class="layui-tab layui-tab-brief" id="sc-4-tab" lay-allowClose="true" lay-filter="docDemoTabBrief-sc-4">
    <ul class="layui-tab-title">
        <li><i class="layui-icon layui-icon-home"></i></li>
    </ul>
    <div class="layui-tab-content"></div>
</div>
<!-- 主体部分 -->
<div id="sc-4-box">
    {block name="body"}{/block}
</div>

</body>
<script type="text/javascript" src="__PUBLIC__/admin_static/layui/layui.js"></script>

{:html_entity_decode($searchJs ?? '')}

<!-- js 的一些配置 -->
<script>
    layui.config({
        base: '__PUBLIC__/admin_static/layui/dist/'
    });
    let local = window.localStorage['layuiAdmin'] ? JSON.parse(window.localStorage['layuiAdmin']) : {};
    let alias = 'black';
    try{
        alias = local.theme.color.alias;
        alias = alias === 'default' ? 'black' : alias;
    }catch (e) {}
    layer.config({
        extend:alias +'/style.css'
        ,skin:'demo-class'
    });

    layui.use('notice',function () {
        window.layNotice = layui.notice;
    });

    // setTimeout(() => {
    //     let h = (window.innerHeight - 144);
    //     layui.jquery("#sc-4-tab").show().find('.layui-tab-content')
    //         .append(`<div class="layui-tab-item">${layui.jquery("#sc-4-box").html()}</div>`);
    //     layui.element.tabAdd('docDemoTabBrief-sc-4', {
    //         title: '测试'
    //         ,content: `<div class="layui-tab-item layui-show"><iframe width="100%" height="${h}" src="{:url('system.Log/index')}" frameborder="0"></iframe></div>` //支持传入html
    //         ,id: 'tet'
    //     }).tabChange('docDemoTabBrief-sc-4', 'tet');
    //
    //     layui.jquery("#sc-4-box").remove();
    // }, 5000);
</script>

<script type="text/javascript" src="__PUBLIC__/admin_static/js/custom.js"></script>

<!-- js 部分-->
{block name="custom"}{/block}
{block name="js"}{/block}
{block name="js_custom"}{/block}
</html>