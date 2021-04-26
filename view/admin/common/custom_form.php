{extend name="frame"}
<?php /** @var \sdModule\layui\form\Form $form */ ?>
{block name="title"}{$page_name ?: ''}{/block}
{block name="meta"}{:token_meta()}{/block}
{block name="body"}

<?= $form->getHtml() /** 加载的表单html */ ?>

{/block}
{block name="js"}


<?= $form->loadJs() /** 加载的外部js代码 */ ?>

<script>
    let defaultData = {},form = layui.form, $ = layui.jquery, upload = layui.upload;
    <?= $form->getJs() /** 加载的js代码 */ ?>
</script>
{/block}

<?php /** 以下模块位继承后的自定义的js代码 */ ?>
{block name="js_custom"}{/block}
