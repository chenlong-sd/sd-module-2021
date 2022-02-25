{extend name="frame"}
<?php /** @var \sdModule\layui\form\Form $form */ ?>
{block name="title"}{$page_name ?: ''}{/block}
{block name="meta"}{:token_meta()}
<style>
    /*pre {outline: 1px solid #ddd; background-color:#f2f2f2;padding: 5px; box-sizing: border-box;font-family: auto;}*/
    .layui-table td{
        padding: 3px;
    }
    .layui-tab .layui-input{
        height: 30px;
    }
    .sc-mn{
        box-shadow: inset #f1d1d1 0 0 12px;
        animation: ;
    }
</style>
<link rel="stylesheet" href="__PUBLIC__/admin_static/codeMirror/codemirror.css">
<link rel="stylesheet" href="__PUBLIC__/admin_static/codeMirror/darcula.css">

{/block}
{block name="body"}
<form class="layui-form  layui-form-pane" lay-filter="sc" action="">
    <input type="hidden" name="id" value="<?= $api['id'] ?? '' ?>">
    <input type="hidden" name="api_module_id" value="<?= $data['id'] ?>">
    <div class="layui-form-item">
        <label class="layui-form-label">接口名</label>
        <div class="layui-input-block" style="position: relative">
            <input type="text" name="api_name" value="<?= $api['api_name'] ?? '' ?>" required lay-verify="required" placeholder="请输入名字" autocomplete="off"
                   class="layui-input">
            <?php if (!request()->get('see')) { ?>
                <button class="layui-btn layui-btn-normal" lay-submit lay-filter="formDemo"
                        style="position: absolute;right: 0;top:0">保存
                </button>
            <?php } ?>
        </div>
    </div>
    <div class="layui-form-item" pane>
        <label class="layui-form-label">请求方式</label>
        <div class="layui-input-block" style="position: relative">
            <input type="radio" name="method" value="get" <?= (isset($api['method']) && $api['method'] == 'get' || empty($api['method'])) ? 'checked' : '' ?> title="GET" class="layui-input">
            <input type="radio" name="method" value="post" <?= isset($api['method']) && $api['method'] == 'post' ? 'checked' : '' ?>  title="POST" class="layui-input">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">请求路径</label>
        <div class="layui-input-block" style="position: relative">
            <input type="text" name="path" value="<?= $api['path'] ?? '' ?>" required lay-verify="required" placeholder="请输入请求路径" autocomplete="off"
                   class="layui-input">
            <button id="send" class="layui-btn layui-btn-primary sc-mn" style="position: absolute;right: 0;top:0">
                <i class="layui-icon layui-icon-release"></i> 模拟请求
            </button>

            <select name="path_no" lay-filter="path_no" id="">
                <?php foreach (explode('|-|', $data['url_prefix']) as $ic_pre){ ?>
                    <option value="<?=$ic_pre?>"><?=$ic_pre?></option>
                <?php } ?>
            </select>

        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">token参数</label>
        <div class="layui-input-block" style="position: relative">
            <input type="text" name="token" value="<?= $data['token'] ?? '' ?>" placeholder="请输入token参数：key=value&key=value" autocomplete="off" class="layui-input">
        </div>
    </div>

    <div class="layui-tab  layui-tab-brief">
        <ul class="layui-tab-title">
            <li class="layui-this"> GET 参数</li>
            <li>POST 参数</li>
            <li>HEAD 参数</li>
            <li>响应描述</li>
        </ul>
        <div class="layui-tab-content">
            <div class="layui-tab-item layui-show sc-param-get">
                <div class="sc-api-add">
                    新增
                    <input type="number" autocomplete="off" value="1" class="layui-input layui-input-inline" style="width: 50px">
                    个参数
                    <button type="button" class="layui-btn layui-btn-sm sc-api-add-number">新增</button>
                    <button type="button" class="layui-btn layui-btn-sm layui-btn-primary sc-api-cp"><i class="layui-icon layui-icon-file"></i>复制</button>
                </div>

                <table class="layui-table">
                    <thead>
                    <tr>
                        <td style="width: 100px">类型</td>
                        <td>key</td>
                        <td>value</td>
                        <td>描述</td>
                        <td style="width: 42px"></td>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($param[\app\admin\enum\ApiEnumParamType::GET] ?? [] as $item){ ?>
                        <tr>
                            <td>
                                <select name="param_type">
                                    <option value="1" <?= $item['param_type'] == 1 ? "selected" : '' ?>>文本</option>
                                    <option value="2" <?= $item['param_type'] == 2 ? "selected" : '' ?>>文件</option>
                                </select>
                            </td>
                            <td><input type="text" name="name" value="<?=$item['name']?>" autocomplete="off" class="layui-input"></td>
                            <td><input type="text" name="test_value" value="<?=$item['test_value']?>"  autocomplete="off" class="layui-input"></td>
                            <td><input type="text" name="describe" value="<?=$item['describe']?>"  autocomplete="off" class="layui-input"></td>
                            <td>
                                <button class="layui-btn  layui-btn-danger layui-btn-sm sc-param-del"><i
                                            class="layui-icon layui-icon-delete"></i></button>
                            </td>
                        </tr>
                    <?php } ?>
                    <tr>
                        <td>
                            <select name="param_type">
                                <option value="1">文本</option>
                                <option value="2">文件</option>
                            </select>
                        </td>
                        <td><input type="text" name="name" autocomplete="off" class="layui-input"></td>
                        <td><input type="text" name="test_value" autocomplete="off" class="layui-input"></td>
                        <td><input type="text" name="describe" autocomplete="off" class="layui-input"></td>
                        <td>
                            <button class="layui-btn sc-param-del  layui-btn-danger layui-btn-sm"><i
                                        class="layui-icon layui-icon-delete"></i></button>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
            <div class="layui-tab-item sc-param-post">
                <div class="sc-api-add">
                    新增
                    <input type="number" autocomplete="off" value="1" class="layui-input layui-input-inline" style="width: 50px">
                    个参数
                    <button type="button" class="layui-btn layui-btn-sm sc-api-add-number">新增</button>
                    <button type="button" class="layui-btn layui-btn-sm layui-btn-primary sc-api-cp"><i class="layui-icon layui-icon-file"></i>复制</button>
                </div>

                <table class="layui-table">
                    <thead>
                    <tr>
                        <td style="width: 100px">类型</td>
                        <td>key</td>
                        <td>value</td>
                        <td>描述</td>
                        <td style="width: 42px"></td>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($param[\app\admin\enum\ApiEnumParamType::POST] ?? [] as $item){ ?>
                        <tr>
                            <td>
                                <select name="param_type">
                                    <option value="1" <?= $item['param_type'] == 1 ? "selected" : '' ?>>文本</option>
                                    <option value="2" <?= $item['param_type'] == 2 ? "selected" : '' ?>>文件</option>
                                </select>
                            </td>
                            <td><input type="text" name="name" value="<?=$item['name']?>" autocomplete="off" class="layui-input"></td>
                            <td><input type="text" name="test_value" value="<?=$item['test_value']?>"  autocomplete="off" class="layui-input"></td>
                            <td><input type="text" name="describe" value="<?=$item['describe']?>"  autocomplete="off" class="layui-input"></td>
                            <td>
                                <button class="layui-btn  layui-btn-danger layui-btn-sm sc-param-del"><i
                                            class="layui-icon layui-icon-delete"></i></button>
                            </td>
                        </tr>
                    <?php } ?>
                    <tr>
                        <td>
                            <select name="param_type">
                                <option value="1">文本</option>
                                <option value="2">文件</option>
                            </select>
                        </td>
                        <td><input type="text" name="name" autocomplete="off" class="layui-input"></td>
                        <td><input type="text" name="test_value" autocomplete="off" class="layui-input"></td>
                        <td><input type="text" name="describe" autocomplete="off" class="layui-input"></td>
                        <td>
                            <button class="layui-btn sc-param-del  layui-btn-danger layui-btn-sm"><i
                                        class="layui-icon layui-icon-delete"></i></button>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
            <div class="layui-tab-item sc-param-head">
                <div class="sc-api-add">
                    新增
                    <input type="number" autocomplete="off" value="1" class="layui-input layui-input-inline" style="width: 50px">
                    个参数
                    <button type="button" class="layui-btn layui-btn-sm sc-api-add-number">新增</button>
                    <button type="button" class="layui-btn layui-btn-sm layui-btn-primary sc-api-cp"><i class="layui-icon layui-icon-file"></i>复制</button>
                </div>

                <table class="layui-table">
                    <thead>
                    <tr>
                        <td style="width: 100px">类型</td>
                        <td>key</td>
                        <td>value</td>
                        <td>描述</td>
                        <td style="width: 42px"></td>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($param[\app\admin\enum\ApiEnumParamType::HEADER] ?? [] as $item){ ?>
                        <tr>
                            <td>
                                <select name="param_type">
                                    <option value="1" <?= $item['param_type'] == 1 ? "selected" : '' ?>>文本</option>
                                    <option value="2" <?= $item['param_type'] == 2 ? "selected" : '' ?>>文件</option>
                                </select>
                            </td>
                            <td><input type="text" name="name" value="<?=$item['name']?>" autocomplete="off" class="layui-input"></td>
                            <td><input type="text" name="test_value" value="<?=$item['test_value']?>"  autocomplete="off" class="layui-input"></td>
                            <td><input type="text" name="describe" value="<?=$item['describe']?>"  autocomplete="off" class="layui-input"></td>
                            <td>
                                <button class="layui-btn  layui-btn-danger layui-btn-sm sc-param-del"><i
                                            class="layui-icon layui-icon-delete"></i></button>
                            </td>
                        </tr>
                    <?php } ?>
                    <tr>
                        <td>
                            <select name="param_type">
                                <option value="1">文本</option>
                                <option value="2">文件</option>
                            </select>
                        </td>
                        <td><input type="text" name="name" autocomplete="off" class="layui-input"></td>
                        <td><input type="text" name="test_value" autocomplete="off" class="layui-input"></td>
                        <td><input type="text" name="describe" autocomplete="off" class="layui-input"></td>
                        <td>
                            <button class="layui-btn sc-param-del  layui-btn-danger layui-btn-sm"><i
                                        class="layui-icon layui-icon-delete"></i></button>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
            <div class="layui-tab-item">
                <textarea id="demo" name="response" style="display: none;"><?= $api['response'] ?? '' ?></textarea>
            </div>
        </div>
    </div>
</form>


<table id="api-params" style="display: none">
    <tr>
        <td>
            <select name="param_type">
                <option value="1">文本</option>
                <option value="2">文件</option>
            </select>
        </td>
        <td><input type="text" name="name" autocomplete="off" class="layui-input"></td>
        <td><input type="text" name="test_value" autocomplete="off" class="layui-input"></td>
        <td><input type="text" name="describe" autocomplete="off" class="layui-input"></td>
        <td>
            <button class="layui-btn  layui-btn-danger layui-btn-sm sc-param-del"><i
                        class="layui-icon layui-icon-delete"></i></button>
        </td>
    </tr>
</table>

<div id="iframe12" style="display:none;width: auto;height: 100%">
    <iframe srcdoc="" id="iframe1" style="width: 100%;height: 100%;" frameborder="0"></iframe>
</div>


{/block}
{block name="js"}

<script>
    let method = 'GET';

</script>

<script>
    let defaultData = {}, form = layui.form, $ = layui.jquery, upload = layui.upload;
    var layedit = layui.layedit;
    let response_ = layedit.build('demo',{
        tool: ['left', 'center', 'right', '|', 'strong', 'italic', 'underline']
    });

    <?php if (isset($api['method']) && $api['method'] == 'post'){ ?>
    $('.layui-tab-title').find('li').eq(1).click();
    <?php } ?>

    // 设置本地默认地址
    let url_prefix_default = layui.sessionData('url_prefix_default');

    if (url_prefix_default.hasOwnProperty('select_<?= $data['id'] ?>')){
        form.val('sc',{path_no:url_prefix_default["select_<?= $data['id'] ?>"]});
    }

    form.on('select(path_no)', function(data){
        layui.sessionData('url_prefix_default', {
            key: 'select_<?= $data['id'] ?>',
            value: data.value
        })
    });

    function auto_add_param(class_name) {
        let y = class_name + ' input[name=name]';
        $(class_name).on('focus', 'input[name=name]' ,function () {
            console.log(123123);
            ($(y).length === $(y).index(this) + 1) && $(class_name + ' .sc-api-add-number').click();
        })
    }

    auto_add_param('.sc-param-head')
    auto_add_param('.sc-param-get')
    auto_add_param('.sc-param-post')


    /**
     * 路径处理
     **/
    function path_no_handle(){
        $('input[name=path]').css({'paddingLeft': '205px', "boxSizing":"border-box"})

        // 设置路径前缀的样式
        $('select[name=path_no]').siblings('.layui-unselect.layui-form-select').css({
            width: "200px",
            position: "absolute",
            top: 0
        });
    }

    $(function (){
        path_no_handle();
    });

    //监听提交
    form.on('submit(formDemo)', function (data) {
        let body = {
            body: {
                id: data.field.id,
                api_name: data.field.api_name,
                method: data.field.method,
                path: data.field.path,
                response: layedit.getContent(response_),
                token: data.field.path,
                api_module_id:data.field.api_module_id,
            },
            get:  getParam(0),
            post: getParam(1),
            head: getParam(2),
        }
        let load = custom.loading();
        $.ajax({
            url:"<?= url('save') ?>",
            type: 'post',
            data: body,
            success:function (res) {
                layer.close(load);
                if (res.code === 200){
                    parent.layer.closeAll();
                    parent.notice.success('成功');
                    parent.table.reload('sc');
                }else{
                    notice.warning(res.msg)
                }
            }
        });
        return false;
    });

    // 新增参数
    $('.sc-api-add-number').on('click', function () {
        let v = $(this).siblings('input').val();
        $(this).parent('.sc-api-add').siblings('.layui-table').find('tbody').append($('#api-params tbody').html().repeat(v));
        form.render();
        path_no_handle();
    });

    // 删除参数
    $('.layui-table').on('click', '.sc-param-del', function () {
        $(this).parents('tr').remove();
    })

    // 模拟请求
    $('#send').on('click', function () {
        let type = $('input[name=method]:checked').val();
        let url  = $('input[name=path]').val();
        let getParams = getParam(0, true);
        var load = custom.loading('模拟请求中，请稍候...')
        let query_config = {
            type:type,
            beforeSend(request){
                let head = getParam(2, true);
                for (const i in head) {
                    request.setRequestHeader(i, head[i]);
                }
                let E     = "<?= env('CROSS_DOMAIN.NO_TOKEN') ?>";
                let token = $('input[name=token]').val();
                if (E && token){
                    request.setRequestHeader(E, token);
                }
            },
            complete(res, r, f){
                layer.close(load);
                if (res.status !== 200 || !res.hasOwnProperty('responseJSON')){
                    $('#iframe1').attr('srcdoc', res.responseText);
                }else if(res.hasOwnProperty('responseJSON')){
                    let text = JSON.stringify(res.responseJSON, undefined, 4);
                    $('#iframe1').attr('srcdoc', "<pre style='outline: 1px solid #ddd; background-color:#f2f2f2;padding: 5px; box-sizing: border-box;font-family: -webkit-pictograph;'>"+ highLight(text) +"</pre>");
                }
                $('.layui-tab-item').css({width:"60%"})
                layer.open({
                    offset: 'rb'
                    ,type:1
                    ,title:'响应信息'
                    ,content: $('#iframe12')
                    ,area:['40%', "70%"]
                    ,shade:false
                    ,cancel: function(index, layero){
                        $('.layui-tab-item').css({width:"auto"})
                        layer.close(index)
                        return false;
                    }
                });
            }
        };
        let url_prefix = $('select[name=path_no]').val();
        if (type === 'post'){
            let getStr = /\?/.test(url_prefix + url) ? '&' : '?';
            for (let key in getParams) {
                getStr += `${key}=${getParams[key]}&`;
            }
            query_config.url  = url_prefix + url + getStr.slice(0, -1);
            query_config.data = getParam(1, true);
        }else{
            query_config.url  = url_prefix + url
            query_config.data = getParams;
        }

        $.ajax(query_config)
        return false;
    });

    // 复制对应的参数
    $('.sc-api-cp').on('click', function () {
        let text = JSON.stringify(getParam($('.sc-api-cp').index(this), true));
        copy(text);
    })

    /**
     * 获取get参数
     * @param method 参数类型， 0 GET, 1 POST, 2 HEAD
     * @param is_test 是否是测试请求
     * @returns {{}|[]}
     */
    function getParam(method, is_test) {
        let tr  = $('.layui-tab-item').eq(method).find('table>tbody>tr');
        let len = tr.length;
        let param_type, name, test_value, describe;
        let param = is_test ? {} : [];
        for (let i = 0; i < len; i++) {
            param_type = tr.eq(i).find('select[name=param_type]').val();
            name       = tr.eq(i).find('input[name=name]').val();
            test_value = tr.eq(i).find('input[name=test_value]').val();
            describe   = tr.eq(i).find('input[name=describe]').val();
            if(name){
                !is_test ? param.push({param_type,name,test_value,describe}) : param[name] = test_value;
            }
        }
        return param;
    }

    /**
     * json 高亮显示
     * @param json
     * @returns {string}
     */
    function highLight(json){
        json = json.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
        return json.replace(/("(\\u[a-zA-Z0-9]{4}|\\[^u]|[^\\"])*"(\s*:)?|\b(true|false|null)\b|-?\d+(?:\.\d*)?(?:[eE][+\-]?\d+)?)/g, function (match) {
            var cls = 'darkorange';
            if (/^"/.test(match)) {
                if (/:$/.test(match)) {
                    cls = 'red';
                } else {
                    cls = 'green';
                }
            } else if (/true|false/.test(match)) {
                cls = 'blue';
            } else if (/null/.test(match)) {
                cls = 'magenta';
            }
            return '<span style="color: ' + cls + '">' + match + '</span>';
        });
    }

    /**
     * 复制
     * @param text
     */
    function copy(text) {
        let transfer = document.createElement('input');
        document.body.appendChild(transfer);
        transfer.value = text;  // 这里表示想要复制的内容
        transfer.focus();
        transfer.select();
        if (document.execCommand('copy')) {
            document.execCommand('copy');
        }
        transfer.blur();
        notice.success('复制成功')
        document.body.removeChild(transfer);

    }
</script>
{/block}

<?php /** 以下模块位继承后的自定义的js代码 */ ?>
{block name="js_custom"}{/block}
