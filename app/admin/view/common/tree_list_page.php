{extend name="frame"}
<?php /** @var \sdModule\layui\tablePage\ListsPage $table */ ?>
<?php /** @var  \sdModule\layui\form\Form $search */?>
{block name="head"}
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
        function tableRender(){
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
                ,cols: [<?= $table->getFiledConfig() ?>],
                done:function (res) {
                    custom.enlarge(layer, $, '.layer-photos-demo');
                    dropdownMenu(res.data);
                    <?= $table->getDoneJs() ?>
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
        <?php if ($table->getEventMode() === $table::BUTTON_MODE){ ?>
        table.on('tool(sc)', function (obj) {
            try {
                table_page.tool_event[obj.event](obj.data);
            }catch (e) {
                notice.error('<?= lang("Operation is undefined") ?>');
            }
        });
        <?php } ?>

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
                line_data = d = data[$('.menu-down-sc').index(elem)];
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
                        tableRender();
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
    <?= $table->getJs();?>
    });
</script>



{/block}