<?php


namespace weChat\common;


use think\facade\Cache;
use think\facade\Log;

/**
 * 公用的一些操作方法
 * Trait Helper
 * @package app\common\wechat
 */
trait Helper
{
    /**
     * 判断 post 请求
     * @return bool
     */
    public static function isPost()
    {
        return isset($_SERVER['REQUEST_METHOD']) && strtoupper($_SERVER['REQUEST_METHOD']) == 'POST';
    }

    /**
     * 判断get请求
     * @return bool
     */
    public static function isGet()
    {
        return isset($_SERVER['REQUEST_METHOD']) && strtoupper($_SERVER['REQUEST_METHOD']) == 'GET';
    }

    /**
     * 需要临时存储的值
     * @param $key      string  储存值的键
     * @param $value    string  储存值
     * @param $expire   int     有效期
     */
    public static function setValue($key, $value, $expire)
    {
        Cache::set($key, $value, $expire);   // thinkphp5.1 缓存保存，可以换做其他方式保存
    }

    /**
     * 获取临时存储的值
     * @param $name
     * @return mixed
     */
    public static function getValue($name)
    {
        return Cache::get($name); // thinkphp6 缓存，可以换做其他方式
    }

    /**
     * 错误原因提示存储
     * @param $content  string  存储内容
     */
    public static function log($content)
    {
        Log::write($content, 'error');// thinkphp6 日志存储，可以换做其他方式
    }

    /**
     * 使用 CURL 发起get请求
     * @param      $url
     * @param bool $resource 是否是资源
     * @return bool|mixed|string
     */
    public static function getRequest($url, $resource = false)
    {
        $headerArray = ["Content-type:application/json;", "Accept:application/json"];
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headerArray);
        $output = curl_exec($ch);
        curl_close($ch);
        $resource or $output = json_decode($output, true);
        return $output;
    }

    /**
     * 使用 CURL 发起post请求（同时有文件及文本数据时，文本数据需转 json）
     * @param string  $url
     * @param array  $data
     * @param bool $isUpload  是否是属于纯文件上传
     * @return mixed
     */
    public static function postRequest($url, $data, $isUpload = false)
    {
        return self::post($url, $data, $isUpload);
    }

    /**
     * 使用 CURL 发起post请求（返回资源）
     * @param string  $url
     * @param array  $data
     * @param bool $isUpload  是否是属于纯文件上传
     * @return mixed
     */
    public static function postResource($url, $data, $isUpload = false)
    {
        return self::post($url, $data, $isUpload, true);
    }

    /**
     * post 请求操作
     * @param string $url 路径
     * @param mixed $data 数据
     * @param bool $isUpload 是否是属于纯文件上传
     * @param bool $isResource 是否返回资源
     * @return bool|mixed|string
     */
    private static function post(string $url, $data, bool $isUpload = false, bool $isResource = false)
    {
        if ($isUpload === false) {
            $data = json_encode($data, JSON_UNESCAPED_UNICODE);
            $headerArray = ["Content-type:application/json;charset='utf-8'", 'Accept:application/json'];
        }

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        $isUpload or curl_setopt($curl, CURLOPT_HTTPHEADER, $headerArray);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($curl);
        curl_close($curl);
        return $isResource ? $output : json_decode($output, true);
    }

    /**
     * // unicode编码转化，用于显示emoji表情
     * @param $str
     * @return mixed
     */
    public static function unicode2utf8($str = '')
    {
        return json_decode(json_encode($str));
    }

    /**
     * 组装消息回复的xml数据
     * @param array $data   所有数据
     * @param bool  $recursion  是否是xml内部数据，递归时使用
     * @return string
     */
    public static function xml($data = [], $recursion = false)
    {
        $xml = '';
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                $key = is_numeric($key) ? 'item' : $key;
                $xml .= sprintf('<%1$s>%2$s</%1$s>', $key, self::xml($value, true));
            }else{
                $xml .= sprintf('<%1$s><![CDATA[%2$s]]></%1$s>', $key, $value);
            }
        }
        return $recursion ? $xml : sprintf('<xml>%s</xml>', $xml);
    }

    /**
     * xml 数据转 数组
     * @param $xml
     * @return mixed
     */
    public static function xmlToArray($xml)
    {
        $xmlToObject = simplexml_load_string($xml, \SimpleXMLElement::class, LIBXML_NOCDATA);
        return json_decode(json_encode($xmlToObject), true);
    }
}

