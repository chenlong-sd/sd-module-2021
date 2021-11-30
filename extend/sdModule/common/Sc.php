<?php
/**
 * Date: 2020/11/5 17:06
 * User: chenlong <vip_chenlong@163.com>
 */

namespace sdModule\common;

use sdModule\common\helper\{ALiYunSms,
    Ciphertext,
    Csv,
    ExpressionCalculation,
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
    Resources,
    Tree};

/**
 * 自定义的扩展基类
 * Class Sc
 * @method static SCRedis       redis() Redis 加锁
 * @method static CcSms         CcSms($phone) 传承短信
 * @method static Ciphertext    ciphertext() 字符串双向加密
 * @method static Download      download() 远程文件下载
 * @method static FileStorage   fileStorage(string $group = null) 本地文件存储数据
 * @method static Infinite      infinite(array $data) 无限极数据处理
 * @method static Tree          tree(array $data, bool $currentIsTreeData = false) 无限极数据处理
 * @method static JWT           jwt(array $data = [])
 * @method static Password      password() 密码加密， 验证
 * @method static Pinyin        pinyin() 拼音
 * @method static RedisGeoHash  redisGeoHash() GeoHash坐标处理
 * @method static ALiYunOSS     aLiYunOSS() 阿里云OSS
 * @method static BinarySystem  binarySystem() 36.59.63进制转换
 * @method static ReflexCall    reflex() 反射类
 * @method static Excel         excel(string $excel_path, string $mode = 'read', string $format = '') Excel
 * @method static Resources     resource() 资源读取，视频，音频等
 * @method static ALiYunSms     aLiYunSms(string $accessKeyId = null, string $accessKeySecret = null, string $regionId = null) 阿里云短信
 * @method static Csv           csv() csv 格式的表格
 * @method static ExpressionCalculation   expressionCalculation() 字符串算术计算
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
