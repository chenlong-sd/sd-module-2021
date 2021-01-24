<?php
/**
 * Date: 2020/10/23 12:42
 * User: chenlong <vip_chenlong@163.com>
 */

namespace sdModule\dataBackup\driver;



use sdModule\dataBackup\Backup;
use sdModule\dataBackup\Driver;

/**
 * Class Mysql
 * @package sdModule\dataBackup\driver
 */
class Mysql implements Driver
{
    private \PDO $PDO;

    private array $tables;

    private int $batch = 500;

    const INSERT = "INSERT INTO `%s` VALUES %s;\r\n";

    /**
     * Mysql constructor.
     * @param \PDO $PDO
     * @param int $type
     */
    public function __construct(\PDO $PDO, int $type)
    {
        $this->PDO = $PDO;
        $this->tables = $this->PDO->query('SHOW TABLES')->fetchAll(\PDO::FETCH_COLUMN);
    }

    /**
     * 备份
     * @return mixed|void
     */
    public function backup()
    {
        $time = date('Y-m-d H:i:s');
        Backup::backupFileWrite("/** 数据备份时间  {$time} */\r\n\r\n");
        Backup::outputTip('开始备份..');
        Backup::outputTip("内存：" . ($start = memory_get_usage() / 8192) . '.开始备份..');

        $start_time = microtime(true);
        Backup::backupFileWrite("SET NAMES utf8mb4;\r\nSET FOREIGN_KEY_CHECKS = 0;\r\n\r\n");
        $this->backupStructure();
        Backup::backupFileWrite("SET FOREIGN_KEY_CHECKS = 0;\r\n");
        Backup::outputTip("内存：" . ($start = memory_get_usage() / 8192) . ".备份结束,耗时:" . (microtime(true) - $start_time));
    }

    /**
     * 数据结构备份
     */
    private function backupStructure()
    {
        Backup::backupFileWrite("-- 数据备份开始 \r\n\r\n");
        Backup::outputTip("数据备份开始..");
        $start_time = microtime(true);

        foreach ($this->tables as $table) {
            Backup::backupFileWrite("-- {$table}数据结构备份开始 \r\n\r\n");
            $data = $this->PDO->prepare("SHOW CREATE TABLE `$table`");
            $data->execute();
            $sql = $data->fetch(\PDO::FETCH_ASSOC)['Create Table'] . ";\r\n\r\n";
            Backup::backupFileWrite("DROP TABLE IF EXISTS `{$table}`;\r\n");
            Backup::backupFileWrite($sql);
            Backup::backupFileWrite("-- {$table}数据结构备份完成 \r\n\r\n");
            $this->backupData($table);
        }

        Backup::backupFileWrite("-- 数据份结束 \r\n\r\n");
        Backup::outputTip("数据备份结束,耗时" . (microtime(true) - $start_time));
    }

    /**
     * 数据备份
     * @param $table
     */
    private function backupData($table)
    {
        Backup::backupFileWrite("-- {$table} 数据备份开始。\r\n\r\n");
        Backup::outputTip("{$table} 数据备份开始。请稍候....");
        $start_time = microtime(true);
        $sql = $this->PDO->prepare("SELECT * FROM `{$table}`", [\PDO::ATTR_CURSOR => \PDO::CURSOR_SCROLL]);
        $sql->execute();
        $fd = fopen(Backup::$filename, 'a');
        $value = [];
        $length = $i = 0;
        while ($row = $sql->fetch(\PDO::FETCH_NUM)) {
            $value[] = "('" . implode("','", $row) . "')";
            if (!$length) {
                $length = strlen(current($value));
            }
            $i++;
            if (($length <= 8192 && $i <= 500) || ($length >= 8192 && $i <= 100)) {
                continue;
            }
            fwrite($fd, sprintf(self::INSERT, $table, implode(',', $value)));
            $value = [];
            $i = 0;
        }
        if ($value) {
            fwrite($fd, sprintf(self::INSERT, $table, implode(',', $value)));
        }
        fclose($fd);
        Backup::outputTip("{$table} 数据完成。耗时" . (microtime(true) - $start_time));
    }

    /**
     * 输出提示
     * @param string $print
     */
    private static function tipPrint(string $print)
    {
        dump($print);
    }
}
