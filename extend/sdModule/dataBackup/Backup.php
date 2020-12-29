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

    public function __construct(string $host, string $database, int $port = 3306, string $driver = 'mysql', string $charset = 'utf8mb4')
    {
        $this->driver = $driver;
        $this->dsn = sprintf(self::DSN, $driver, $host, $database, $port, $charset);
    }


    public function connect(string $user, string $password, array $options = [])
    {
        $this->PDO = new \PDO($this->dsn, $user, $password, $options);
        return $this;
    }

    /**
     * 开始备份
     * @param int $type
     * @return mixed
     * @throws \ReflectionException
     */
    public function backup(int $type = self::STRUCTURE)
    {
        $driver = parse_name($this->driver, 1);
        $driver = "\\sdModule\\dataBackup\\driver\\{$driver}";
        $driverClass = (new \ReflectionClass($driver))->newInstance($this->PDO, $type);
        return $driverClass->backup();
    }

    /**
     * 备份文件写入
     * @param string $data
     * @param bool $before
     * @return false|int
     */
    public static function backupFileWrite(string $data, $before = false)
    {
        $filename = __DIR__ . '/drivertest.sql';
        return $before ? file_put_contents($filename, $data)
            : file_put_contents($filename, $data, FILE_APPEND);
    }

    /**
     * 进度提示
     * @param string $str
     */
    public static function schedule(string $str)
    {
        ob_start();
        echo $str . "</br>" . str_pad('', 200, ' == ');
        ob_end_flush();
    }
}
