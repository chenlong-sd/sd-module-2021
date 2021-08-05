{extend name="frame"}

{block name="meta"}{:token_meta()}{/block}

{block name="body"}
<style>
    .layui-card{margin: 5px}
    .layui-card:hover{cursor: pointer;box-shadow: 1px 1px 2px}
</style>

<!-- 导航面包屑 -->
<div class="layui-row">
    <div class="layui-col-md3">
        <div class="layui-card" data-href="<?= admin_url('file-make') ?>">
            <div class="layui-card-header"><b>基础代码文件创建</b></div>
            <div class="layui-card-body">
                对应数据表的模型、验证器、<br/>控制器、页面的文件代码生成。
            </div>
        </div>
    </div>
    <div class="layui-col-md3">
        <div class="layui-card" data-href="<?= admin_url('field') ?>">
            <div class="layui-card-header"><b>数据表详情查询</b></div>
            <div class="layui-card-body">
                查询的字段太多了，看看是否存在某个表，复制字段注释<br/>来体验看看。
            </div>
        </div>
    </div>

</div>


{/block}

{block name="js"}
<script>
    let $ = layui.jquery;
    $('.layui-card').on('click', function(){
        let href = $(this).data('href');
        custom.frame(href, $(this).find('.layui-card-header>b').text(),{area:['90%', '90%']});
    });
</script>
{/block}