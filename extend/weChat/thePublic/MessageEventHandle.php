<?php


namespace weChat\thePublic;
use weChat\common\Helper;
use weChat\common\Config;

/**
 * 公众号被动回复
 * Class MessageEventHandle
 * @package app\common\wechat
 */
class MessageEventHandle
{
    /**
     * 被动回复消息组建
     * 文本直接回复对应字符串
     * 图片和语音直接回复 media_id 的字符串
     * 视频回复 Video 里面的，以数组方式，音乐则是 Music
     * 图文回复 Articles 里面的，以二维数组方式，一个 item 一个子数组
     * @param object $data  微信的xml准对象数据
     * @param string|array $contentMethod 回掉获取内容的方法名称,或是带方法名称和参数数组的数组
     * @param string $type  回复类型，可在回调里面返回 用数组第二个值来替换，第一个值为正常返回值
     *                      取值：text | image | voice | video | music | news
     * @throws \ReflectionException
     */
    public function msgXml($data, $contentMethod, $type = 'text')
    {
//        判断方法是否带额外参数
        if (is_array($contentMethod)) {
            list($method, $args) = $contentMethod;
            $args[] = $data;
            $content = $this->configMethodCall($method,  $args);
        }else{
            $content = $this->configMethodCall($contentMethod, [$data]);
        }

//        判断返回数据是否带有返回类型MsgType
        if (is_array($content)) {
            list($content, $type) = $content;
        }

//      没有回复内容，则直接回复success
        if (empty($content)) {
            echo 'success';
            return;
        }
        $xmlArr = $this->xmlData($content, $data, $type);

        $xml = Helper::xml($xmlArr);

        echo Config::get('safeMode') ? $this->encrypt($xml) : $xml;
    }

    /**
     * event 事件消息推送，没有地自己补上
     * @param $data
     * @throws \ReflectionException
     */
    public function eventMsg($data)
    {
        switch ($data->Event) {
            case 'subscribe':   // 关注
                $this->msgXml($data, 'subscribe');
                break;
            case 'unsubscribe': // 取消关注
                $this->configMethodCall('unsubscribe', [$data]);
                break;
            case 'CLICK':   //  自定义菜单点击
            case 'location_select':
            case 'pic_sysphoto':
            case 'scancode_push':
                $this->msgXml($data, 'click');
                break;
            case 'SCAN':    // 扫描二维码
                $this->msgXml($data, 'scan');
                break;
            default:
                echo 'success';
        }
    }



    /**
     * 消息加密
     * @param $xml
     * @return string
     * @throws \ReflectionException
     */
    protected function encrypt($xml)
    {
        $crypt = WXBizMsgCrypt::class;
        if (!class_exists($crypt)) {
            Helper::log('not found class ' . $crypt);
            return '';
        }
        $method = new \ReflectionMethod(new $crypt(), 'encryptMsg');
        $newValues = $method->invokeArgs(new $crypt(), [$xml, Config::$domainVerifyToken, Config::$appId, Config::$encodingAppKey]);
        if (is_int($newValues)) {   // 加密明文中出现错误。记录错误码
            Helper::log('加密：' . $newValues);
            return '';
        }else{
            return Helper::xml($newValues);
        }
    }

    /**
     * xml 数据
     * @param $content
     * @param $data
     * @param $type
     * @return array
     */
    protected function xmlData($content, $data, $type)
    {
//        xml 消息通用数据
        $xmlArr = [
            'ToUserName' => $data->FromUserName,
            'FromUserName' => $data->ToUserName,
            'CreateTime' => $_SERVER['REQUEST_TIME'],
            'MsgType' => $type,
        ];

//        根据不同类型增加不同的xml数据
        switch ($type) {
            case 'text':
                $xmlArr['Content'] = $content;
                break;
            case 'image':   // 图片
                $xmlArr['Image'] = [
                    'MediaId' => $content
                ];
                break;
            case 'voice':   // 音频
                $xmlArr['Voice'] = [
                    'MediaId' => $content
                ];
                break;
            case 'video':   // 视频
                $xmlArr['Video'] = $content;
//                $xmlArr['Video']['MediaId'] = $content['MediaId'];
//                empty($content['Title']) or $xmlArr['Video']['Title'] = $content['Title'];
//                empty($content['Description']) or $xmlArr['Video']['Description'] = $content['Description'];
                break;
            case 'music':   // 音乐
                $xmlArr['Music'] = $content;
//                empty($content['Title']) or $xmlArr['Music']['Title'] = $content['Title'];
//                empty($content['Description']) or $xmlArr['Music']['Description'] = $content['Description'];
//                empty($content['MusicUrl']) or $xmlArr['Music']['MusicUrl'] = $content['MusicUrl'];
//                empty($content['HQMusicUrl']) or $xmlArr['Music']['HQMusicUrl'] = $content['HQMusicUrl'];
//                $xmlArr['Music']['ThumbMediaId'] = $content['ThumbMediaId'];
                break;
            case 'news':    // 图文
                $xmlArr['ArticleCount'] = count($content);
                $xmlArr['Articles'] = $content;
                break;
            default:
                $xmlArr['Content'] = $content;
        }
        return $xmlArr;
    }

    /**
     * 调用配置函数
     * @param string $method    方法名
     * @param array  $args      方法参数
     * @return mixed|string
     */
    protected function configMethodCall($method = '', $args = [])
    {
        try {
            $reflection = new \ReflectionMethod(Config::$callbackClass, $method);
            $class = is_object(Config::$callbackClass) ? Config::$callbackClass : new Config::$callbackClass();
            return $reflection->invokeArgs($class, $args);
        } catch (\ReflectionException $reflectionException) {
            Helper::log($reflectionException->getMessage());
        }
        return '';
    }
}

