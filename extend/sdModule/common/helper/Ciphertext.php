<?php
/**
 *
 * Ciphertext.php
 * User: ChenLong <vip_chenlong@163.com>
 * DateTime: 2020/7/22 9:16
 */


namespace sdModule\common\helper;

/**
 * 密文处理
 * Class Ciphertext
 * @author ChenLong <vip_chenlong@163.com>
 * @package sdModule\common
 */
class Ciphertext
{
    /**
     * 默认加密 key
     */
    private const KEY = 'SC-SD_DS-CS';
    /**
     * 默认加密方式
     */
    const DEFAULT_METHOD = 'aes-256-cbc-hmac-sha256';

    /**
     * @var string
     */
    private $iv;


    /**
     * @param string $iv
     * @return Ciphertext
     */
    public function setIv(string $iv)
    {
        $this->iv = $iv;
        return $this;
    }

    /**
     * 密文加密，加密方式默认 aes-256-cbc-hmac-sha256， 所有方式参考函数：openssl_get_cipher_methods()
     * @param string $text 要加密的字符串
     * @param string $key 不传则使用默认的
     * @param string $method 加密方式
     * @return array|bool|string
     * @throws \Exception
     */
    public function encrypt(string $text, string $key = self::KEY, string $method = self::DEFAULT_METHOD)
    {
        if (!$key) $key = self::KEY;
        if (in_array($method, openssl_get_cipher_methods())) {
            $iv = self::getIv($method);
            $ciphertext = preg_match('/(.*ccm)|(.*gcm)/', $method)
                ? openssl_encrypt($text, $method, $key, $options = 0, $iv, $tag)
                : openssl_encrypt($text, $method, $key, $options = 0, $iv);

            return implode('.', [$ciphertext, base64_encode($iv), empty($tag) ? '' : base64_encode($tag)]);
        }
        throw new \Exception("加密方式【{$method}】不存在，加密方式请查看函数： penssl_get_cipher_methods()");
    }

    /**
     * 解密
     * @param string $ciphertext 加密后的密文
     * @param string $key
     * @param string $method    密文加密的方式
     * @return bool|string
     */
    public function decrypt(string $ciphertext, string $key = self::KEY, string $method = self::DEFAULT_METHOD)
    {
        if (!$key) $key = self::KEY;
        if (in_array($method, openssl_get_cipher_methods())) {
            list($ciphertext, $iv, $tag) = explode('.', $ciphertext);
            $iv = base64_decode($iv);
            $tag = base64_decode($tag);
            return preg_match('/(.*ccm)|(.*gcm)/', $method)
                ? openssl_decrypt($ciphertext, $method, $key, $options = 0, $iv, $tag)
                : openssl_decrypt($ciphertext, $method, $key, $options = 0, $iv);
        }
        throw new \Exception("解密方式【{$method}】不存在，加密方式请查看函数： penssl_get_cipher_methods()");
    }

    /**
     * 获取iv
     * @param string $method
     * @return string
     */
    private function getIv(string $method)
    {
        if ($this->iv){
            return strlen($this->iv) <= 16 ? str_pad($this->iv, 16) : substr($this->iv, 0, 16);
        }

        return openssl_random_pseudo_bytes(openssl_cipher_iv_length($method));
    }
}

