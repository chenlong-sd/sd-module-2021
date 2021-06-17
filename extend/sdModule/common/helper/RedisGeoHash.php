<?php
/**
 *
 * RedisGeoHash.php
 * User: ChenLong
 * DateTime: 2020/1/19 17:15
 */
namespace sdModule\common\helper;

/**
 * Class RedisGeoHash
 * @package sdModule\common\helper
 */
class RedisGeoHash
{
    private const KEYS = 'golf_address';

    const UNIT_M = 'm';     // 米
    const UNIT_KM = 'km';   // 千米
    const UNIT_MI = 'mi';   // 英里
    const UNIT_FT = 'ft';   // 英尺

    // 以下常量 针对radius方法的param参数
    const RADIUS_WITHCOORD = 'WITHCOORD'; // 返回结果会带上匹配位置的经纬度
    const RADIUS_WITHDIST = 'WITHDIST';   // 结果会带上匹配位置与给定地理位置的距离
    const RADIUS_ASC = 'ASC';             // 传入ASC为从近到远排序，传入DESC为从远到近排序
    const RADIUS_DESC = 'DESC';           // 传入ASC为从近到远排序，传入DESC为从远到近排序
    const RADIUS_WITHHASH = 'WITHHASH';   // 则返回结果会带上匹配位置的hash值
    const RADIUS_COUNT = 'COUNT';        // 传入COUNT参数，可以返回指定数量的结果， 此参数需传数组， 例：[RedisGeoHash::RADIUS_COUNT, 10]

    /**
     * redis版本支持：3.2+
     * @var \Redis
     */
    private $redis;

    /**
     * 数据存储路径
     * @return string
     */
    private function getPath()
    {
        $path = \think\facade\App::getRootPath() .  'resource' . DIRECTORY_SEPARATOR . 'geohash';
        if (!is_dir($path)) mkdir($path, 777, true);
        return $path;
    }

    /**
     * RedisGeoHash constructor.
     * @throws \Exception
     */
    public function __construct()
    {
        $this->connectRedis();
        $info = $this->redis->info();
        if(isset($info['redis_server']) && $info['redis_server'] <  3.2){
            throw new \Exception('Redis 版本低于3.2，请使用 Redis 3.2 + 版本');
        }

        if(!$this->redis->config('SET', 'dir', $this->getPath())){
            throw new \Exception('Redis dir 设置失败！');
        }
    }

    /**
     * 链连接redis服务
     */
    private function connectRedis()
    {
        $this->redis = \think\facade\Cache::store('redis')->handler();
    }

    /**
     * 新增一个成员
     * @param       $longitude
     * @param       $latitude
     * @param mixed $member 唯一标识
     * @param bool  $save
     * @return mixed
     */
    public function add($longitude, $latitude, $member, $save = true)
    {
        $result = $this->redis->rawCommand('geoadd', ...[self::KEYS, $longitude, $latitude, $member]);

        if ($result !== false && $save)$this->save();
        return $result;
    }


    /**
     * 一次添加多个地址
     * @param $data
     * @return mixed
     */
    public function addAll($data)
    {
        array_unshift($data, self::KEYS);
        $result = $this->redis->rawCommand('geoadd', ...$data);
        if ($result !== false) $this->save();
        return $result;
    }

    /**
     * 返回指定成员的坐标
     * @param $member
     * @return mixed
     */
    public function getPos($member)
    {
        if (is_array($member)) {
            array_unshift($member, self::KEYS);
        } else {
            $member = [self::KEYS, $member];
        }

        return $this->redis->rawCommand('geopos', ...$member);
    }

    /**
     * 获取指定的成员的 geohash 值
     * @param $member
     * @return mixed
     */
    public function getHash($member)
    {
        if (is_array($member)) {
            array_unshift($member, self::KEYS);
        } else {
            $member = [self::KEYS, $member];
        }

        return $this->redis->rawCommand('geohash', ...$member);
    }

    /**
     * 返回两个成员的距离，或指定坐标和成员的距离
     * @param mixed  $member1 指定的member1 或 包含坐标的数组: [longitude, latitude]
     * @param mixed  $member2 指定的member2 或 包含坐标的数组: [longitude, latitude]
     * @param string $unit
     * @return bool|mixed
     */
    public function getDist($member1, $member2, $unit = 'km')
    {
        if (is_array($member1)) {
            $randMember1 = self::KEYS . "_TMP_" .  mt_rand(1000, 9999);
            $addResult = $this->add($member1[0], $member1[1], $randMember1, false);
            if (!$addResult) return false;
            $member1 = $randMember1;
        }

        if (is_array($member2)) {
            $randMember = self::KEYS . "_TMP_" . mt_rand(1000, 9999);
            $addResult = $this->add($member2[0], $member2[1], $randMember, false);
            if (!$addResult) return false;
            $member2 = $randMember;
        }

        $dist = $this->redis->rawCommand('geodist', ...[self::KEYS, $member1, $member2, $unit]);

        isset($randMember1) and $this->redis->zRem(self::KEYS, $randMember1);
        isset($randMember) and $this->redis->zRem(self::KEYS, $randMember);

        return $dist;
    }


    /**
     * 返回指定的坐标附近的成员 member
     * @param float|string $longitude 经度
     * @param float|string $latitude  纬度
     * @param int          $radius    范围
     * @param string       $unit      单位
     * @param array        $param     额外参数，参考常量 RADIUS_*,
     * @return mixed
     * @example \RedisGeoHash::init()->getRadius(104.074411,  30.6203098, 10, \RedisGeoHash::UNIT_KM, [
     *          [\RedisGeoHash::RADIUS_COUNT, 6], \RedisGeoHash::RADIUS_ASC, \RedisGeoHash::RADIUS_WITHDIST
     *          ])
     */
    public function getRadius($longitude, $latitude, int $radius, $unit = self::UNIT_KM, $param = [])
    {
        $var = [self::KEYS, $longitude, $latitude, $radius, $unit];
        foreach ($param as $value) {
            if (is_array($value)) {
                $var[] = $value[0];
                $var[] = $value[1];
            } else {
                $var[] = $value;
            }
        }

        return $this->redis->rawCommand('georadius', ...$var);
    }


    public function save()
    {
        return $this->redis->bgsave();
    }

}

