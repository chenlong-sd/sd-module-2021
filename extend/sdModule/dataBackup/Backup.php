<?php
/**
 * Date: 2020/10/23 12:15
 * User: chenlong <vip_chenlong@163.com>
 */

namespace sdModule\dataBackup;


class Backup
{
    const STRUCTURE = 1; // 结构
    const DATA = 2; // 数据
    const ALL = 3;  // 全部

    private const DSN = '%s:host=%s;dbname=%s;port=%d;charset=%s';
    /**
     * @var \PDO
     */
    private \PDO $PDO;

    private string $dsn = '';

    private string $driver = '';

    private string $table = '';

    public static string $filename = '';

    public static string $dir = '';

    public function __construct(string $host, string $database, int $port = 3306, string $driver = 'mysql', string $charset = 'utf8mb4')
    {
        $this->driver = $driver;
        self::$dir = __DIR__ . DIRECTORY_SEPARATOR . 'data';
        if (!is_dir(self::$dir)) {
            mkdir(self::$dir, '0777', true);
        }
        $this->dsn = sprintf(self::DSN, $driver, $host, $database, $port, $charset);
    }


    public function connect(string $user, string $password, array $options = [])
    {
        $this->PDO = new \PDO($this->dsn, $user, $password, $options);
        return $this;
    }

    /**
     * 备份指定表
     * @param string $table
     * @param int $type
     * @return mixed
     * @throws \ReflectionException
     */
    public function backupTable(string $table, int $type = self::STRUCTURE)
    {
        $this->table = $table;
        self::$filename = implode(DIRECTORY_SEPARATOR, [self::$dir, 'table', $table, 'table_' . date('YmdHis') . '.sql']);
        return $this->backup($type);
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
        $driverClass->backup();
    }

    public function resetIni()
    {
        ini_set('max_execution_time', 0);
        ini_set('memory_limit', '512M');
    }

    /**
     * 恢复数据
     * @param string $filename
     * @param string $table
     */
    public function dataRecovery(string $filename, string $table = '')
    {
        $filepath = $table ? implode(DIRECTORY_SEPARATOR, [self::$dir, 'table', $table, $filename])
            : implode(DIRECTORY_SEPARATOR, [self::$dir, $filename]);
        self::outputTip('数据恢复开始....,内存：' . memory_get_usage());
        $fd = fopen($filepath, 'r');
        $sql = '';
        while (($row = fgets($fd)) !== false) {
            $rowSql = trim($row);
            $first  = substr($rowSql, 0, 2);
            if (empty($rowSql) || (($first === '--'  || $first === '/*') && !$sql)) {
                continue;
            }
            $sql .= $rowSql;
            if (substr($row, -3) !== ";\r\n") continue;
            $result = $this->PDO->exec($sql);
            if ($result === false) {
                self::outputTip("数据恢复失败, 执行SQL: " . $sql);
                break;
            }
            self::outputTip("result:" . $result);
            $sql = '';
        }
        fclose($fd);
        self::outputTip('数据恢复结束....,内存：' . memory_get_usage());
    }

    /**
     * 备份文件写入
     * @param string $data
     * @return false|int
     */
    public static function backupFileWrite(string $data)
    {
        return file_put_contents(self::$filename, $data, FILE_APPEND);
    }

    /**
     * 进度提示
     * @param string $str
     */
    public static function outputTip(string $str)
    {
        dump($str);
    }
}
