<?php
/**
 *
 * EasyJWT.php
 * User: ChenLong
 * DateTime: 2020/4/24 12:59
 */


namespace sdModule\common\helper;


class EasyJWT
{

    /**
     * 数据字符串
     * @var array
     */
    private static $string_arr = [
        "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",
    ];

    private $index = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';


    //  有效时间，单位：S（秒）
    private const EXPIRE_TIME = 7200;


    public static function encryption($exp = 60, int $param = null, $valid = self::EXPIRE_TIME)
    {
        $instance = new self();

        $randString = $instance->randString();

        $exp = $instance->transform(time() + $exp, $randString, array_search($randString, self::$string_arr));



    }

    private function randString()
    {
        return array_rand(self::$string_arr);
    }

    private function encryHandle($data)
    {

    }

    /**
     * @param int $time 有效时间，单位：S（秒）
     * @return int
     */
    private function expire(int $time)
    {
        return time() + $time;
    }

    /**
     * 转换
     * @param      $int
     * @param      $string
     * @param null $diff
     * @return string
     */
    private function transform($int, $string, $diff = null)
    {
        $value = '';
        $string = strtr($string, [$diff => '']);
        $length = strlen($string);
        while ($int >= $length) {
            $value .= $string[intval(fmod(floatval($int), $length))];
            $int = floor(floatval($int) / $length);
        }
        return strrev($value . $string[intval($int)]);
    }
}

