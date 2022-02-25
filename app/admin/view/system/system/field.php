{extend name="frame"}

{block name="meta"}{:token_meta()}{/block}


{block name="body"}
<style>
    body{background: #f2f2f2}
    #field-show{word-wrap:break-word;}
    .layui-card{margin: 5px}
    #table--list{height: 420px;overflow: auto;margin-top: 5px}
    .layui-elem-quote{position: relative;min-height: 54px}
    #copy{position: absolute;bottom: 0;right: 0}
    #sql{
        color: #779eea;
    }
    #sql .remark{
        color: #86ac6d;
    }
    #sql .field{
        color: #9776a9;
    }
    #sql .b {
        display: inline-block;
        width: 30px;
    }
    #field_remark .field_name_show{
        min-width: 200px;
    }
    .float{
        border: none;
    }
</style>
<div class="layui-row">
    <form action="" class="layui-form" lay-filter="formTest">
        <div class="layui-col-md3">
            <div class="layui-card">
                <div class="layui-card-header"><b>数据表</b></div>
                <div class="layui-card-body">
                    <div class="layui-input-inline">
                        <input type="text" name="table_input" placeholder="请输入表名或注释" autocomplete="off" class="layui-input">
                    </div>
                    <button class="layui-btn" type="button" id="reset">重置</button>
                    <div id="table--list"></div>
                </div>
            </div>
        </div>
        <div class="layui-col-md9">
            <div class="layui-card">
                <div class="layui-card-header"><b>数据表详细信息</b></div>
                <div class="layui-card-body">
                    <blockquote class="layui-elem-quote layui-quote-nm">
                        <div id="field-show" class="layui-word-aux"></div>
                        <div class="layui-word-aux"><button id="copy" type="button" class="layui-btn layui-btn-primary layui-btn-xs">复制</button></div>
                    </blockquote>
                    <div class="layui-input-inline">
                        <input type="text" name="table_alias" placeholder="请输入表别名" autocomplete="off" class="layui-input">
                    </div>
                    <div class="layui-input-inline" pane>
                        <input type="radio" name="field_show_type" lay-filter="show_type" value="1" title="字串" checked>
                        <input type="radio" name="field_show_type"  lay-filter="show_type" value="2" title="数组">
                    </div>
                    <button class="layui-btn" type="button" id="reset1">重置</button>


                    <div class="layui-collapse" id="field-info">
                        <div class="layui-colla-item">
                            <h2 class="layui-colla-title">表字段注释
                                <button class="layui-btn layui-btn-primary layui-border-blue float" type="button">
                                    浮动信息<i class="layui-icon layui-icon-upload-drag"></i>
                                </button></h2>
                            <div class="layui-colla-content float-content">
                                <div id="field_remark" class="layui-form"></div>
                            </div>
                        </div>
                        <div class="layui-colla-item">
                            <h2 class="layui-colla-title">表结构详情
                                <button class="layui-btn layui-btn-primary layui-border-blue float" type="button">
                                    浮动信息<i class="layui-icon layui-icon-upload-drag"></i>
                                </button></h2>
                            <div class="layui-colla-content float-content">
                                <div id="sql"></div>
                            </div>
                        </div>
                        <div class="layui-colla-item">
                            <h2 class="layui-colla-title">表数据查看
                                <button class="layui-btn layui-btn-primary layui-border-blue float d" type="button">
                                    浮动信息<i class="layui-icon layui-icon-upload-drag"></i>
                                </button></h2>
                            <div class="layui-colla-content float-content">
                                <div id="table-data-sc">
                                    <table class="layui-table" id="sc-tabvkle"></table>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </form>
</div>


{/block}

{block name="js"}
<?php
$table_data_ = \think\facade\Db::query("SELECT
	TABLE_NAME,
	TABLE_COMMENT 
FROM
	INFORMATION_SCHEMA.TABLES 
WHERE
	TABLE_SCHEMA = '" . env('database.database') . "'");

$table_data_ = array_map(function ($v){
    return $v['TABLE_NAME'] . '（' . trim($v['TABLE_COMMENT']) . '）';
}, $table_data_);

?>


<script>
    let $ = layui.jquery;
    let table_ = <?= json_encode($table_data_, JSON_UNESCAPED_UNICODE) ?>;
    $('#table--list').css('height', $(window).height() - 120 + 'px')

    // 字段相关变量
    let field_ = [], alias_ = '', show_type = 1,current_table = '', current_field_arr, current_table_origin = '' ;

    tableList(table_);

    // 重置表名搜索
    $('#reset').on('click', function () {
        $('[name=table_input]').val('');
        tableList(table_);
    });

    // 重置表别名
    $('#reset1').on('click', function () {
        $('[name=table_alias]').val('');
        alias_ = '';
        fieldShow(field_, alias_, show_type);
    });

    // 监听键盘
    $(document).on('keyup', function (e){
        let code  = $('[name=table_input]').val();
        alias_    = $('[name=table_alias]').val();
        tableList(table_, code);
        fieldShow(field_, alias_, show_type);
    });

    $('.float').on('click', function (e) {
        e.stopPropagation();
        let html = $(this).parents('.layui-colla-item').find('.float-content').html().trim();
        if (html === '<div id="sql" class="layui-colla-content"></div>' || html === '<div id="field_remark" class="layui-form"></div>') return notice.warning('无信息可浮动');
        let width  = $(this).hasClass('d') ? '80%' : '500px';
        let height = $(this).hasClass('d') ? '80%' : 'auto';

        layer.open({
            type: 1,
            title:`${current_table}`,
            shade: 0,
            moveOut: true,
            content: $(this).hasClass('d') ? `<div class="float-win" style="margin: 10px"><table class="layui-table"></table></div>` : `<div class="float-win" style="margin: 10px">${html}</div>`, //这里content是一个普通的String
            area: [width, height],
            success: function(layero, index){
                layui.jquery(layero).on('click', function (){
                    layero.css('z-Index', ++layer.zIndex);
                });
                tableInit(current_field_arr, current_table_origin, layui.jquery(layero).find('.layui-table'))
                layer.setTop(layero); //重点2
            }
        });
    });

    // 复制
    $('#copy').on('click', function () {
        copyToClip($('#field-show').html())
    });

    // 表选择
    layui.form.on('radio(table)', function(data){
        let table = data.value.substr(0, data.value.indexOf('（'));
        current_table = data.value;
        current_table_origin = table;
        let load = custom.loading('数据加载中，请稍后...');
        $.ajax({
            url:"<?= admin_url('field-query')?>"
            ,  data:{table:table}
            ,  success:function (res){
                tableInit(current_field_arr = fieldList(res.data.sql), table);
                let sql =  res.data.sql.replace(/('(((?!').)*)')/g, "<span class=\"remark\">$1</span>");
                sql =  sql.replace(/(`\w+`)/g, "<span class=\"field\">$1</span>");
                sql =  sql.replace(/\n/g, "<br/>");
                sql =  sql.replace(/(<br\/>(?!\)))/g, "$1<span class='b'></span>");
                $('#sql').html(sql);
                layer.close(load);
            }
        })
    });

    // 类型选择
    layui.form.on('radio(show_type)', function(data){
        show_type = data.value;
        fieldShow(field_, alias_, show_type);
    });

    // 字段选择
    layui.form.on('checkbox', function(data){
        let like = layui.form.val("formTest");
        field_ = [];
        for (const k in like) {
            /^like\[/.test(k) && field_.push(like[k]);
        }
        fieldShow(field_, alias_, show_type);
    });

    /**
     *
     * @param field
     * @param alias
     * @param showType
     * @returns {string}
     */
    function fieldShow(field, alias, showType)
    {
        let arr = [];
        field.map((v)=>{
            showType == 1 ? arr.push(alias ? `${alias}.${v}` : v) : arr.push(alias ? `'${alias}.${v}'` : `'${v}'`);
        });
        $('#field-show').html(arr.join(','));
    }


    /**
     * 数据表的字段列表
     * @param sql
     */
    function fieldList(sql) {
        let field =  sql.match(/(\s*`.+,)\n/g);
        let fieldArr = [];
        let fieldStr = '<table>';
        for (let i = 0; i < field.length; i++) {
            let field_name = field[i].match(/`(\w+)`/);
            if (field_name){
                let field_title = field[i].match(/'(((?!').)+)',/);
                field_title = field_title ? field_title[1] : '';
                field_title && fieldArr.push({field: field_name[1], title: field_title})
                fieldStr += `<tr><td class="field_name_show"><input type="checkbox" lay-skin="primary"  name="like[]" title="" value="${field_name[1]}">${field_name[1]}</td><td>${field_title}</td></tr>`;
            }
        }
        fieldStr += '</table>';
        $('#field_remark').html(fieldStr).parent('div').addClass('layui-show');
        layui.form.render();
        return fieldArr;
    }

    /**
     * 表列表数据的渲染
     * @param {Array} table 表数据
     * @param {String} filter 过滤
     */
    function tableList(table, filter) {
        let html = '';
        table.map((v)=>{
            if (filter) {
                v.indexOf(filter) >= 0 && (html += '<input type="radio" lay-filter="table" name="table" value="'+ v +'" title="'+ v +'"><br/>');
            }else{
                html += '<input type="radio" lay-filter="table" name="table" value="'+ v +'" title="'+ v +'"><br/>';
            }
        })
        $('#table--list').html(html);
        layui.form.render();
    }

    /**
     * 复制
     * @param content
     * @param message
     */
    function copyToClip(content, message) {
        var aux = document.createElement("input");
        aux.setAttribute("value", content);
        document.body.appendChild(aux);
        aux.select();
        document.execCommand("copy");
        document.body.removeChild(aux);
        if (message == null) {
            notice.success("复制成功");
        } else {
            notice.success(message);
        }
    }



    function tableInit(field, table_name, el) {
        layui.table.render({
            elem: el ? el : '#sc-tabvkle'
            ,height: 500
            ,url: '{:url("getTableData")}' //数据接口
            ,page: true //开启分页
            ,limit:1000
            ,limits:[1000, 5000]
            ,where:{
                table_name:table_name
            }
            ,cols: [field]
        });
    }
</script>
{/block}