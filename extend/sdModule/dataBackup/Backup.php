<?php
/**
 * Date: 2020/10/23 12:15
 * User: chenlong <vip_chenlong@163.com>
 */

namespace sdModule\dataBackup;


use app\common\SdException;
use sdModule\common\Sc;

class Backup
{
    const STRUCTURE = 1; // 结构
    const DATA = 2; // 数据
    const ALL = 3;  // 全部

    private const DSN = '%s:host=%s;dbname=%s;port=%d;charset=%s';
    /**
     * @var \PDO
     */
    private $PDO;

    /**
     * @var string 链接数据库的dsn
     */
    private $dsn = '';

    /**
     * @var string 数据库驱动类型
     */
    private $driver = '';
    /**
     * @var string 表名
     */
    private $table = '';
    /**
     * @var string 文件路径
     */
    public static $filename = '';
    /**
     * 数据存储文件夹
     * @var string|mixed
     */
    public static $dir = '';

    /**
     * @var resource
     */
    private static $fd;

    /**
     * Backup constructor.
     * @param string $host
     * @param string $database
     * @param int $port
     * @param string $driver
     * @param string $charset
     */
    public function __construct(string $host, string $database, int $port = 3306, string $driver = 'mysql', string $charset = 'utf8mb4')
    {
        $this->driver = $driver;
        self::$dir = self::getDir();
        if (!is_dir(self::$dir)) {
            mkdir(self::$dir, 0777, true);
        }
        $this->dsn = sprintf(self::DSN, $driver, $host, $database, $port, $charset);
        $this->resetIni();
    }

    /**
     * 设置路径
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/10/15
     */
    private static function getDir()
    {
        return env('DATABASE.BACK_UP_LINK') ?: __DIR__ . DIRECTORY_SEPARATOR . 'data';
    }

    /**
     * @param string $user
     * @param string $password
     * @param array $options
     * @return $this
     */
    public function connect(string $user, string $password, array $options = []): Backup
    {
        $this->PDO = new \PDO($this->dsn, $user, $password, $options);
        return $this;
    }

    /**
     * 备份指定表
     * @param string $table
     * @param int $type
     * @return mixed|void
     * @throws \ReflectionException
     */
    public function backupTable(string $table, int $type = self::STRUCTURE)
    {
        $this->table = $table;
        self::$filename = implode(DIRECTORY_SEPARATOR, [self::$dir, 'table', $table, 'table_' . date('YmdHis') . '.sql']);
        if (!is_dir(dirname(self::$filename))) {
            mkdir(dirname(self::$filename), 0777, true);
        }
        $this->backup($type);
    }

    /**
     * 开始备份(默认全表
     * @param int $type
     * @return mixed|void
     * @throws \ReflectionException
     */
    public function backup(int $type = self::STRUCTURE)
    {
        if (!self::$filename) {
            self::$filename = self::$dir . DIRECTORY_SEPARATOR . 'database_' . date('YmdHis') . '.sql';
        }

        $driver = parse_name($this->driver, 1);
        $driver = "\\sdModule\\dataBackup\\driver\\{$driver}";
        /** @var Driver $driverClass */
        $driverClass = (new \ReflectionClass($driver))->newInstance($this->PDO, $type);
        try {
            self::$fd = fopen(self::$filename, 'w');
            $driverClass->backup($this->table);
            self::outputTip('--end--');
            fclose(self::$fd);
        } catch (\Throwable $exception) {
            self::outputTip($exception->getMessage());
            self::outputTip('--end--');
            fclose(self::$fd);
            unlink(self::$filename);
        }
    }

    /**
     * 重置ini设置
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/10/15
     */
    public function resetIni()
    {
        ini_set('max_execution_time', 0);
//        ini_set('memory_limit', '512M');
    }

    /**
     * 恢复数据
     * @param string $filename
     * @param string $table
     */
    public function dataRecovery(string $filename, string $table = '')
    {
        $filepath = $table
            ? implode(DIRECTORY_SEPARATOR, [self::$dir, 'table', $table, $filename])
            : implode(DIRECTORY_SEPARATOR, [self::$dir, $filename]);
        self::outputTip('数据恢复开始....,内存：' . memory_get_usage() / 8192);
        $startTime = microtime(true);
        $fd        = fopen($filepath, 'r');
        $sql       = '';
        $line      = 0;

        while (fgets($fd) !== false){
            $line++;
        }
        fseek($fd, 0);
        self::outputTip('总量数：' . $line);

        $line = 0;
        $recovery_result = true;
        while (($row = fgets($fd)) !== false) {
            $line++;
            $rowSql  = trim($row);
            $first   = substr($rowSql, 0, 2);
            if (empty($rowSql) || (($first === '--'  || $first === '/*') && !$sql)) {
                continue;
            }

            $sql .= $rowSql;
            if (substr($row, -3) !== ";\r\n" && substr($row, -2) !== ";\n") {
                continue;
            }

            $result = $this->PDO->exec($sql);
            if ($result === false) {
                self::outputTip("数据恢复失败, 执行SQL: " . $sql);
                self::outputTip($this->PDO->errorInfo());
                $recovery_result = false;
                break;
            }

            self::outputTip("result:$result, r:$line");
            $sql = '';
        }
        fclose($fd);
        $recovery_result and self::outputTip('数据恢复成功....内存消耗：' . memory_get_usage() / 8192);
        self::outputTip('总耗时：' . (microtime(true) - $startTime) . '秒');
        self::outputTip('--end--');
    }

    /**
     * 备份文件写入
     * @param string $data
     */
    public static function backupFileWrite(string $data)
    {
        fwrite(self::$fd, $data);
    }

    /**
     * 进度提示
     * @param mixed $str
     */
    public static function outputTip(...$str)
    {
        // 可换成自己需要的代码
        $redis = Sc::redis()->getRedis();
        $redis->lPush('back_tip', ...$str);
    }

    /**
     * 获取提示
     * @return false|string
     * @throws SdException
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/10/15
     */
    public static function getTip()
    {
        // 可换成自己需要的代码
        $redis = Sc::redis()->getRedis();
        $content = [];
        while ($single = $redis->rPop('back_tip')) {
            $content[] = $single;
            if ($single  === '--end--') {
                $redis->del('back_tip');
            }
        }
        return $content ?  "\r\n" . implode("\r\n", $content) : '';
    }
}
