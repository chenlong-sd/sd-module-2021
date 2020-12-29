<?php


namespace weChat\thePublic;
use weChat\common\Helper;
use weChat\common\Config;
use weChat\common\AccessToken;

/**
 * 公众号菜单
 * Class Menu
 * @package app\common\wechat
 */
class Menu
{
    /**
     * 菜单创建
     * @param array $menuData
     * @return bool
     */
    public function create($menuData = [])
    {
        $createUrl = 'https://api.weixin.qq.com/cgi-bin/menu/create?access_token=%s';
        $createUrl = sprintf($createUrl, AccessToken::getAccessToken());
        $menuData = [
            'button' => $menuData
        ];

        $createResult = Helper::postRequest($createUrl, $menuData);
        return empty($createResult['errcode']) ? true : $createResult['errcode'];
    }
}

