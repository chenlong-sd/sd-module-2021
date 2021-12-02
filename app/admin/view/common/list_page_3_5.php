{extend name="frame"}
<?php /** @var \sdModule\layui\lists\PageData $table */ ?>
{block name="title"}<?=$page_name ?? lang("Lists")?>{/block}
{block name="meta"}
<?php $table = $table->render(); ?>
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
    <?= $table->getCss() ?>
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
    <?=  $table->getEventElement(true); ?>
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
            <?= $table->getColumnConfigure() ?>
        ],
        done: function (res) {
            custom.enlarge(layer, $, '.layer-photos-demo');
            window.table = table;
            <?= $table->getDoneJs() ?>
            // 下拉菜单
            <?php foreach ($table->getRowDropDownMenu() as $row_menu_class => $row_menu_data){ ?>
            rowDropdownMenu(res.data, <?= json_encode($row_menu_data, JSON_UNESCAPED_UNICODE) ?>, '<?= $row_menu_class ?>');
            <?php }?>

            // 头部的下拉菜单
            <?php foreach ($table->getHeaderDropDownMenu() as $header_menu_class => $header_menu_data){ ?>
            headerDropdownMenu(<?= json_encode($header_menu_data, JSON_UNESCAPED_UNICODE) ?>, '<?= $header_menu_class ?>');
            <?php }?>
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
            notice.error('<?= lang("Operation is undefined") ?>');
        }
    });

    /**
     * tool 事件
     */
    table.on('tool(sc)', function (obj) {
        try {
            table_page.tool_event[obj.event](obj.data);
        }catch (e) {
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
                sort:`${obj.field},${obj.type}`,
            }
        }, true);
    });

    form.on('submit(sc-form)', function (object) {
        let reload = {
            where: {
                search: object.field
            }
        };
        if (table_render_data.page === true) {
            reload.page = {
                curr: 1
            }
        }

        table.reload('sc', reload, true);
        return false;
    });

    /**
     * 行事件菜单
     * @param data 数据
     * @param menu_data 组件菜单的数据
     * @param menu_class 渲染的元素
     */
    function rowDropdownMenu(data, menu_data, menu_class){
        let  line_data = {},d = {};
        layui.dropdown.render({
            elem: `.${menu_class}`
            ,data: menu_data
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

    /**
     * 头部事件下拉菜单
     * @param menu_data
     * @param menu_class
     */
    function headerDropdownMenu(menu_data, menu_class) {
        layui.dropdown.render({
            elem: `.${menu_class}`
            ,data: menu_data
            ,click: function(data, othis){
                console.log(data.id)
                if (othis.hasClass('layui-disabled')){
                    return false;
                }
                try {
                    table_page.toolbar_event[data.id]();
                }catch (e) {
                    notice.error('<?= lang("Operation is undefined" ) ?>');
                }
            }
        });
    }

    <?= $search->getUnitJs();?>
    <?= $table->getJs();?>
</script>
{/block}