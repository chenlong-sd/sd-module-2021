{extend name="frame"}

{block name="title"}新增{/block}
{block name="meta"}{:token_meta()}{/block}

{block name="body"}

<div class="layui-container">
    <div class="layui-row">
        <div class="layui-col-md6">
            <form class="layui-form" action="" lay-filter="sd">


                <div class="layui-form-item">
                    <label class="layui-form-label">{:lang('route.route_title')}</label>
                    <div class="layui-input-block">
                        <input type="text" name="title" required placeholder="{:lang('please enter')}" value='' autocomplete="off"
                               class="layui-input">
                    </div>
                </div>

                <div class="layui-form-item">
                    <label class="layui-form-label">{:lang('route.route_route')}</label>
                    <div class="layui-input-block">
                        <input type="text" name="route" required placeholder="{:lang('please enter')}" value='' autocomplete="off"
                               class="layui-input">
                    </div>
                </div>

                <div class="layui-form-item">
                    <label class="layui-form-label">{:lang('route.route_type')}</label>
                    <div class="layui-input-block">
                        {foreach $type_data as $value => $title}
                        <input type="radio" {$value == 1 ? 'checked' : ''} name="type" lay-filter="type" value="{$value}" title="{$title}"
                               autocomplete="off" class="layui-input">
                        {/foreach}
                    </div>
                </div>

                <div class="layui-form-item">
                    <label class="layui-form-label">{:lang('route.route_parent')}</label>
                    <div class="layui-input-block">
                        <div id="demo3" class="xm-select-demo"></div>
                    </div>
                </div>

                <div class="layui-form-item">
                    <label class="layui-form-label">{:lang('route.route_weigh')}</label>
                    <div class="layui-input-block">
                        <input type="number" name="weigh" lay-verify="number" placeholder="{:lang('please enter')}" value=''
                               autocomplete="off" class="layui-input">
                    </div>
                </div>

                <div class="layui-form-item">
                    <label class="layui-form-label">{:lang('route.icon_title')}</label>
                    <div class="layui-input-inline">
                        <input type="text" name="icon" id="iconPicker" lay-filter="iconPicker" value=""
                               style="display:none;">
                    </div>
                    <div class="layui-form-mid layui-word-aux">{:lang('route.Effective when it is a first-level menu')}</div>
                </div>

                <div id="children" class="layui-form-item layui-hide">
                    <label class="layui-form-label">{:lang('route.Sub-operation')}</label>
                    <div class="layui-input-block">
                        <input type="checkbox" name="children[index]" title="{:lang('List data')}" lay-skin="primary" autocomplete="off" class="layui-input">
                        <input type="checkbox" name="children[create]" title="{:lang('add')}" lay-skin="primary" autocomplete="off" class="layui-input">
                        <input type="checkbox" name="children[update]" title="{:lang('edit')}" lay-skin="primary" autocomplete="off" class="layui-input">
                        <input type="checkbox" name="children[del]" title="{:lang('delete')}" lay-skin="primary" autocomplete="off" class="layui-input">
                    </div>
                </div>

                <div class="layui-form-item">
                    <div class="layui-input-block">
                        <button class="layui-btn" lay-submit lay-filter="formDemo">{:lang('submit')}</button>
                        <button type="reset" class="layui-btn layui-btn-primary">{:lang('reset')}</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

{/block}
{block name="js"}
<script src="__PUBLIC__/admin_static/layui/dist/xm-select.js"></script>

<script>

    layui.use(['form', 'jquery', 'iconPicker'], function () {
        var form = layui.form, $ = layui.jquery, iconPicker = layui.iconPicker;

        iconPicker.render({
            // 选择器，推荐使用input
            elem: '#iconPicker',
            // 数据类型：fontClass/unicode，推荐使用fontClass
            type: 'fontClass',
            // 是否开启搜索：true/false，默认true
            search: true,
            // 是否开启分页：true/false，默认true
            page: true,
            // 每页显示数量，默认12
            limit: 15,
            // 每个图标格子的宽度：'43px'或'20%'
            cellWidth: '60px',
            // 点击回调
            click: function (data) {
                console.log(data);
            },
            // 渲染成功后的回调
            success: function (d) {
                iconPicker.checkIcon('iconPicker', '');
            }
        });


        let top, left, node;
        $.ajax({
            url: "{:url('getNode')}?type=1"
            , success: function (res) {
                top = res.data.top;
                left = res.data.left;
                node = res.data.node;
                pRender(left, []);
            }
        })

        form.on('radio(type)', function (data) {
            pRender([[],left, top, node][data.value * 1]);
        });

        function pRender(nodeData, select) {
            xmSelect.render({
                el: '#demo3',
                model: { label: { type: 'text' } },
                height: 'auto',
                filterable: true,
                name: 'pid',
                tree: {
                    show: true,
                    strict: false,
                    expandedKeys: [ -1 ],
                },
                //处理方式
                on: function(data){
                    if(data.isAdd){
                        return data.change.slice(0, 1)
                    }
                },
                prop: {
                    name: 'title',
                    value: 'id',
                },
                data(){
                    return nodeData;
                }
            }).setValue(select);
        }

        form.on('submit(formDemo)', function (data) {
            layer.confirm('{:lang("Confirm this operation")}?', {title: "{:lang('information')}" ,btn:['{:lang("confirm")}', '{:lang("cancel")}']}, function (index) {
                let load = custom.loading();
                $.ajax({
                    type: 'post'
                    , headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                    , data: data.field
                    , success: function (res) {
                        layer.close(load);
                        if (res.code === 200) {
                            parent.layer.closeAll();
                            window.parent.layNotice.success('{:lang("success")}');
                            window.parent.tableRender();
                        } else {
                            layNotice.warning(res.msg);
                        }
                    },
                    error: function (err) {
                        layer.close(load);
                        console.log(err);
                    }
                });
            });
            return false;
        })
    });
</script>
{/block}