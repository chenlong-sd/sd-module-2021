<?php
/**
 * Date: 2020/11/5 17:06
 * User: chenlong <vip_chenlong@163.com>
 */

namespace sdModule\common;

use sdModule\common\helper\{ALiYunSms,
    Ciphertext,
    Csv,
    JWT,
    SCRedis,
    CcSms,
    Infinite,
    Password,
    Pinyin,
    RedisGeoHash,
    ALiYunOSS,
    BinarySystem,
    Download,
    FileStorage,
    ReflexCall,
    Excel,
    Resources};

/**
 * 自定义的扩展基类
 * Class Sc
 * @method static SCRedis       redis()
 * @method static CcSms         CcSms($phone)
 * @method static Ciphertext    ciphertext()
 * @method static Download      download()
 * @method static FileStorage   fileStorage(string $group = null)
 * @method static Infinite      infinite(array $data)
 * @method static JWT           jwt(array $data = [])
 * @method static Password      password()
 * @method static Pinyin        pinyin()
 * @method static RedisGeoHash  redisGeoHash()
 * @method static ALiYunOSS     aLiYunOSS()
 * @method static BinarySystem  binarySystem()
 * @method static ReflexCall    reflex()
 * @method static Excel         excel(string $excel_path, string $mode = 'read', string $format = '')
 * @method static Resources     resource()
 * @method static ALiYunSms     aLiYunSms(string $accessKeyId = null, string $accessKeySecret = null, string $regionId = null)
 * @method static Csv           csv()
 * @package sdModule\common
 */
class Sc extends StaticCallGetInstance
{
    /**
     * @return string
     */
    protected function getNamespace(): string
    {
        return "sdModule\\common\\helper\\";
    }
}
