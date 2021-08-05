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
                    <div class="layui-collapse">
                        <div class="layui-colla-item">
                            <h2 class="layui-colla-title">表字段注释</h2>
                            <div class="layui-colla-content">
                                <div id="field_remark" class="layui-form"></div>
                            </div>
                        </div>
                        <div class="layui-colla-item">
                            <h2 class="layui-colla-title">表结构详情</h2>
                            <div id="sql" class="layui-colla-content"></div>
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
    $('#table--list').css('height', $(window).height() - 135 + 'px');

    // 字段相关变量
    let field_ = [], alias_ = '', show_type = 1;

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

    // 复制
    $('#copy').on('click', function () {
        copyToClip($('#field-show').html())
    });

    // 表选择
    layui.form.on('radio(table)', function(data){
        let table = data.value.substr(0, data.value.indexOf('（'));
        let load = custom.loading('数据加载中，请稍后...');
        $.ajax({
            url:"<?= admin_url('field-query')?>"
            ,  data:{table:table}
            ,  success:function (res){
                fieldList(res.data.sql);
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
        let fieldStr = '<table>';
        for (let i = 0; i < field.length; i++) {
            let field_name = field[i].match(/`(\w+)`/);
            if (field_name){
                let field_title = field[i].match(/'(((?!').)+)',/);
                field_title = field_title ? field_title[1] : '';
                fieldStr += `<tr><td class="field_name_show"><input type="checkbox" lay-skin="primary"  name="like[]" title="" value="${field_name[1]}">${field_name[1]}</td><td>${field_title}</td></tr>`;
            }
        }
        fieldStr += '</table>';
        $('#field_remark').html(fieldStr).parent('div').addClass('layui-show');
        layui.form.render();
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
</script>
{/block}