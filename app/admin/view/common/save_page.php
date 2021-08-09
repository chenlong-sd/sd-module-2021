{extend name="frame"}
<?php /** @var \sdModule\layui\form\Form $form */ ?>
{block name="title"}{$page_name ?: ''}{/block}
{block name="meta"}{:token_meta()}{/block}
{block name="head"}
<link rel="stylesheet" href="__PUBLIC__/admin_static/css/create.css">
<?= $form->getUnitCss() /** 加载的单元CSS代码 */ ?>
{/block}
{block name="body"}

<div class="layui-container">
    <div class="layui-row">
        <form class="layui-form <?= $form->getSkin() ?>" action="" lay-filter="sd">
            <?php if ($form->getCustomMd()){ ?>
                <div class="layui-col-md<?=$form->getCustomMd()?>">
                    <?=$form->getHtml(); /** 加载的表单html */ ?>
                </div>
            <?php }else{ ?>
                <?=$form->getHtml(); /** 加载的表单html */ ?>
            <?php } ?>
        </form>
    </div>
</div>

{/block}
{block name="js"}


<?= $form->getLoadJs() /** 加载的外部js代码 */ ?>

<script>
    let defaultData = {},form = layui.form, $ = layui.jquery, upload = layui.upload;
    <?= $form->getJs() /** 加载的js代码 */ ?>
</script>
{/block}

<?php /** 以下模块位继承后的自定义的js代码 */ ?>
{block name="js_custom"}{/block}
