<?php
/**
 * Date: 2020/11/25 14:44
 * User: chenlong <vip_chenlong@163.com>
 */

namespace app\admin\page\system;


use app\admin\enum\RouteEnumType;
use app\common\BasePage;
use sdModule\layui\Dom;
use sdModule\layui\form4\FormProxy as Form;
use sdModule\layui\form4\FormUnit;
use sdModule\layui\lists\module\Column;
use sdModule\layui\lists\module\EventHandle;
use sdModule\layui\lists\PageData;

class RoutePage extends BasePage
{
    public $list_template = 'common/tree_list_page';

    /**
     * 获取创建列表table的数据
     * @return PageData
     * @throws \app\common\SdException
     */
    public function listPageData(): PageData
    {
        $table = PageData::create([
            Column::checkbox(),
            Column::normal('ID', 'id')->moreConfiguration('width', 100),
            Column::normal('标题', 'title'),
            Column::normal('类型', 'type'),
            Column::normal('路由地址', 'route'),
            Column::normal('排序权重', 'weigh'),
        ]);

        $table->setHandleAttr([
            'width' => 150,
        ]);
        $table->setConfig([
            'treeColIndex' => 2
        ]);

        $table->removeBarEvent(['delete']);

        $table->addEvent('delete')->setDangerBtn('删除', 'delete', 'xs')
            ->setJs(EventHandle::ajax(url('delete'), '节点删除会同时删除对应的所有子节点，确认删除吗？')
                ->setConfig(['icon' => 3])->successCallback('tableRender();'));

        $table->addBarEvent()->setWarmBtn('自动检测', '', 'sm')
            ->setJs(EventHandle::openPage(url('automaticDetection'), '自动检测')->popUps());

        return $table;
    }

    /**
     * @param string $scene
     * @param array $default_data
     * @return Form
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/12/11
     */
    public function formPageData(string $scene, array $default_data = []): Form
    {
        $unit = [
            FormUnit::hidden('id'),
            FormUnit::group(
                FormUnit::text('title', '权限标题'),
                FormUnit::text('route', '权限路由'),
            ),
            FormUnit::radio('type', '节点类型')->defaultValue(RouteEnumType::LEFT_MENU)->inputAttr('-', ['lay-filter' => 'type'])->options(RouteEnumType::getMap(true)),
            FormUnit::customize(
                Dom::create()->addClass('layui-form-item')
                    ->addContent(Dom::create('label')->addClass('layui-form-label')->addContent('上级节点'))
                    ->addContent(Dom::create()->addClass('layui-input-block')
                        ->addContent(Dom::create()->setId('pid-selects')))
            ),
            FormUnit::group(
                FormUnit::text('weigh', '排序权重'),
                FormUnit::icon('icon', '图标'),
            ),
        ];

        $form = Form::create($unit, $default_data)->setScene($scene)->setMd(8);

        $form->addLoadJs('/admin_static/layui/dist/xm-select.js');
        $url = url('getNode');

        $default_pid       = $default_data['pid'] ?? 0;
        $default_node_type = $default_data['type'] ?? RouteEnumType::LEFT_MENU;
        $form->addJs(<<<JS

        let top_n, left_n, node_n;
        layui.jquery.ajax({
            url: "$url"
            , success: function (res) {
                top_n  = res.data.top;
                left_n = res.data.left;
                node_n = res.data.node;
                pRender([[],left_n, top_n, node_n]['$default_node_type' * 1], ['$default_pid']);
            }
        });

        form.on('radio(type)', function (data) {
            pRender([[],left_n, top_n, node_n][data.value * 1]);
        });

        function pRender(nodeData, select) {
            xmSelect.render({
                el: '#pid-selects',
                model: { label: { type: 'text' } },
                height: 'auto',
                filterable: true,
                name: 'pid',
                tree: { show: true,strict: false,expandedKeys: [ -1 ]},
                //处理方式
                on: function(data){
                    if(data.isAdd){
                        return data.change.slice(0, 1)
                    }
                },
                prop: {name: 'title', value: 'id'},
                data(){
                    return nodeData;
                }
            }).setValue(select);
        }
        
JS);
        $form->setSuccessHandle('window.parent.layNotice.success("成功");window.parent.tableRender();window.parent.layer.close(window.closeLayerIndex);');

        return $form;
    }
}
