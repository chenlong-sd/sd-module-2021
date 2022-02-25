<?php /** @var \sdModule\layui\tableDetail\Page $this */ ?>

<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="<?= $this->getRoot() ?>admin_static/layui/css/layui.css">
    <style>
        body{margin: 20px;background-color: white}
        td img{margin-right: 10px;cursor: zoom-in}
        .layui-table th {text-align: center;}
        <?= $this->css ?>
    </style>

    <?= implode(array_map(function($v){
        return \sdModule\layui\Dom::create('link', true)->addAttr([
            'href' => $this->getRoot() . $v,
            'rel' => 'stylesheet'
        ]);
    }, $this->loadCss)) ?>
    <?= include __DIR__ . '/js_var.php'?>
</head>
<body>

<fieldset class="layui-elem-field layui-field-title">
    <legend><?= $this->page_name ?></legend>
    <div class="layui-field-box">

        <div class="layui-btn-container">
            <?= implode($this->event) ?>
        </div>


<?php ////////////////////////////////////===表格===//////////////////////////////////////// ?>

<?php foreach ($this->table as $table){ ?>
    <br/>
    <blockquote class="layui-elem-quote"><?= $table->getTitle() ?></blockquote>

    <table class="layui-table">
    <?php foreach ($table->getTable() as $tr) { ?>
        <tr>
            <?php foreach ($tr as $td) { ?>
                <?php if ($table->isLineMode()) { ?>
                    <td <?= $td['field'] ? $td['field_attr'] : $td['content_attr'] ?> ><?= $td['content'] ?></td>
                <?php }else if (is_numeric($td['field'])) { ?>
                    <td <?= $td['content_attr'] ?> <?= $td['field_attr'] ?> ><?= $td['title'] ?></td>
                <?php } else{ ?>
                    <td <?= $td['field_attr'] ?> ><?= $td['title'] ?></td>
                    <td <?= $td['content_attr'] ?> ><?= $td['content'] ?></td>
                <?php } ?>
            <?php } ?>
        </tr>
    <?php  }?>
    </table>
<?php } ?>
        <?= implode($this->afterEvent) ?>
    </div>
</fieldset>

</body>

<?= implode(array_map(function($v){
    return \sdModule\layui\Dom::create('script')->addAttr([
        'src' => $this->getRoot() . $v,
        'type' => 'text/javascript'
    ]);
}, $this->loadJs)) ?>


<script type="text/javascript">
    layer.ready(function() {
        custom.enlarge(layer,layui.jquery,'.img-table');
    });

    layui.util.event('lay-event', {<?= implode($this->eventJs) ?>})

    <?= $this->customJs ?>
</script>
</html>