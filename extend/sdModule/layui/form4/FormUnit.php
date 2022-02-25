<?php
/**
 * datetime: 2021/11/18 21:30
 * user    : chenlong<vip_chenlong@163.com>
 **/

namespace sdModule\layui\form4;

use sdModule\common\StaticCallGetInstance;
use sdModule\layui\form4\formUnit\unitProxy\{AuxTitleProxy,
    CheckboxProxy,
    ColorProxy,
    CustomizeProxy,
    GroupProxy,
    HiddenProxy,
    IconProxy,
    ImageProxy,
    ImagesProxy,
    PasswordProxy,
    RadioProxy,
    SelectProxy,
    SelectsProxy,
    SelectTreeProxy,
    SliderProxy,
    TableProxy,
    TagProxy,
    TextareaProxy,
    TextProxy,
    TimeProxy,
    UEditorProxy,
    UploadProxy,
    VideoProxy};
use sdModule\layui\Dom;

/**
 * Class FormUnit
 * @method static TextProxy     text(string $name, string $label = '')      文本框
 * @method static PasswordProxy password(string $name, string $label = '')  密码框
 * @method static CheckboxProxy checkbox(string $name, string $label = '')  多选框
 * @method static UEditorProxy  uEditor(string $name, string $label = '')   百度富文本
 * @method static ImageProxy    image(string $name, string $label = '')     单图上传
 * @method static TimeProxy     time(string $name, string $label = '')      时间日期
 * @method static SelectProxy   select(string $name, string $label = '')    下拉单选
 * @method static TextareaProxy textarea(string $name, string $label = '')  文本域
 * @method static RadioProxy    radio(string $name, string $label = '')     单选
 * @method static HiddenProxy   hidden(string $name, string $label = '')    隐藏表单
 * @method static ImagesProxy   images(string $name, string $label = '')    多图上传
 * @method static ColorProxy    color(string $name, string $label = '')     颜色
 * @method static SelectsProxy  selects(string $name, string $label = '')   下拉多选
 * @method static SelectTreeProxy selectTree(string $name, string $label = '')   下拉树
 * @method static SliderProxy   slider(string $name, string $label = '')    滑块
 * @method static TagProxy      tag(string $name, string $label = '')       标签
 * @method static UploadProxy   upload(string $name, string $label = '')    通用上传
 * @method static VideoProxy    video(string $name, string $label = '')     视频上传
 * @method static TableProxy    table(string $name)                         表格形式的表单
 * @method static IconProxy     icon(string $name, string $label = '')      图标选择
 * @method static AuxTitleProxy auxTitle(string|Dom $content, string $show_type = 'grey') 辅助标题
 * @package sdModule\layui\form4
 * @author chenlong<vip_chenlong@163.com>
 * @date 2021/11/18
 */
class FormUnit extends StaticCallGetInstance
{

    /**
     * 返回命名空间
     * @return string|array
     */
    protected function getNamespace()
    {
        return 'sdModule\\layui\\form4\\formUnit\\unitProxy\\';
    }

    /**
     * 组合表单
     * @param ...$unit
     * @return GroupProxy
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/11/19
     */
    public static function group(...$unit): GroupProxy
    {
        return (new GroupProxy())->addChildrenItem(...$unit);
    }

    /**
     * 定制自己的html元素
     * @param Dom $element
     * @return CustomizeProxy
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/11/25
     */
    public static function customize(Dom $element): CustomizeProxy
    {
        return (new CustomizeProxy())->setElement($element);
    }
}
