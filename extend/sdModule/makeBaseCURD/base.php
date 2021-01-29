<?php
 /** @var \sdModule\makeBaseCURD\CURD $this */
?>

<!DOCTYPE html>
<html lang="zh-cn">
<head>
    <meta charset="UTF-8">
    <title>首页</title>
    <link rel="stylesheet" href="<?= $this->config('layui_dir'); ?>css/layui.css" media="all" />
    <style>
        body{padding: 10px}
        td .layui-form-checkbox[lay-skin=primary]{
            padding-left: 0!important;
        }
    </style>
    <script>
        // 以下为一般网络路径设置
        const DEBUG = "<?=env('APP_DEBUG')?>";
        const ROOT = "<?= $this->config('root_path') ?>";
        const EDITOR_UPLOAD = '<?=config("admin.editor_upload")?>';
        const UPLOAD_URL = '<?=admin_url("image")?>';
        const RESOURCE_URL = '<?=url("system.system/resource")?>';

        // 以下为表格的多语言设置
        const PAGE_TO = "<?=lang('page_to')?>";
        const PAGE_PAGE = "<?=lang('page_page')?>";
        const PAGE_TOTAL = function (num) {
            return "<?=lang('page_total')?>".replace(1, num);
        };
        const CONFIRM = "<?=lang('confirm')?>";
        const PAGE_ARTICLE = "<?=lang('page_article')?>";
        const FILTER_COLUMN = "<?=lang('Filter column')?>";
        const EXPORT = "<?=lang('Export')?>";
        const PRINT = "<?=lang('print')?>";

        // layui的多语言设置

        const L_LANG = {
            confirm: "<?=lang('confirm')?>",
            clear: "<?=lang('clear')?>",
            upload_exception:"<?=lang('layui upload_exception')?>",
            upload_exception_1:"<?=lang('layui upload_exception_1')?>",
            upload_exception_json:"<?=lang('layui upload_exception_json')?>",
            file_format_error:"<?=lang('layui file_format_error')?>",
            video_format_error:"<?=lang('layui video_format_error')?>",
            audio_format_error:"<?=lang('layui audio_format_error')?>",
            image_format_error:"<?=lang('layui image_format_error')?>",
            max_upload:"<?=lang('layui max_upload')?>",
            file_exceed:"<?=lang('layui file_exceed')?>",
            file_a:"<?=lang('layui file_a')?>",
            shrink:"<?=lang('layui shrink')?>",
            require:"<?=lang('layui require')?>",
            phone:"<?=lang('layui phone')?>",
            email:"<?=lang('layui email')?>",
            link:"<?=lang('layui link')?>",
            number:"<?=lang('layui number')?>",
            date:"<?=lang('layui date')?>",
            id_card:"<?=lang('layui id_card')?>",
            select:"<?=lang('layui select')?>",
            unnamed:"<?=lang('layui unnamed')?>",
            no_data:"<?=lang('layui no data')?>",
            no_matching_data:"<?=lang('layui No matching data')?>",
            request_exception:"<?=lang('layui require exception')?>",
            response_error:"<?=lang('layui response error')?>",
            upload_failed:"<?=lang('layui upload failed')?>",
        }
    </script>


</head>
<body>
<!-- 主体部分 -->

<div class="layui-card layui-anim layui-anim-fadein"  data-anim="layui-anim-scale">
    <div class="layui-card-header">辅助开发操作</div>
    <div class="layui-card-body">
        <form class="layui-form" lay-filter="tt" action="">
            <div class="layui-form-item">
                <div class="layui-inline">
                    <label class="layui-form-label">表名</label>
                    <div class="layui-input-inline">
                        <input type="tel" name="table_name" lay-verify="required" placeholder="table_name"
                               autocomplete="off" class="layui-input">
                    </div>
                    <button lay-event="pull" onclick="return false" class="layui-btn"><i class="layui-icon layui-icon-template-1"></i> 获取</button>
                </div>

                <div class="layui-inline">
                    <label class="layui-form-label">页面名称</label>
                    <div class="layui-input-inline">
                        <input type="text" name="page_name" placeholder="新闻" autocomplete="off"
                               class="layui-input">
                    </div>
                </div>
            </div>
            <div id="view"></div>
            <div class="layui-form-item">
                <div class="layui-inline">
                    <label class="layui-form-label">创建文件</label>
                    <div class="layui-input-block">
                        <?php foreach ($this->config('make_item') as $value){ ?>
                            <input type="checkbox" name="make[]" lay-skin="primary" value="<?= $value['tag'] ?>" title="<?= $value['title'] ?>" checked autocomplete="off"
                                   class="layui-input">
                        <?php } ?>
                    </div>
                </div>

            </div>
            <div class="layui-form-item">
                <div class="layui-input-block">
                    <button class="layui-btn" lay-submit lay-filter="formDemo">立即提交</button>
                    <button type="reset" class="layui-btn layui-btn-primary">重置</button>
                </div>
            </div>
        </form>
    </div>
</div>
<script>
    var formType = <?= json_encode($this->config('form_module'), JSON_UNESCAPED_UNICODE) ?>;
    var showType = [
        {value:'', title:'无'},
        {value:'image', title:'图片'},
        {value:'text', title:'文本'},
    ];
</script>

<script id="demo" type="text/html">
    <table class="layui-table">
        <thead>
        <tr>
            <th style="text-align: center">字段名</th>
            <th style="text-align: center">字段标题</th>
            <th style="text-align: center">表单类型</th>
            <th style="text-align: center;width: 10px" class="S">S</th>
            <th style="text-align: center">列表页展示字段类型</th>
            <th style="text-align: center" id="inin">字段初始数据展示</th>
        </tr>
        </thead>
        <tbody>
        {{# layui.each(d, function(index, item){ }}
        {{# let join = typeof item.join != 'string' ? JSON.stringify(item.join) : item.join }}
        <tr>
            <td style="text-align: center"><div class="layui-input-inline">{{ item.column_name }}</div></td>
            <td>
                <input type="text" name="{{ item.column_name }}[label]"  value="{{ item.column_comment }}" placeholder="请输入"  class="layui-input">
            </td>
            <td>
                <select name="{{ item.column_name }}[type]" >
                    <option value="" >无</option>
                    {{#  layui.each(formType, function(index1, item1){ }}
                    {{# if(item.form_type == item1.value) {}}
                    <option value="{{item1.value}}" selected>{{item1.title}}</option>
                    {{# }else{ }}
                    <option value="{{item1.value}}">{{item1.title}}</option>
                    {{# } }}
                    {{# }); }}
                </select>
            </td>
            <td>
                <input type="checkbox" name="{{ item.column_name }}[quick_search]" lay-skin="primary"  value="1" class="layui-input">
            </td>
            <td>
                <select name="{{ item.column_name }}[show_type]">
                    {{#  layui.each(showType, function(index2, item2){ }}
                    {{#   if(item.show_type == item2.value) { }}
                    <option value="{{item2.value}}" selected>{{item2.title}}</option>
                    {{# }else{ }}
                    <option value="{{item2.value}}">{{item2.title}}</option>
                    {{# } }}
                    {{# }); }}
                </select>

            </td>
            <td>
                <input ondblclick="db(this)" type="text" name="{{ item.column_name }}[join]"  value='{{ join ? join : "" }}' placeholder=""  class="layui-input inin">
            </td>
        </tr>
        {{# }); }}
        </tbody>
    </table>
</script>




</body>
<script type="text/javascript" src="<?= $this->config('layui_dir')  ?>/layui.all.js"></script>

<script id="join" type="text/html">
    <div style="padding: 10px">
        <form class="layui-form" id="join-form" lay-filter="j" action="">
            <div class="layui-form-item">
                <div class="layui-input-inline">
                    <input type="text" name="[]" required  lay-verify="required" placeholder="示例：1=正常" autocomplete="off" class="layui-input">
                </div>
                <div class="layui-input-inline" >
                    <button type="button" lay-event="add"  class="layui-btn layui-btn-normal">加一个</button>
                    <button type="button" lay-event="final"  class="layui-btn layui-btn-normal">完成</button>
                </div>
            </div>

        </form>
    </div>

</script>



<script id="key-value" type="text/html">

    <div class="layui-form-item">
        <div class="layui-input-inline">
            <input type="text" name="[]" required  lay-verify="required" placeholder="示例：1=正常" autocomplete="off" class="layui-input">
        </div>
        <div class="layui-input-inline" >
            <button type="button" lay-event="del"  class="layui-btn layui-btn-danger"><i class="layui-icon layui-icon-delete"></i></button>
        </div>
    </div>

</script>


<script>

    var db ;
    var join ;
    layui.use(['form', 'jquery', 'util', 'laytpl'], function () {
        var form = layui.form, $ = layui.jquery, util = layui.util,laytpl = layui.laytpl;

        $(document).on('mouseover ', '.inin', function () {
            layer.tips('可填写：\n【表:值字段=显示字段】或者【双击编辑】更多', this, {tips:3})
        });

        $(document).on('mouseover ', '.S', function () {
            layer.tips('勾选搜索字段', this, {tips:3});
        });

        util.event('lay-event',{
            // 获取表信息
            pull:function () {
                getTableInfo(form.val("tt").table_name, res => {
                    laytpl(document.getElementById('demo').innerHTML).render(res.data, function(html){
                        document.getElementById('view').innerHTML = html;
                        form.render('select');
                        form.render('radio');
                        form.render('checkbox');
                    });
                });
                return false;
            },
            // 加一个输入框
            add: () => {
                $('#join-form').append($('#key-value').html())
            },
            // 删一个输入框
            del: (obj) => {
                $(obj).parents('.layui-form-item').remove();
            },
            // 设置成功
            final: () => {
                join.value = JSON.stringify(form.val("j"));
                layer.closeAll()
            }
        });

        function getTableInfo(table_name, call) {
            let load = layer.msg('正在请求数据，请稍后...', {icon: 16,shade:0.1,time:0});
            $.ajax({
                data:{ table_name: table_name}
                ,success:function (res) {
                    layer.close(load);
                    if (res.code === 200) {
                        call(res);
                    }else{
                        layer.alert(res.msg, {icon:7, title:'提示'});
                    }
                }
            });
        }

        db = (obj) => {
            layer.open({
                type: 1,
                area: ['500px', '600px'],
                content: $('#join').html()
            });
            join = obj;
        };


        form.on('submit(formDemo)', function (data) {
            let load = layer.msg('请稍后...', {icon: 16,time:0});
            $.ajax({
                data:{table:data.field.table_name,make:make_item(data.field)}
                , success:function (res) {
                    layer.close(load);
                    if (res.code === 200) {
                        make(data);
                    }else{
                        layer.confirm(res.msg, {icon:3}, function (index) {
                            make(data);
                        })
                    }
                }
            });

            return false;
        });

        /**
         *
         * 提交创建文件
         * */
        function make(data){
            let load = layer.msg('请稍后...', {icon: 16,time:0});
            $.ajax({
                type: 'post'
                , data: data.field
                , success: function (res) {
                    layer.close(load);
                    if (res.code === 200) {
                        layer.alert('成功！', {icon:1, title:"提示"},function () {
                            location.reload();
                        });
                    } else {
                        layer.alert(res.msg, {icon:5, title: '错误'});
                    }
                },
                error: function (err) {
                    layer.close(load);
                    console.log(err);
                }
            });
        }

        /**
         * 创建的项目
         * @param {object} $data
         * @returns {[]}
         */
        function make_item($data) {
            let make_item = []
            for (const $dataKey in $data) {
                if (/^make\[.*\]$/.test($dataKey) && $data.hasOwnProperty($dataKey)){
                    make_item.push($data[$dataKey]);
                }
            }
            return make_item;
        }
    });

</script>

</html>