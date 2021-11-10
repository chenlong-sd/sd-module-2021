{extend name="frame"}
<?php /** @var \sdModule\layui\lists\PageData $table */ ?>
<?php /** @var  \sdModule\layui\form\Form $search */?>
{block name="head"}
<?php $table = $table->render(); ?>
<style>
    .layui-form-label {
        padding: 5px 15px;
    }
    .layui-input, .layui-select, .layui-textarea {
        height: 30px;
        line-height: 30px;
    }
    .layui-form-select dl dd, .layui-form-select dl dt {
        line-height: 30px;
    }
    <?= $table->getCss() ?>
</style>

{/block}


{block name="body"}
<div class="layui-card">
    <div class="layui-card-body">
        <table class="layui-hide" id="sc" lay-filter="sc"></table>
    </div>
</div>
{/block}

{block name="js"}

<!-- 表格头部工具栏 -->
<script type="text/html" id="tableHead">
    <?= $table->getEventElement(true); ?>
    <button type="button" lay-event="expandAll" class="layui-btn layui-btn-sm">{:lang('expand all')}</button>
    <button type="button" lay-event="foldAll" class="layui-btn layui-btn-sm">{:lang('collapse all')}</button>

    <form class="layui-form layui-inline" action="">
        <div class="layui-inline">
            <div class="layui-input-inline">
                <input type="text" name="title" required  lay-verify="required" placeholder="{:lang('please enter')}" autocomplete="off" class="layui-input">
            </div>
        </div>
        <button type="submit" lay-submit="" lay-filter="demo1" class="layui-btn  layui-btn-sm">
            <i class="layui-icon layui-icon-search"></i>
        </button>
    </form>
</script>

<!-- 行操作 -->
<script type="text/html" id="table_line">
    <?= $table->getEventElement(); ?>
</script>


<div id="sc-menu" style="display: none;min-width: 100px; position: absolute"></div>
<script>
    let primary = "<?=$primary ?? 'id'?>";
    layui.use(['table', 'jquery', 'form', 'notice', 'treetable'], function() {
        var $ = layui.jquery, form = layui.form,
            treeGrid = layui.treetable,table = layui.table;// 很重要
        // 代码地址 https://gitee.com/whvse/treetable-lay
        // 演示地址 https://whvse.gitee.io/treetable-lay/index.html
        window.tableRender = () => {
            let table_render_data = {
                elem: '#sc'
                ,toolbar: '#tableHead'
                ,treeColIndex: 1
                ,treeSpid: 0
                ,cellMinWidth: 80 //全局定义常规单元格的最小宽度，layui 2.2.1 新增
                ,treeIdName:'id'//树形id字段名称
                ,treePidName:'pid'//树形父id字段名称
                ,treeShowName:'title'//以树形式显示的字段
                ,treeDefaultClose:true
                ,cols: [<?= $table->getColumnConfigure() ?>],
                done:function (res) {
                    custom.enlarge(layer, $, '.layer-photos-demo');
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
            };
            /** @var {object} */
            let sys_config = <?= $table->getConfig() ?>;

            for (let x in sys_config) {
                table_render_data[x] = sys_config[x];
            }
            treeGrid.render(table_render_data);
        }

        tableRender();

        let table_page = {
            // toolbar事件定义
            toolbar_event: {<?= $table->getEventJs(true); ?>},
            // tool事件定义
            tool_event: {<?= $table->getEventJs(); ?>},
        }


        table.on('toolbar(sc)', function (obj) {
            if (obj.event === 'expandAll') {
                treeGrid.expandAll('#sc');
            }else if (obj.event === 'foldAll') {
                treeGrid.foldAll('#sc');
            }else{
                try {
                    if (!/^LAYTABLE_/.test(obj.event)){
                        table_page.toolbar_event[obj.event](obj.data);
                    }
                }catch (e) {
                    console.log(e)
                    notice.error('<?= lang("Operation is undefined") ?>');
                }
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

        form.on('submit(demo1)', function (obj) {
            var keyword = obj.field.title;
            var searchCount = 0;
            $('#sc').next('.treeTable').find('.layui-table-body tbody tr td').each(function () {
                $(this).css('background-color', 'transparent');
                var text = $(this).text();
                if (keyword != '' && text.indexOf(keyword) >= 0) {
                    $(this).css('background-color', 'rgba(250,230,160,0.5)');
                    if (searchCount == 0) {
                        treeGrid.expandAll('#sc');
                        $('html,body').stop(true);
                        $('html,body').animate({scrollTop: $(this).offset().top - 150}, 500);
                    }
                    searchCount++;
                }
            });
            if (keyword == '') {
                layer.msg("{:lang('route.Enter search content')}", {icon: 5});
            } else if (searchCount == 0) {
                layer.msg("{:lang('route.No match')}", {icon: 5});
            }
            return false;
        })


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
    });
</script>



{/block}