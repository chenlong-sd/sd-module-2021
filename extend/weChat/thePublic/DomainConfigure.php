<?php


namespace weChat\thePublic;
use weChat\common\Helper;
use weChat\common\Config;

/**
 * 域名配置
 * Class DomainConfigure
 * @package app\common\wechat
 */
class DomainConfigure
{
    /**
     * 域名配置地址调用
     * @return bool|string|void
     * @throws \ReflectionException
     */
    public function index()
    {
        if (Helper::isPost()) {
            $this->autoRecovery();
        }else if (Helper::isGet()) { //      域名配置验证
            return $this->checkSignature();
        }
    }

    /**
     * 域名配置验证
     * @return bool|mixed
     */
    private function checkSignature()
    {
        $tmpArr = [$_GET['timestamp'], $_GET['nonce'], Config::get('domainVerifyToken')];
        sort($tmpArr, SORT_STRING);
        $tmpStr = sha1( implode( $tmpArr ) );

        return $_GET['signature'] == $tmpStr ? $_GET['echostr'] : false;
    }

    /**
     * 自动回复
     * @return bool|void
     * @throws \ReflectionException
     */
    private function autoRecovery()
    {
        libxml_disable_entity_loader(true);
        $data = file_get_contents('php://input');
        $values = simplexml_load_string($data, \SimpleXMLElement::class, LIBXML_NOCDATA);

//            安全模式下，需要对密文进行解密
        if (Config::get('safeMode') && !$values = $this->decrypt($values)) return false;

        $messageEventHandle = new MessageEventHandle();

        if ($values->MsgType == 'event') {  //   事件类型
            $messageEventHandle->eventMsg($values);
        } elseif ($values->MsgType == 'text') { //   文字消息类型
            $messageEventHandle->msgXml($values, 'textMsg');
        } elseif ($values->MsgType == 'image') {    // 图片消息
            $messageEventHandle->msgXml($values, 'imageMsg');
        } else {
//                调用方法名为：以消息类型加上Msg，参考上面的文字消息和图片消息哦
//                【voice，video，shortvideo，location，link
            $messageEventHandle->msgXml($values, sprintf('%sMsg', $values->MsgType));
        }
    }

    /**
     * @param $values
     * @return bool|\SimpleXMLElement
     * @throws \ReflectionException
     */
    protected function decrypt($values)
    {
        $crypt = WXBizMsgCrypt::class;
        if (!class_exists($crypt)) {
            Helper::log('not found class ' . $crypt);
            return false;
        }
        $method = new \ReflectionMethod(new $crypt(), 'decryptMsg');
        $newValues = $method->invokeArgs(new $crypt(), [$values, $_GET, Config::get('appId'), Config::get('encodingAppKey'), Config::get('domainVerifyToken')]);
        if (is_int($newValues)) {   // 解密密文中出现错误。记录错误码
            Helper::log($newValues);
            return false;
        }else{
            return simplexml_load_string($newValues, \SimpleXMLElement::class, LIBXML_NOCDATA);
        }
    }
}

