<?php
/**
 * Date: 2020/8/5 9:41
 * User: chenlong <vip_chenlong@163.com>
 */

namespace app\common\traits\api;

use app\common\ResponseJson;
use app\common\SdException;
use sdModule\common\Sc;
use think\facade\Db;
use weChat\common\Login;

/**
 * 微信用户登录操作
 * Class WeChatUser
 * @package app\common\controller\apiTrait
 */
trait WeChatUser
{
    public function h5AndLetLogin($code = '')
    {
        if (empty($code)) throw new SdException('请求错误！');

        if (!$user = Login::getUserInfo($code)) throw new SdException('授权登录失效，请重新授权。');

        return Sc::redis()->lock(function () use ($user){
            if ($user_data = Db::name('user')->where('wx_openid', $user['openid'])->field('id,nickname,avatar')->find()){
                goto end;
            }

            $id = Db::name('user')->insertGetId([
                'wx_openid' => $user['openid'],
                "nickname" => $user["nickname"],
                "avatar" => $user["headimgurl"],
                'create_time' => datetime(),
                'update_time' => datetime(),
            ]);

            if (!$id) throw new SdException('信息错误');
            $user_data = Db::name('user')->where('id', $id)->find();

            end:
            $user_data['token'] = Sc::jwt(['user_id' => $user_data['id']])->getRefresh(30)
                ->setExp(3600)->getToken();
            return ResponseJson::success($user_data);
        });
    }
}

