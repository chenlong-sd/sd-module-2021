<?php
/**
 * Date: 2020/8/10 9:59
 * User: chenlong <vip_chenlong@163.com>
 */

namespace weChat\thePublic;

use sdModule\common\StaticCallGetInstance;

/**
 * @method static DomainConfigure domainConfigure() 域名配置
 * @method static Material material() 素材管理
 * @method static User user() 用户
 * @method static QRCode QRCode() 二维码
 * @method static Menu menu() 菜单
 * @method static TemplateMessage templateMessage(string $openid, string $template_id) 模板消息
 * Class ThePublic
 * @package weChat\thePublic
 */
class ThePublic extends StaticCallGetInstance
{
    protected function getNamespace(): string
    {
        return "weChat\\thePublic\\";
    }

}
