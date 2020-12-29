<?php

namespace sdModule\nlp;

/**
 * 敏感词处理
 * Class SensitiveWordFilter
 * @package sdModule\nlp
 */
class SensitiveWordFilter
{
    /**
     * 词库资源路径
     */
    private const SRC = __DIR__ . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR;

    /** @var string 替换字符 */
    private const REPLACE = '***';

    /** @var int 所有的 */
    const TYPE_ALL = 511;
    /** @var int 广告 */
    const TYPE_GUANG_GAO = 1;
    /** @var int 暴恐 */
    const TYPE_BAO_KONG = 2;
    /** @var int 敏感词 */
    const TYPE_MIN_GAN = 4;
    /** @var int 民生 */
    const TYPE_MIN_SHENG = 8;
    /** @var int 色情 */
    const TYPE_SE_QING = 16;
    /** @var int 涉枪涉爆 */
    const TYPE_SHE_QIANG_SHE_BAO = 32;
    /** @var int 网址 */
    const TYPE_WANG_ZHI = 64;
    /** @var int 政治 */
    const TYPE_ZHENG_ZHI = 128;
    /** @var int 反动政治 */
    const TYPE_FNA_ZHENG_ZHI = 256;

    /**
     * 词库文件
     * @var array
     */
    private static $load = [
        self::TYPE_BAO_KONG             => self::SRC . 'bk.txt',
        self::TYPE_GUANG_GAO            => self::SRC . 'adv.txt',
        self::TYPE_MIN_GAN              => self::SRC . 'mgc.txt',
        self::TYPE_MIN_SHENG            => self::SRC . 'msck.txt',
        self::TYPE_SE_QING              => self::SRC . 'sq.txt',
        self::TYPE_SHE_QIANG_SHE_BAO    => self::SRC . 'sqsb.txt',
        self::TYPE_WANG_ZHI             => self::SRC . 'wz.txt',
        self::TYPE_ZHENG_ZHI            => self::SRC . 'zz.txt',
        self::TYPE_FNA_ZHENG_ZHI        => self::SRC . 'zzfd.txt',
    ];

    /**
     * 排除词
     * @var array
     */
    private static $except = [];

    /**
     * 自定义
     * @var array
     */
    private static $custom = [];

    /**
     * 排除词
     * @param array $except
     * @return SensitiveWordFilter
     */
    public static function except(array $except)
    {
        $instance = new self();
        $instance::$except = $except;
        return $instance;
    }

    /**
     * 自定义词汇
     * @param array $custom
     * @return SensitiveWordFilter
     */
    public static function custom(array $custom)
    {
        $instance = new self();
        $instance::$custom = $custom;
        return $instance;
    }


    /**
     * 过滤
     * @param string $data 需要过滤的词
     * @param int $resource 词库
     *  多个词库：       SensitiveWordFilter::TYPE_BAO_KONG | SensitiveWordFilter::TYPE_MIN_SHENG，
     *  排除某个词库：     SensitiveWordFilter::TYPE_ALL ^ SensitiveWordFilter::TYPE_BAO_KONG
     * @param string $replace 替换的字符
     * @return mixed
     */
    public static function handle(string $data, int $resource = self::TYPE_ALL, string $replace = self::REPLACE)
    {
        foreach (self::$load as $key => $file) {
            if ($key & $resource) {
                $data = self::filterHandle($file, $data, $replace);
            }
        }

        if (self::$custom) {
            foreach (self::$custom as $word) {
                $data = strtr($data, $word, $replace);
            }
        }

        return $data;
    }

    /**
     * 过滤文字
     * @param string $file 文件名
     * @param string $data 要处理的字符串
     * @param string $replace 替换为replace
     * @return string
     */
    private static function filterHandle(string $file, string $data, string $replace)
    {
        $fileHandle = fopen($file, "r");
        if (!$fileHandle) return $data;
        do {
            $var = trim(fgets($fileHandle));
            if (in_array($var, self::$except)) continue;
            $data = strtr($data, [$var => $replace]);
        } while (!feof($fileHandle));

        fclose($fileHandle);
        return $data;
    }
}






