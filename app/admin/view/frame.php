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
    </style>
    {include file="predefine"/}

    {block name="head"}{/block}
</head>
<body>

<!-- 主体部分 -->
{block name="body"}{/block}

</body>
<script type="text/javascript" src="__PUBLIC__/admin_static/layui/layui.js"></script>

{:html_entity_decode($searchJs ?? '')}

<!-- js 的一些配置 -->
<script>
    layui.config({
        base: '__PUBLIC__/admin_static/layui/dist/'
    });
    let local = window.localStorage['layuiAdmin'];
    let alias = 'black';
    if (local != '{}') {
        alias = eval('(' + window.localStorage['layuiAdmin'] + ')').theme.color.alias;
        alias = alias === 'default' ? 'black' : alias;
    }
    layer.config({
        extend:alias +'/style.css'
        ,skin:'demo-class'
    });

    layui.use('notice',function () {
        window.layNotice = layui.notice;
    });
</script>

<script type="text/javascript" src="__PUBLIC__/admin_static/js/custom.js"></script>

<!-- js 部分-->
{block name="custom"}{/block}
{block name="js"}{/block}
{block name="js_custom"}{/block}
</html>