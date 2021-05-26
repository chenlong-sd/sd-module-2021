{extend name="frame"}
<?php /** @var \sdModule\layui\TablePage $table */ ?>
{block name="title"}<?=$page_name ?? lang("Lists")?>{/block}
{block name="meta"}{:token_meta()}{/block}
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
    #sc-menu{
        background-color: white;
        box-shadow: 5px 7px 2px rgba(0,0,0, 0.3);
        padding: 5px;
        border-radius: 3px;
        border: 1px solid #ddd;
        z-index: 10000;
    }
    #sc-menu .shadow{
        list-style: none;
        line-height: 30px;
        text-align: center;
        margin-bottom: 5px;
        padding: 0 5px;
        box-sizing: border-box;
    }
    #sc-menu .shadow:not(.layui-disabled):hover{
        background-color: #f2f2f2;
        cursor: pointer;
    }
    .shadow{
        position: relative;
        max-width: 270px;
        box-shadow: 0 0 20px rgba(0,0,0,0.1) inset;
        border: 1px solid #ddd;
        border-radius: 3px;
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
    <?= $table->getToolbar(); ?>
</script>

<!-- table_line 模板-->
<script id='table_line' type='text/html'>
    <?= $table->getTool(); ?>
</script>

<script id="sc-menu-s" type="text/html">
    <?= $table->getContextHtml() ?>
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
            <?= $table->getField() ?>
        ],
        done: function (res) {
            custom.enlarge(layer, $, '.layer-photos-demo');
            window.table = table;
            <?= $table->getContextJs() ?>
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
        toolbar_event:<?= $table->getToolbarJs(); ?>,
        // tool事件定义
        tool_event:<?= $table->getToolJs(); ?>,
    }

    /**
     * toolbar 事件
     */
    table.on('toolbar(sc)', function (obj) {
        try {
            if (!/^LAYTABLE_/.test(obj.event)){
                table_page.toolbar_event[obj.event](obj);
            }
        }catch (e) {
            console.log(e)
            notice.error('<?= lang("Operation is undefined") ?>');
        }
    });

    /**
     * tool 事件
     */
    table.on('tool(sc)', function (obj) {
        try {
            table_page.tool_event[obj.event](obj);
        }catch (e) {
            console.log(e)
            notice.error('<?= lang("Operation is undefined") ?>');
        }
    });

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

    <?= $search->getUnitJs();?>
</script>
{/block}