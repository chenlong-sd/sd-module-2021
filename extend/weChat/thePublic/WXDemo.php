<?php


namespace weChat\thePublic;

use weChat\common\Helper;
use think\facade\Log;
use weChat\common\Config;

class WXDemo implements MessageReply
{
    /**
     * 服务器配置地址
     * @param DomainConfigure $configure
     * @return bool|string|void
     * @throws \ReflectionException
     */
    public function addressConfig(DomainConfigure $configure)
    {
        return $configure->index();
    }
    
    /**
     * 关注公众号处理
     * @param $data object  微信推送的所有数据
     * @return array|string
     */
    public function subscribe($data)
    {
        $user = User::getInfo($data->FromUserName);
        $name = $user ? $user['nickname'] : '+帅帅的二一把+';

        if (!empty($data->EventKey)) {
            return $this->scan($data);
        }
        return sprintf('欢迎%s来到的测试公众号,谢谢关注！', $name);
    }

    /**
     * 扫描二维码
     * @param $data
     * @return array
     */
    public function scan($data)
    {
        $param = $data->EventKey;
        $news = [
            [
                'Title' => 'this is title',
                'Description' => '这里是图文消息的描述' . $param,
                'PicUrl' => 'http://www.renzhonghe028.com/back.jpg',
                'Url' => 'http://www.renzhonghe028.com'
            ]
        ];
        return [$news, 'news'];
    }

    /**
     * 菜单点击处理
     * @param $data object  微信推送的所有数据
     * @return string|array
     */
    public function click($data)
    {
//        file_put_contents('xxx.json', json_encode($data, JSON_UNESCAPED_UNICODE));
        if ($data->EventKey == 'XXCC') {
            return '这是点击出来的';
        } elseif ($data->EventKey == 'Music') {

            $news = [
                [
                    'Title' => 'this is title',
                    'Description' => '这里是图文消息的描述',
                    'PicUrl' => 'http://www.renzhonghe028.com/back.jpg',
                    'Url' => 'http://www.renzhonghe028.com'
                ],[
                    'Title' => '这是标题',
                    'Description' => '这里是图文消息的描述第二单',
                    'PicUrl' => 'http://www.renzhonghe028.com/back.jpg',
                    'Url' => 'http://www.renzhonghe028.com'
                ],
            ];
            return [$news, 'news'];
//            return [
//                [
//                    'ThumbMediaId' => 'woFccHYoMNtrsXNllPLWgTy8uSxHlo6tUHnO2d1pDmRE2k0jiSPycl_fNN6uYKCV',
//                    'Title' => '浮白',
//                    'Description' => '没有那么多的描述哦！',
//                    'HQMusicUrl' => 'http://www.renzhonghe028.com/fubai.flac',
//                    'MusicUrl' => 'http://www.renzhonghe028.com/fubai.flac',
//                ],
//                'music'
//            ];
        } elseif ($data->EventKey == 'sao') {
            return '你扫了啥？';
        } elseif ($data->EventKey == 'photo') {
            return '这是照片吗？';
        } elseif ($data->EventKey == 'position') {
            $content = '你的位置：' . $data->SendLocationInfo . ',X:' . $data->Location_X
                . ',Y:' . $data->Location_Y . ',精度' . $data->Scale . ',Label' . $data->Label;
            Log::write($content);
            Log::write('我就试试');
            Log::write($data->SendLocationInfo);
            return $content;
        }
    }

    /**
     * 文本消息处理
     * @param $data object  微信推送的所有数据
     * @return array|string 返回的就是回复给用户的内容
     */
    public function textMsg($data)
    {
        if (strpos($data->Content, '魔兽') !== false) {   // 文字
            return '你是魔兽玩家ddddd';
        }elseif(strpos($data->Content, '图片') !== false){    // 图片
            return ['b6BqQmO4lYqcUu6J85pfqftID0oRdLf25E2rM0dI_VOw69b2td2q6Ost-WP_LlO9', 'image'];
        } elseif (strpos($data->Content, '音乐') !== false) { // 音频
            return ['xfM6y63BQtrKydFtGtqEqUordIaJ51izeGwK3LOlNmdh1vivAcAnlK3VP-5VPuyn', 'voice'];
        } elseif (strpos($data->Content, '龙哥') !== false) {     // 视频
            return [
                [
                    'MediaId' => 'DV-IpHJqWPmRysQ0kxdPGexG8auaVDlfh5fYiNl6abo',
                    'Title' => '测试龙哥视频',
                    'Description' => '没有那么多的描述哦！'
                ],
                'video'
            ];
        } elseif (strpos($data->Content, '浮白') !== false) {     // 音乐
            return [
                [
                    'ThumbMediaId' => 'woFccHYoMNtrsXNllPLWgTy8uSxHlo6tUHnO2d1pDmRE2k0jiSPycl_fNN6uYKCV',
                    'Title' => '浮白',
                    'Description' => '没有那么多的描述哦！',
                    'HQMusicUrl' => 'http://www.renzhonghe028.com/fubai.flac',
                    'MusicUrl' => 'http://www.renzhonghe028.com/fubai.flac',
                ],
                'music'
            ];
        }elseif (strpos($data->Content, '新闻') !== false){  // 当用户发送文本、图片、视频、图文、地理位置这五种消息时，开发者只能回复1条图文消息；其余场景最多可回复8条图文消息
            $news = [
                [
                    'Title' => 'this is title',
                    'Description' => '这里是图文消息的描述',
                    'PicUrl' => 'http://www.renzhonghe028.com/back.jpg',
                    'Url' => 'http://www.renzhonghe028.com'
                ],[
                    'Title' => '这是标题',
                    'Description' => '这里是图文消息的描述第二单',
                    'PicUrl' => 'http://www.renzhonghe028.com/back.jpg',
                    'Url' => 'http://www.renzhonghe028.com'
                ],
            ];
            return [$news, 'news'];
        }
    }

    /**
     * 图片消息处理
     * @param $data
     */
    public function imageMsg($data)
    {
        file_put_contents('xxx.json', json_encode($data, JSON_UNESCAPED_UNICODE));
    }

    /**
     * 临时素材上传
     * @param Material $material
     */
    public function uploadMedia(Material $material)
    {
//          xfM6y63BQtrKydFtGtqEqUordIaJ51izeGwK3LOlNmdh1vivAcAnlK3VP-5VPuyn    音频
//        QMgnWg1-Z7Prt7yCsY8lNzWTtBOoQiKb7zmrpF8MNxG0Z1aMLZ_TkutGqkX3-pQh      图片
//        qa7DlI3EzAHa3bEeX4HByo8aL7uKKd8rBQgwZhnQL_daQUIUYB5jZ5roCSuppsQI      视频
//        woFccHYoMNtrsXNllPLWgTy8uSxHlo6tUHnO2d1pDmRE2k0jiSPycl_fNN6uYKCV      缩略图
        $data = $material->uploadMedia(\think\facade\Env::get('ROOT_PATH') . 'upok.jpg', Material::MATERIAL_THUMB);

        var_dump($data);
    }

    /**
     * 获取临时素材
     * @param Material $material
     * @return \think\Response
     */
    public function getMedia(Material $material)
    {
        $data = $material->getMedia('QMgnWg1-Z7Prt7yCsY8lNzWTtBOoQiKb7zmrpF8MNxG0Z1aMLZ_TkutGqkX3-pQh');
        return response($data)->contentType('image/jpeg');
    }

    /**
     * 创建菜单
     */
    public function createMenu()
    {
        $menu = [
            [
                'name' => '点击按钮',
                'type' => 'click',
                'key' => 'XXCC',
            ],
            [
                'name' => '网页按钮',
                'type' => 'view',
                'url' => 'http://renzhonghe028.com'
            ],[
                'name' => '有子菜单',
                'sub_button' => [
                    [
                        'name' => '点我听音乐',
                        'type' => 'click',
                        'key' => 'Music'
                    ],[
                        'name' => '点我跳转',
                        'type' => 'view',
                        'url' => 'http://renzhonghe028.com'
                    ],[
                        'name' => '点我扫一扫',
                        'type' => 'scancode_push',
                        'key' => 'sao'
                    ],[
                        'name' => '我是图文',
                        'type' => 'media_id',
                        'media_id' => 'k42s_xRjHpWJevigJpOpDusJgdP1-RtznzmomRk-ouI'
                    ],[
                        'name' => '点我找位置',
                        'type' => 'location_select',
                        'key' => 'position'
                    ]
                ]
            ]
        ];

        $result = Menu::create($menu);

        if ($result === true) {
            echo '成功';
        }else{
            echo $result;
        }
    }

    /**
     * 获取用户信息
     */
    public function getUserInfo()
    {
        pr(User::getInfo($openid = ''));
    }

    /**
     * 上传永久图文素材
     * @param Material $material
     */
    public function uploadNews(Material $material)
    {
//        $image = $this->wx->uploadNews(\think\facade\Env::get('ROOT_PATH') . 'back.jpg', true);
//        http://mmbiz.qpic.cn/mmbiz_jpg/D2stibLCyEbRFhN9IibqHhI7vPAIicrXE1kSqQEK0XwqUsGLJlUdOvQrcXcvJngbDundQ53mSGDItwNauDIK0CFew/0
//        if (empty($image['url'])) {
//            $imageUrl = $image['url'];
//        }

        $news = [
            [
                'title' => '这是标题一',
                'thumb_media_id' => 'k42s_xRjHpWJevigJpOpDi96Q_HLftuRvRB_-yBjQNI',   // 永久的media_id
                'author' => 'logged',
//                'digest' => 'asdasdsadd',
                'show_cover_pic' => 1,
                'content' => '1、最近更新：永久图片素材新增后，将带有URL返回给开发者，开发者可以在腾讯系域名内使用（腾讯系域名外使用，图片将被屏蔽）。

                            2、公众号的素材库保存总数量有上限：图文消息素材、图片素材上限为5000，其他类型为1000。
                            
                            3、素材的格式大小等要求与公众平台官网一致：
                            
                            图片（image）: 2M，支持bmp/png/jpeg/jpg/gif格式
                            
                            语音（voice）：2M，播放长度不超过60s，mp3/wma/wav/amr格式
                            
                            视频（video）：10MB，支持MP4格式
                            
                            缩略图（thumb）：64KB，支持JPG格式
                            
                            4、图文消息的具体内容中，微信后台将过滤外部的图片链接，图片url需通过"上传图文消息内的图片获取URL"接口上传图片获取。
                            
                            5、"上传图文消息内的图片获取URL"接口所上传的图片，不占用公众号的素材库中图片数量的5000个的限制，图片仅支持jpg/png格式，大小必须在1MB以下。
                            
                            6、图文消息支持正文中插入自己帐号和其他公众号已群发文章链接的能力。
                            
                            <img src="http://mmbiz.qpic.cn/mmbiz_jpg/D2stibLCyEbRFhN9IibqHhI7vPAIicrXE1kSqQEK0XwqUsGLJlUdOvQrcXcvJngbDundQ53mSGDItwNauDIK0CFew/0?wx_fmt=jpeg">
                            
                            ',  // 图片地址只能是永久素材
                'content_source_url' => 'http://renzhonghe028.com',
                'need_open_comment' => 1,
                'only_fans_can_comment' => 1
            ],[
                'title' => '这是标题er',
                'thumb_media_id' => 'k42s_xRjHpWJevigJpOpDi96Q_HLftuRvRB_-yBjQNI',   // 永久的media_id
                'author' => 'logged',
//                'digest' => 'asdasdsadd',
                'show_cover_pic' => 1,
                'content' => '1、最近更新：永久图片素材新增后，将带有URL返回给开发者，开发者可以在腾讯系域名内使用（腾讯系域名外使用，图片将被屏蔽）。

                            2、公众号的素材库保存总数量有上限：图文消息素材、图片素材上限为5000，其他类型为1000。
                            
                            3、素材的格式大小等要求与公众平台官网一致：
                            
                            图片（image）: 2M，支持bmp/png/jpeg/jpg/gif格式
                            
                            语音（voice）：2M，播放长度不超过60s，mp3/wma/wav/amr格式
                            
                            视频（video）：10MB，支持MP4格式
                            
                            缩略图（thumb）：64KB，支持JPG格式
                            
                            4、图文消息的具体内容中，微信后台将过滤外部的图片链接，图片url需通过"上传图文消息内的图片获取URL"接口上传图片获取。
                            
                            5、"上传图文消息内的图片获取URL"接口所上传的图片，不占用公众号的素材库中图片数量的5000个的限制，图片仅支持jpg/png格式，大小必须在1MB以下。
                            
                            6、图文消息支持正文中插入自己帐号和其他公众号已群发文章链接的能力。
                            
                            <img src="http://mmbiz.qpic.cn/mmbiz_jpg/D2stibLCyEbRFhN9IibqHhI7vPAIicrXE1kSqQEK0XwqUsGLJlUdOvQrcXcvJngbDundQ53mSGDItwNauDIK0CFew/0?wx_fmt=jpeg">
                            
                            ',  // 图片地址只能是永久素材
                'content_source_url' => 'http://renzhonghe028.com',
                'need_open_comment' => 1,
                'only_fans_can_comment' => 1
            ],

        ];
//      k42s_xRjHpWJevigJpOpDkUKjzrtwA_krYqQ8--jvxE         一条
//      k42s_xRjHpWJevigJpOpDusJgdP1-RtznzmomRk-ouI         两条
        $data = $material->uploadNews($news);
        var_dump($data);
    }

    /**
     * 上传永久素材
     * @param Material $material
     */
    public function uploadMaterial(Material $material)
    {
//        $url = \think\facade\Env::get('ROOT_PATH') . 'back.jpg';
//        $result = $this->wx->uploadMaterial($url, 'image');

//        k42s_xRjHpWJevigJpOpDi96Q_HLftuRvRB_-yBjQNI
//        http://mmbiz.qpic.cn/mmbiz_jpg/D2stibLCyEbRFhN9IibqHhI7vPAIicrXE1kSqQEK0XwqUsGLJlUdOvQrcXcvJngbDundQ53mSGDItwNauDIK0CFew/0?wx_fmt=jpeg
//        pr($result);

//        上传视频的时候
//        DV-IpHJqWPmRysQ0kxdPGexG8auaVDlfh5fYiNl6abo 视频
        $url = \think\facade\Env::get('ROOT_PATH') . '20190429154013.mp4';
        $data = [
            'video' => $url,
            'description' => [
                'title' => '测试视频',
                'introduction' => '测试的有啥描述和简介哦！'
            ]
        ];

        $result = $material->uploadMaterial($data, Material::MATERIAL_VIDEO);
        var_dump($result);

    }

    /**
     * 根据OpenID列表群发
     * @param Material $material
     * @throws \Exception
     */
    public function sendOpenidGroup(Material $material)
    {
        $openid = [
            'ogDUY1nztPPi6fMEoYGGHz6TpafM',
            'ogDUY1kViV5KQx115LGaTP4ND8xw'
        ];
        $mediaId = 'k42s_xRjHpWJevigJpOpDusJgdP1-RtznzmomRk-ouI';

        var_dump($material->sendOpenidGroup($openid, $mediaId, 'mpnews'));
    }


    /**
     * 带参数二维码生成
     * @param QRCode $code
     * @return \think\Response
     */
    public function QRCode(QRCode $code)
    {
//        pr($this->wx->createTicket('test', true, 7200));
//        [ticket] => gQF58DwAAAAAAAAAAS5odHRwOi8vd2VpeGluLnFxLmNvbS9xLzAyM1hmNjg1MFJjb20xTVY3aDFzY3UAAgQZ69BcAwQgHAAA
//        [expire_seconds] => 7200
//        [url] => http://weixin.qq.com/q/023Xf6850Rcom1MV7h1scu

//      这里是直接输出图片，可用 file_put_contents()把内容写入文件保存
        return response($code->getCode('gQF58DwAAAAAAAAAAS5odHRwOi8vd2VpeGluLnFxLmNvbS9xLzAyM1hmNjg1MFJjb20xTVY3aDFzY3UAAgQZ69BcAwQgHAAA'))->contentType('image/jpeg');
    }

    /**
     * 删除永久素材
     * @param Material $material
     */
    public function delMaterial(Material $material)
    {
        $result = $material->delMaterial('DV-IpHJqWPmRysQ0kxdPGexG8auaVDlfh5fYiNl6abo');

        if ($result === true) {
            echo '成功';
        }else{
            var_dump($result);
        }
    }

    /**
     * 取消关注
     * @param $data
     */
    public function unsubscribe($data)
    {
        // TODO: Implement unsubscribe() method.
    }
}

