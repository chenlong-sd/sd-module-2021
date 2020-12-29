<?php


namespace sdModule\common\helper;

/**
 * 进制转换，非 2,8,16
 * Class BinarySystem
 * @package sdModule\common\helper
 */
class BinarySystem
{
    const DIGIT_36 = 36;
    const DIGIT_59 = 59;
    const DIGIT_62 = 62;

    private static $aggregate = [
        self::DIGIT_36 => '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ',
        self::DIGIT_59 => '023456789ABCDEFGHJKLMNOPQRSTUVWXYZabcdefghijkmnopqrstuvwxyz',
        self::DIGIT_62 => '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz'
    ];

    /**
     * 10 进制转 其他进制
     * @param int|string $int 10进制数字
     * @param int $digit    要转的进制标识
     * @return string
     */
    public function binarySystemTo($int, int $digit = self::DIGIT_36)
    {
        return self::transform($int, self::$aggregate[$digit]);
    }

    /**
     * 其他进制转10进制
     * @param string|int $data  其他进制代表值
     * @param int $digit        其他进制标识
     * @return float|int
     */
    public function binarySystemFrom($data, $digit = self::DIGIT_36)
    {
        $value = 0;
        $data = strrev($data);
        for ($i = 0; isset($data[$i]); $i++) {
            $value +=  floatval((int)(strpos(self::$aggregate[$digit], $data[$i])) * pow($digit, $i));
        }
        return $value;
    }

    /**
     * 指定不包含某个值的
     * @param int    $int
     * @param string $appoint
     * @return string
     */
    public function notAppointTo(int $int, $appoint = null)
    {
        $str = strtr(self::$aggregate[self::DIGIT_62], [$appoint => '']);

        return self::transform($int, $str);
    }

    /**
     * 转换
     * @param $int
     * @param $string
     * @return string
     */
    private static function transform($int, $string)
    {
        $value = '';
        $length = strlen($string);
        while ($int >= $length) {
            $value .= $string[intval(fmod(floatval($int), $length))];
            $int = floor(floatval($int) / $length);
        }
        return strrev($value . $string[intval($int)]);
    }
}

