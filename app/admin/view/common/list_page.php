{extend name="frame"}
<?php /** @var \sdModule\layui\tablePage\ListsPage $table */ ?>
{block name="title"}<?=$page_name ?? lang("Lists")?>{/block}
{block name="meta"}
<?php $table->render(); ?>
{:token_meta()}
{/block}
{block name="head"}
<style>
    .layui-table-body.layui-table-main{
        -ms-overflow-style: none;
        overflow-y: -moz-scrollbars-none;
    }
    .layui-card{
        /*overflow-y: hidden !important;*/
        margin-bottom: 0 !important;
    }

    .layui-table-page, .layui-table-total{
        margin-bottom: 0;
    }
    [lay-id=sc]>.layui-table-box>.layui-table-header{
        z-index: 9;
    }

    .layui-table-tool{
        position: sticky;
        top:0
    }
    .layui-table-page{
        position: sticky;
        bottom:0;
        background: #fff;
    }
    .layui-menu.layui-dropdown-menu li:not(.layui-disabled):hover{
        box-shadow: inset 0 0 25px #ddd;
        border-radius: 5px;
    }
    .layui-menu-body-title{
        border-bottom: 1px solid #ddd;
        text-align: center;
    }

</style>

{/block}
{block name="body"}

<div class="layui-card">
    <div class="layui-card-body">
        <?php /** @var  \sdModule\layui\form\Form $search */?>
        <form class="layui-form" action="" lay-filter="sd">
            <?=$search->getHtml(); /** 加载的表单html */ ?>
        </form>
        <table class="layui-hide" id="sc" lay-filter="sc"></table>
    </div>
</div>

{/block}

<?php /** custom 代码，自定义的自行更改,自定义的list页面继承此页面 */ ?>
{block name="custom"}
<!-- table_head 模板-->
<script id='table_head' type='text/html'>
    <?= $table->getEventElement(true); ?>
</script>

<!-- table_line 模板-->
<script id='table_line' type='text/html'>
    <?= $table->getEventElement(); ?>
</script>

<div id="sc-menu" style="display: none;min-width: 100px; position: absolute"></div>

{/block}

{block name="js"}

<script>

    let primary = "<?=$primary ?? 'id'?>";

    var form = layui.form, $ = layui.jquery, table = layui.table,wh=0;

    let table_render_data = {
        elem: '#sc'
        , url: location.href
        , toolbar: '#table_head'
        , cellMinWidth: 80
        , page: true
        , autoSort: false
        , title: '<?=$page_name ?? lang("List data")?>'
        , limits: [10, 20, 30, 40, 50, 100, 200, 1000]
        , cols: [
            <?= $table->getFiledConfig() ?>
        ],
        done: function (res) {
            custom.enlarge(layer, $, '.layer-photos-demo');
            window.table = table;
            dropdownMenu(res.data);
            <?= $table->getDoneJs() ?>
        }
    }

    /** @var {object} */
    let sys_config = <?= $table->getConfig() ?>;

    for (let x in sys_config) {
        table_render_data[x] = sys_config[x];
    }

    /**
     * 表格渲染
     * */
    table.render(table_render_data);

    let table_page = {
        // toolbar事件定义
        toolbar_event: {<?= $table->getEventJs(true); ?>},
        // tool事件定义
        tool_event: {<?= $table->getEventJs(); ?>},
    }

    /**
     * toolbar 事件
     */
    table.on('toolbar(sc)', function (obj) {
        try {
            if (!/^LAYTABLE_/.test(obj.event)){
                table_page.toolbar_event[obj.event](obj.data);
            }
        }catch (e) {
            console.log(e)
            notice.error('<?= lang("Operation is undefined") ?>');
        }
    });

    /**
     * tool 事件
     */
    <?php if ($table->getEventMode() === $table::BUTTON_MODE){ ?>
    table.on('tool(sc)', function (obj) {
        try {
            table_page.tool_event[obj.event](obj.data);
        }catch (e) {
            notice.error('<?= lang("Operation is undefined") ?>');
        }
    });
    <?php } ?>

    /**
     * 排序事件
     */
    table.on('sort(sc)', function(obj){
        table.reload('sc', {
            initSort: obj
            ,where: {
                sort:`${obj.field},${obj.type}`
            }
        });
    });

    form.on('submit(sc-form)', function (object) {
        let is_have_where = false;
        if (table_render_data.hasOwnProperty('where') && table_render_data.where.hasOwnProperty('search')) {
            is_have_where = true;
        }

        let reload = {
            where: {
                search: Object.assign({}, is_have_where ? table_render_data.where.search : {}, object.field)
            }
        };
        if (table_render_data.page === true) {
            reload.page = {
                curr: 1
            }
        }

        table.reload('sc', reload);
        return false;
    });

    /**
     * 删除数据
     * @param id
     */
    function del(id) {
        layer.confirm('<?=lang("confirm delete")?>？', {
            icon: 3,
            title: '<?=lang("warning")?>',
            btn: ['<?=lang("confirm")?>', '<?=lang("cancel")?>']
        }, function (index) {
            let load = custom.loading();
            $.ajax({
                url: '<?=url("del")?>'
                , type: 'post'
                , data: {id: id}
                , success: function (res) {
                    layer.close(load);
                    if (res.code === 200) {
                        layNotice.success('<?=lang("success")?>');
                        table.reload('sc');
                    } else {
                        layNotice.warning(res.msg);
                    }
                }
                , error: function (err) {
                    console.log(err);
                }
            });
        })
    }

    /**
     * 菜单
     * @param data
     */
    function dropdownMenu(data){
        let  line_data = {},d = {};
        layui.dropdown.render({
            elem: '.menu-down-sc'
            ,data: <?= $table->getMenuModeEventData() ?>
            ,click: function(data, othis){
                if (othis.hasClass('layui-disabled')){
                    return false;
                }
                try {
                    table_page.tool_event[data.id](line_data);
                }catch (e) {
                    notice.error('<?= lang("Operation is undefined") ?>');
                }
            }
            ,ready: function(elemPanel, elem){
                line_data = d = data[$(elem).parents('tr').data('index')];
                for (let i = 0; i < this.data.length; i++){
                    if (!this.data[i].hasOwnProperty('where')){
                        continue;
                    }
                    if (this.data[i].where && !eval(this.data[i].where)) {
                        elemPanel.find('li').eq(i).addClass('layui-disabled');
                    }
                }
            }
        });
    }

    <?= $search->getUnitJs();?>
    <?= $table->getJs();?>
</script>
{/block}