<?php


namespace weChat\offiaccount;

use weChat\common\Config;
use weChat\common\Helper;
use function Swoole\Coroutine\Http\get;

/**
 * Class Basic
 * @package weChat\offiaccount
 * @author chenlong<vip_chenlong@163.com>
 * @date
 */
class Basic
{
    /**
     * @var string 配置标签
     */
    private $configTag;

    public function __construct(string $config_tag = '')
    {
        $this->configTag = $config_tag;
    }

    /**
     * 所有的消息入口
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/5/29
     */
    public function index()
    {
        if (Helper::isPost()) {
            return $this->eventHandle();
        }else if (Helper::isGet()) {
            return $this->checkSignature();
        }
    }

    /**
     * 域名配置验证
     * @return false|mixed
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/5/29
     */
    private function checkSignature()
    {
        $tmpArr = [$_GET['timestamp'], $_GET['nonce'], Config::get('domainVerifyToken')];
        sort($tmpArr, SORT_STRING);
        $tmpStr = sha1( implode( $tmpArr ) );

        return $_GET['signature'] == $tmpStr ? $_GET['echostr'] : false;
    }

    private function eventHandle()
    {
        try {
            $messageData = $this->messageAnalyze();





        } catch (\Throwable $throwable) {
            Helper::log(
                $throwable->getMessage() . " " .
                $throwable->getFile() . " " .
                "({$throwable->getLine()})"
            );
        }
        return '';
    }

    /**
     * 消息解析
     * @return false|\SimpleXMLElement
     * @throws \Exception
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/5/29
     */
    private function messageAnalyze()
    {
        if (version_compare(PHP_VERSION, '8.0.0', '<')) {
            libxml_disable_entity_loader(true);
        }
        $data   = file_get_contents('php://input');
        $values = simplexml_load_string($data, \SimpleXMLElement::class, LIBXML_NOCDATA);

        // 安全模式下，需要对密文进行解密
        if (Config::get('safeMode')) {
            $wbmc = new WXBizMsgCrypt();
            $newValues = $wbmc->decryptMsg($values, $_GET, Config::get("base.{$this->configTag}.appId"), Config::get('encodingAppKey'), Config::get('domainVerifyToken'));
            if (is_int($newValues)) {   // 解密密文中出现错误。记录错误码
                throw new \Exception($newValues);
            }else{
                $values = simplexml_load_string($newValues, \SimpleXMLElement::class, LIBXML_NOCDATA);
            }
        }
        return $values;
    }
}

