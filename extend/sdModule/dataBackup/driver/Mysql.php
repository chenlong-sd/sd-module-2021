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
    /**
     * @var \PDO
     */
    private $PDO;
    /**
     * @var array
     */
    private $tables;
    /**
     * @var int
     */
    private $type;

    const INSERT = "INSERT INTO `%s` VALUES %s;\r\n";

    /**
     * Mysql constructor.
     * @param \PDO $PDO
     * @param int $type
     */
    public function __construct(\PDO $PDO, int $type)
    {
        $this->PDO    = $PDO;
        $this->type   = $type;
        $this->tables = $this->PDO->query('SHOW TABLES')->fetchAll(\PDO::FETCH_COLUMN);
    }

    /**
     * 备份
     * @param null $table
     * @return mixed|void
     */
    public function backup($table = null)
    {
        $time = date('Y-m-d H:i:s');
        Backup::backupFileWrite("/** 数据备份时间  {$time} */\r\n\r\n");
        if (!$table) {
            Backup::outputTip('本次备份数据表数量：' . count($this->tables));
        }
        Backup::outputTip('开始备份..');
        Backup::outputTip("内存消耗：" . ($start = memory_get_usage() / 8192) . '.开始备份..');

        $start_time = microtime(true);
        Backup::backupFileWrite("SET NAMES utf8mb4;\r\nSET FOREIGN_KEY_CHECKS = 0;\r\n\r\n");
        foreach ($this->tables as $index => $table_b) {
            if ($table && $table != $table_b){
                continue;
            }
            if ($this->type & Backup::STRUCTURE) {
                $this->backupStructure($table_b);
            }
            if ($this->type & Backup::DATA) {
                $this->backupData($table_b);
            }
            if (!$table) {
                Backup::outputTip('当前已完成备份数据表数量：' . ($index + 1) . "\r\n");
            }
        }
        Backup::backupFileWrite("SET FOREIGN_KEY_CHECKS = 0;\r\n");
        Backup::outputTip("备份结束,内存消耗：" . ($start = memory_get_usage() / 8192) . ".耗时:" . (microtime(true) - $start_time) . '秒');
    }

    /**
     * 数据结构备份
     * @param string $table
     */
    private function backupStructure(string $table)
    {
        Backup::backupFileWrite("-- {$table}数据结构备份开始 \r\n\r\n");
        Backup::outputTip("-- {$table}数据结构备份开始");
        $data = $this->PDO->prepare("SHOW CREATE TABLE `$table`");
        $data->execute();
        $sql = $data->fetch(\PDO::FETCH_ASSOC)['Create Table'] . ";\r\n\r\n";
        Backup::backupFileWrite("DROP TABLE IF EXISTS `{$table}`;\r\n");
        Backup::backupFileWrite($sql);
        Backup::backupFileWrite("-- {$table}数据结构备份完成 \r\n\r\n");
        Backup::outputTip("-- {$table}数据结构备份完成");
    }

    /**
     * 数据备份
     * @param $table
     */
    private function backupData($table)
    {
        Backup::backupFileWrite("-- {$table} 数据备份开始。\r\n\r\n");
        Backup::outputTip("{$table} 数据备份开始。请稍候....");
        if (!($this->type & Backup::STRUCTURE)) {
            Backup::backupFileWrite("TRUNCATE `{$table}`;\r\n\r\n");
        }

        $start_time = microtime(true);
        // 获取最大的id
        $max_id = $this->PDO->query("SELECT max(id) max_id FROM `{$table}`")->fetch(\PDO::FETCH_ASSOC)['max_id'];
        $start = 0;
        while (true){
            $i = $data_length = 0;
            $value = [];

            $sql = $this->PDO->prepare("SELECT * FROM `{$table}` WHERE id > $start  LIMIT 1000", [\PDO::ATTR_CURSOR => \PDO::CURSOR_SCROLL]);
            $sql->execute();

            while ($row = $sql->fetch(\PDO::FETCH_ASSOC)) {
                $start = $row['id'];
                $data_length++;
                $i++;
                $row = array_map(function ($v){
                    return is_null($v) ? "null" : "\"" . addslashes($v) . "\"";
                }, $row);
                $value[] = '(' . implode(',', $row) . ')';

                if ($i < 500) continue;

                Backup::backupFileWrite(sprintf(self::INSERT, $table, implode(',', $value)));
                Backup::outputTip("数据已备份至：$start/$max_id");
                $value = [];
                $i = 0;
            }
            if ($value) {
                Backup::backupFileWrite(sprintf(self::INSERT, $table, implode(',', $value)));
                Backup::outputTip("数据已备份至：$start/$max_id");
            }
            if ($data_length < 1000) break;
        }

        Backup::outputTip("{$table} 数据备份完成。耗时" . (microtime(true) - $start_time) . "秒");
    }
}
