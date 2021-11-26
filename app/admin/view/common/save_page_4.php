{extend name="frame"}
<?php /** @var \sdModule\layui\form4\Form $form */ ?>
{block name="title"}{$page_name ?: ''}{/block}
{block name="meta"}{:token_meta()}{/block}
{block name="head"}
<link rel="stylesheet" href="__PUBLIC__/admin_static/css/create.css">
<?= $form->getCss() /** 加载的js代码 */ ?>
{/block}
{block name="body"}
<div class="layui-container">
    <div class="layui-row">
        <form class="layui-form <?= $form->getIsPane() ? "layui-form-pane" : "" ?> <?= $form->getMd() ? "layui-col-md" . $form->getMd() : '' ?>" id="" action="" lay-filter="sd">
            <?=$form->getHtml(); /** 加载的表单html */ ?>
        </form>
    </div>
</div>

{/block}
{block name="js"}


<?= $form->getLoadJs() /** 加载的js代码 */ ?>

<script>
    let defaultData = {},form = layui.form, $ = layui.jquery, upload = layui.upload;
    <?= $form->getJs() /** 加载的js代码 */ ?>
</script>
{/block}

<?php /** 以下模块位继承后的自定义的js代码 */ ?>
{block name="js_custom"}{/block}
