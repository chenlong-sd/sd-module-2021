{extend name="frame"}

{block name="title"}资源浏览{/block}
{block name="head"}
<style>
    #box-sc{
        position: relative;
    }
    .layui-form-checkbox[lay-skin=primary]{
        position: absolute;
    }
    .cs-is{
        width: 100px;
        max-height: 100px;
        margin: 5px;
        overflow: hidden;
        position: relative;
    }
    .cs-iss{
        display: inline-block;
        border-radius: 3px;
        box-shadow: 1px 1px 5px #aaa;
        margin: 10px;
    }
    .cs-iss:hover {
        box-shadow: 2px 2px 5px #666;
    }
    .cs-is img{
        max-width: 100%;
    }
    #sc-confirm{
        float: right;
        text-align: center;
        position: fixed;
        right: 10px;
        bottom: 10px;
    }

</style>

{/block}

{block name="body"}


<div class="layui-container">
    <div class="layui-row">
        <div class="layui-card">
            <div class="layui-card-header">资源选择</div>
            <div class="layui-card-body">
                <form id="box-sc" lay-filter="formTest" class="layui-form"></form>
                <div id="test1"></div>
            </div>
        </div>
        <button type="button" id="sc-confirm" class="layui-btn">确认选择</button>
    </div>
</div>

{/block}
{block name="js"}


<script>

    let vars = '{$Request.get.vars ?: "image"}';
    let type = '{$Request.get.type}';
    let value = [];
    let search = '';

    //执行一个laypage实例
    layui.laypage.render({
        elem: 'test1' //注意，这里的 test1 是 ID，不用加 # 号
        ,count: '{$count}' //数据总数，从服务端得到
        ,limits:[10,20,50,100,200,500,1000]
        ,limit:20
        ,layout:['count', 'prev', 'page', 'next', 'limit', 'refresh', 'skip']
        ,jump: function(obj, first){
            getResource(obj.curr, obj.limit, search);
        }
    });

    console.log(Thumbnail)

    function getResource(page, limit, where) {
        layui.jquery.ajax({
            data:{ page:page, limit:limit, where:where}
            ,success:function (res) {
                let html = '';
                for (let pro in res.data) {
                    if (res.data.hasOwnProperty(pro)) {
                        let path = /^http.*$/.test(res.data[pro].path) ? res.data[pro].path
                            : '__PUBLIC__/' + thumbnailUrl(res.data[pro].path);
                        html += '<div class="cs-iss" title="' + res.data[pro].tag + '">' +
                            '<div class="cs-is">' +
                            '<input type="checkbox" name="r[]" value="' + res.data[pro].path + '" lay-skin="primary">' +
                            '<img src="' + path + '" alt="' + path + '">' +
                            '</div>' +
                            '</div>';
                    }
                }
                layui.jquery('#box-sc').html(html);
                layui.form.render();
            }
        });
    }

    /**
     * 缩略图处理
     * @param path
     * @returns {string}
     */
    function thumbnailUrl(path){
        if(!Thumbnail) return path;

        let arr = path.split('.');
        let suffix = arr.pop();
        arr.join('.')
        return arr.join('.') + '_thumbnail.' + suffix;
    }

    layui.jquery(document).off('click', '.cs-is').on('click', '.cs-is', function (e) {
        let v = layui.jquery(this).find('input[type=checkbox]').prop('checked');

        if (!v && ((type === 'radio' && Object.values(layui.form.val("formTest")).length >= 1)
            || (type !== 'radio' && Object.values(layui.form.val("formTest")).length >= 9))) {
            notice.warning('选择数量已达最大值');
            return false;
        }
        layui.jquery(this).find('input[type=checkbox]').prop('checked', !v);
        value = layui.form.val("formTest");
        layui.form.render();
        layui.form.val("formTest", value);
    });

    layui.jquery('#sc-confirm').on('click', function () {
        type === 'radio'
            ? parent.window[vars].defaults(Object.values(value).join(','))
            : parent.window[vars].push(Object.values(value));
        parent.layer.closeAll();
    })

</script>
{/block}