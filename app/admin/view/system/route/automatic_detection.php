{extend name="frame"}

<?php

// ======================================
// 此为自定义页面的继承模板文件，复制重命名文件即可
// 更多的模块重写，查看frame.php文件
// ======================================

?>

{block name="meta"}{:token_meta()}{/block}


{block name="body"}
<!-- 导航面包屑 -->
<div class="layui-container">
    <div class="layui-row layui-form">

        <?php if (!empty($accessible)){ ?>

        <?php foreach ($accessible as $index => $item) {  ?>
            <blockquote class="layui-elem-quote">
                <?= $item['controller_name']?>
                <input type="hidden" name="controller[]" value="<?= $item['controller_name']?>">
            </blockquote>
            <div class="layui-form-item">
                <label class="layui-form-label">选择父节点</label>
                <div class="layui-input-block">
                    <div id="xm-id-<?= $index ?>" class="xm-select-demo"></div>
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">新增节点</label>
                <div class="layui-input-block">
                    <?php foreach ($item['accessible'] as $newRoute) {?>
                        <div>
                            <input type="checkbox" name="new_route[<?= $index ?>][]" title="<?= $newRoute['title'] ?>(<?= $newRoute['route'] ?>)" value="<?= $newRoute['title'] ?>(<?= $newRoute['route'] ?>)" lay-skin="primary" checked>
                        </div>
                    <?php } ?>
                </div>
            </div>
        <?php } ?>

        <div class="layui-form-item">
            <div class="layui-input-block">
                <button class="layui-btn" lay-submit lay-filter="formDemo">立即提交</button>
                <button type="reset" class="layui-btn layui-btn-primary">重置</button>
            </div>
        </div>
        <?php } else { ?>
            <fieldset class="layui-elem-field layui-field-title">
                <legend style="margin-left: 40%;">没有检测到有新的访问节点</legend>
            </fieldset>
        <?php }  ?>
    </div>
</div>


{/block}

{block name="js"}
<script src="__PUBLIC__/admin_static/layui/dist/xm-select.js"></script>

<script>

    let $index = <?= $index ?? -1 ?>;
    let xm = [];
    if ($index >= 0) {
        for (let i = 0; i <= $index; i++) {
            xm[i] = xmSelect.render({
                el: `#xm-id-${i}`,
                model: { label: { type: 'text' } },
                radio: true,
                clickClose: true,
                tree: {
                    show: true,
                    strict: false,
                    expandedKeys: [ -1 ],
                },
                height: 'auto',
                data(){
                    return <?= json_encode($parentNode, JSON_UNESCAPED_UNICODE) ?>;
                }
            })
        }
    }

    layui.form.on('submit(formDemo)', function(data){
        data.field.parent = [];
        xm.map((v, k)=>{
            data.field.parent[k] = v.getValue('valueStr');
        });
        ScXHR.ajax({
            url:'',
            type:'post',
            data: data.field,
            success:function (res){

            }
        });
        return false;
    });


</script>


{/block}