<?php


namespace weChat\thePublic;
use weChat\common\Helper;
use weChat\common\Config;

/**
 * 微信消息加密解密
 * Class WXBizMsgCrypt
 * @package app\common\controller
 */
class WXBizMsgCrypt
{
    /** @var int  成功*/
    const OK = 0;

    /** @var int 签名验证错误</ */
    const VALIDATE_SIGNATURE_ERROR = -40001;

    /** @var int xml解析失败 */
    const PARSE_XML_ERROR = -40002;

    /** @var int sha加密生成签名失败 */
    const COMPUTE_SIGNATURE_ERROR = -40003;

    /** @var int encodingAesKey  */
    const ILLEGAL_AES_KEY = -40004;

    /** @var int appid 校验错误 */
    const VALIDATE_APP_ID_ERROR = -40005;

    /** @var int aes 加密失败 */
    const ENCRYPT_AES_ERROR = -40006;

    /** @var int aes 解密失败 */
    const DECRYPT_AES_ERROR = -40007;

    /** @var int 解密后得到的buffer非法 */
    const ILLEGAL_BUFFER = -40008;

    /** @var int base64加密失败 */
    const ENCODE_BASE64_ERROR = -40009;

    /** @var int base64解密失败 */
    const DECODE_BASE64_ERROR = -40010;

    /** @var int 生成xml失败 */
    const GEN_RETURN_XML_ERROR = -40011;

    /** @var int  */
    public static $block_size = 32;

    /**
     * 将公众平台回复用户的消息加密打包.
     * @param $replyMsg string  回复原文xml数据
     * @param $token    string  token
     * @param $app_id   string  appid
     * @param $key      string  EncodingAESKey
     * @return array|int    新的包含加密的数据
     */
    public function encryptMsg($replyMsg, $token, $app_id, $key)
    {
        $timeStamp = $_SERVER['REQUEST_TIME'];
        $nonce = $this->getRandomStr(mt_rand(6,8));

        $encrypt = $this->encrypt($replyMsg, $app_id, $key);
        if (is_int($encrypt)) {
            return $encrypt;
        }

        $sign = $this->getSHA1($token, $timeStamp, $nonce, $encrypt);

        if (is_int($sign)) {
            return $sign;
        }

        return [
            'Encrypt' => $encrypt,
            'MsgSignature' => $sign,
            'TimeStamp' => $timeStamp,
            'Nonce' => $nonce
        ];
    }

    /**
     * 对明文消息进行加密
     * @param $text string  回复原xml数据
     * @param $app_id   string  appid
     * @param $key      string  EncodingAESKey
     * @return int|string
     */
    public function encrypt($text, $app_id, $key)
    {
        try {
            $key = base64_decode($key . '=');
            $iv = substr($key, 0, 16);
            $text = $this->getRandomStr() . pack("N", strlen($text)) . $text . $app_id;
            $text = $this->PKCS7Encode($text);

            $encrypt = openssl_encrypt($text, 'AES-256-CBC', $key, OPENSSL_RAW_DATA | OPENSSL_ZERO_PADDING, $iv);
            return base64_encode($encrypt);
        } catch (\Exception $exception) {
            return self::ENCRYPT_AES_ERROR;
        }
    }

    /**
     * 检验消息的真实性，并且获取解密后的明文
     * @param $post array|object    POST 数据
     * @param $get  array           GET 数据
     * @param $app_id   string      appid
     * @param $key      string      EncodingAESKey
     * @param $token    string      token
     * @return bool|int|string
     */
    public function decryptMsg($post, $get, $app_id, $key, $token)
    {
//        验证key的长度有效性
        if (strlen($key) != 43) {
            return self::ILLEGAL_AES_KEY;
        }

//      查看小xml的加密数据是否得到
        if (empty($post->Encrypt)) {
            return self::PARSE_XML_ERROR;
        }

        $timestamp = $get['timestamp'] ?? time();

        $sign = $this->getSHA1($token, $timestamp, $get['nonce'], $post->Encrypt);

        if (is_int($sign)) {
            return $sign;
        }
        if ($sign != $get['msg_signature']) {
            return self::VALIDATE_SIGNATURE_ERROR;
        }

        return $this->decrypt($post->Encrypt, $app_id, $key);
    }

    /**
     * 对密文进行解密
     * @param $encrypted   string   需要解密的密文
     * @param $app_id      string   appid
     * @param $key         string   EncodingAESKey
     * @return bool|int|string
     */
    public function decrypt($encrypted, $app_id, $key)
    {
        $key = base64_decode($key . '=');
        try {

            $base64Decode = base64_decode($encrypted);
            $iv = substr($key, 0, 16);
            $decryptData = openssl_decrypt($base64Decode, 'AES-256-CBC', $key, OPENSSL_RAW_DATA|OPENSSL_ZERO_PADDING, $iv);

            $result = $this->PKCS7Decode($decryptData);
            if (strlen($result) < 16) return self::DECRYPT_AES_ERROR;

            $content = substr($result, 16, strlen($result));
            $len_list = unpack("N", substr($content, 0, 4));
            $xml_len = $len_list[1];
            $xml_content = substr($content, 4, $xml_len);
            $from_app_id = substr($content, $xml_len + 4);

            return $from_app_id != $app_id ? self::VALIDATE_APP_ID_ERROR : $xml_content;

        } catch (\Exception $exception) {
            return self::DECRYPT_AES_ERROR;
        }
    }

    /**
     * 对解密后的明文进行补位删除
     * @param $text string  解密后的明文
     * @return bool|string  删除填充补位后的明文
     */
    protected function PKCS7Decode($text)
    {
        $pad = ord(substr($text, -1));
        if ($pad < 1 || $pad > self::$block_size) {
            $pad = 0;
        }
        return substr($text, 0, (strlen($text) - $pad));
    }

    /**
     * 对需要加密的明文进行填充补位
     * @param $text string  需要进行填充补位操作的明文
     * @return string   补齐明文字符串
     */
    protected function PKCS7Encode($text)
    {
        $text_length = strlen($text);
        //计算需要填充的位数
        $amount_to_pad = self::$block_size - ($text_length % self::$block_size);
        if ($amount_to_pad == 0) {
            $amount_to_pad = self::$block_size;
        }
        //获得补位所用的字符
        $pad_chr = chr($amount_to_pad);
        return $text . str_repeat($pad_chr, $amount_to_pad);
    }

    /**
     * 用SHA1算法生成安全签名
     * @param string $token 票据
     * @param string $timestamp 时间戳
     * @param string $nonce 随机字符串
     * @param string $encrypt_msg 密文消息
     * @return int|string
     */
    public function getSHA1($token, $timestamp, $nonce, $encrypt_msg)
    {
        //排序
        try {
            $array = [$encrypt_msg, $token, $timestamp, $nonce];
            sort($array, SORT_STRING);
            $str = implode($array);
            return sha1($str);
        } catch (\Exception $e) {
            return self::COMPUTE_SIGNATURE_ERROR;
        }
    }

    /**
     * 随机生成16位字符串
     * @param $length int   字符串长度
     * @return string 生成的字符串
     */
    protected function getRandomStr($length = 16)
    {
        $str = "";
        $str_pol = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz";
        $max = strlen($str_pol) - 1;
        for ($i = 0; $i < $length; $i++) {
            $str .= $str_pol[mt_rand(0, $max)];
        }
        return $str;
    }
}

