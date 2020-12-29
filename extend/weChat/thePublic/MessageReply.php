<?php


namespace weChat\thePublic;

/**
 * 被动回复常规处理接口
 * Interface MessageReply
 * @package app\common\wechat
 */
interface MessageReply
{
    /**
     * 关注公众号处理
     * @param $data         object  微信推送的所有数据,包括：
     *                      ToUserName    开发者微信号
     *                      FromUserName    发送方帐号（一个OpenID）
     *                      CreateTime    消息创建时间 （整型）
     *                      MsgType    消息类型，文本为text
     *                      Content    文本消息内容
     *                      MsgId    消息id，64位整型
     * @return string|array 回复类型, 用数组第二个值来替换，第一个值为正常返回值
     *                      取值：text | image | voice | video | music | news
     */
     public function subscribe($data);

    /**
     * 文本消息处理
     * @param $data object  微信推送的所有数据，包括
     *                      ToUserName    开发者微信号
     *                      FromUserName    发送方帐号（一个OpenID）
     *                      CreateTime    消息创建时间 （整型）
     *                      MsgType    消息类型，文本为text
     *                      Content    文本消息内容
     *                      MsgId    消息id，64位整型
     * @return array|string
     */
    public function textMsg($data);

    /**
     * 图片消息处理
     * @param $data         object  微信推送的所有数据，包括
     *                      ToUserName    开发者微信号
     *                      FromUserName    发送方帐号（一个OpenID）
     *                      CreateTime    消息创建时间 （整型）
     *                      MsgType    消息类型，图片为image
     *                      PicUrl    图片链接（由系统生成）
     *                      MediaId    图片消息媒体id，可以调用获取临时素材接口拉取数据。
     *                      MsgId    消息id，64位整型
     * @return array [ 值， 'image']
     */
    public function imageMsg($data);

    /**
     * 取消关注
     * @param $data
     */
    public function unsubscribe($data);
}

