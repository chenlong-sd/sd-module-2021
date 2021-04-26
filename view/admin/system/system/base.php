{extend name="frame"}

<?php

// ======================================
// 此为自定义页面的继承模板文件，复制重命名文件即可
// 更多的模块重写，查看frame.php文件
// ======================================

?>

{block name="meta"}{:token_meta()}{/block}

{block name="body"}
<?php
$page_group = array_column($base, 'group_name', 'group_id');
$tab_group  = $page_group;
$page_group = array_merge($page_group, ['default_group' => '默认分组']);
$page_base  = array_column($base, null, 'id');
?>
<!-- 导航面包屑 -->
<div style="background-color: #fff;overflow: hidden">
    <div class="layui-tab layui-tab-brief" lay-filter="docDemoTabBrief">
        <ul class="layui-tab-title">
            <?php if (env('APP_DEBUG')){ ?>
                <li class="layui-this"><b>更新设置</b></li>
            <?php } ?>
            <?php foreach ($tab_group as $gI => $pgI){  ?>
                <li><?= $pgI ?></li>
            <?php } ?>
        </ul>
        <div class="layui-tab-content">
            <?php if (env('APP_DEBUG')){ ?>
                <div class="layui-tab-item layui-show">
                    <blockquote class="layui-elem-quote">
                        取值方式：<span class="layui-badge-rim">base_config($key, $default)</span> 或
                        <span class="layui-badge-rim">\app\common\service\BaseConfig::get($key, $default)</span>
                    </blockquote>
                    <form action="" lay-filter="base-config" class="layui-form" style="width: 800px">
                        <div class="layui-form-item">
                            <div class="layui-inline">
                                <label class="layui-form-label">选择分组</label>
                                <div class="layui-input-block">
                                    <select lay-filter="group">
                                        <option value=""></option>
                                        <option value="default_group">默认分组</option>
                                    </select>
                                </div>
                            </div>
                            <div class="layui-inline">
                                <label class="layui-form-label">修改配置</label>
                                <div class="layui-input-block">
                                    <select lay-filter="have" lay-search></select>
                                </div>
                            </div>
                        </div>
                        <hr class="layui-border-red">
                        <div class="layui-form-item">
                            <div class="layui-inline">
                                <label class="layui-form-label">分组配置</label>
                                <div class="layui-input-inline">
                                    <input type="text" maxlength="32" name="group_id" placeholder="标识，eg：system_param" autocomplete="off" class="layui-input">
                                </div>
                            </div>
                            <div class="layui-inline">
                                <label class="layui-form-label">分组名称</label>
                                <div class="layui-input-inline">
                                    <input type="text"  maxlength="32" name="group_name" placeholder="名称，eg：系统参数" autocomplete="off" class="layui-input">
                                </div>
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <div class="layui-inline">
                                <label class="layui-form-label">配置标识</label>
                                <div class="layui-input-inline">
                                    <input type="text" maxlength="32" name="key_id"  placeholder="eg:company_name" autocomplete="off" class="layui-input">
                                </div>
                            </div>
                            <div class="layui-inline">
                                <label class="layui-form-label">配置名称</label>
                                <div class="layui-input-inline">
                                    <input type="text" maxlength="32" name="key_name" placeholder="eg:公司名称" autocomplete="off" class="layui-input">
                                </div>
                            </div>
                            <div class="layui-inline">
                                <button id="sc-delete" style="display: none" class="layui-btn layui-btn-danger">删除配置</button>
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label">表单类型</label>
                            <div class="layui-input-block">
                                <select name="form_type">
                                    <option value=""></option>
                                    <option value="text">文本</option>
                                    <option value="image">单图</option>
                                    <option value="images">多图</option>
                                    <option value="select">下拉</option>
                                    <option value="radio">单选</option>
                                    <option value="switchSc">开关</option>
                                    <option value="textarea">文本域</option>
                                    <option value="u_editor">富文本</option>
                                    <option value="checkbox">多选</option>
                                    <option value="date">日期</option>
                                    <option value="time">时间</option>
                                </select>
                            </div>
                        </div>
                        <div class="layui-form-item layui-form-text">
                            <label class="layui-form-label">可选值</label>
                            <div class="layui-input-block">
                                <textarea name="options" style="min-height: 200px" placeholder="key=value,多个值请换行" class="layui-textarea"></textarea>
                            </div>
                        </div>
                        <input type="hidden" name="id" value="">
                        <div class="layui-form-item">
                            <div class="layui-input-block">
                                <button class="layui-btn" lay-submit="" lay-filter="config">立即提交</button>
                                <button  id="base-config" class="layui-btn layui-btn-primary" type="reset">清空</button>
                            </div>
                        </div>
                    </form>
                </div>
            <?php } ?>
            <?php foreach ($tab_group as $gI => $pgI){  ?>
                <div class="layui-tab-item">
                    <iframe style="min-height: 500px;width: 100%" src="<?= url('system.System/baseConfig?group_id=' . $gI) ?>" frameborder="0"></iframe>
                </div>
            <?php } ?>
        </div>
    </div>
</div>



{/block}

{block name="js"}


<script>

    var form = layui.form,$ = layui.jquery;
    $('.layui-tab-title').find('li:first-child').click();
    $('iframe').css("height", window.innerHeight - 115 + 'px');

    var group_data = <?= json_encode($page_group, 256) ?>;
    var base_data  = <?= json_encode($page_base, 256) ?>;
    groupRender(group_data, 'select[lay-filter=group]');
    haveRender(base_data, 'select[lay-filter=have]');
    form.render();

    //监听提交
    form.on('submit(config)', function(data){
        ScXHR.ajax({
            type:'post',
            data: data.field,
            success:function (res){
                if (res.code === 200) {
                    notice.success('成功');
                    if (!data.field.id) {
                        data.field.id = res.data.id;
                    }else{
                        delete group_data[base_data[data.field.id].group_id];
                    }
                    group_data[data.field.group_id] = data.field.group_name;
                    base_data[data.field.id] = data.field;
                    groupRender(group_data, 'select[lay-filter=group]');
                    haveRender(base_data, 'select[lay-filter=have]');
                    form.render();
                    $('#base-config').click();
                    $('input[name=id]').val('');
                    $('#sc-delete').hide();
                } else {
                    notice.warning(res.msg);
                }
            }
        });
        return false;
    });

    form.on('select(group)', function(data){
        $('input[name=group_id]').val(data.value);
        $('input[name=group_name]').val(group_data.hasOwnProperty(data.value) ? group_data[data.value] : '');
        form.render();
        return false;
    });

    form.on('select(have)', function(data){
        form.val('base-config', base_data[data.value]);
        $('#sc-delete').show();
        return false;
    });

    function groupRender(obj, selector) {
        let html = '<option value=""></option>';
        for (let objKey in obj) {
            html += `<option value="${objKey}">${obj[objKey]}</option>`;
        }
        $(selector).html(html);
    }

    function haveRender(obj, selector) {
        var group = {};
        for (let objKey in obj) {
            let group_id = obj[objKey].group_id;
            if (!group.hasOwnProperty(group_id)){
                group[group_id] = obj[objKey].group_name
            }
        }

        let html = '<option value=""></option>';
        for (let k in group) {
            html += `<optgroup label='${group[k]}'>`
            for (let objKey1 in obj) {
                if (k  === obj[objKey1].group_id){
                    html += `<option value="${objKey1}">${obj[objKey1].key_name}</option>`;
                }
            }
            html += "</optgroup>";
        }
        $(selector).html(html);
    }

    $('#sc-delete').on('click', function (){
        let id = $('input[name=id]').val();
        ScXHR.confirm('确认删除吗？').ajax({
            url:'<?= url("deleteConfig") ?>',
            type:'post',
            data:{
                id:id
            },
            success: function (res) {
                if (res.code === 200) {
                    notice.success('成功！');
                    delete base_data[id];
                    groupRender(group_data, 'select[lay-filter=group]');
                    haveRender(base_data, 'select[lay-filter=have]');
                    form.render();
                    $('#base-config').click();
                    $('input[name=id]').val('');
                    $('#sc-delete').hide();
                }else{
                    notice.warning('失败！')
                }
            }
        })
        return false;
    });
</script>


{/block}