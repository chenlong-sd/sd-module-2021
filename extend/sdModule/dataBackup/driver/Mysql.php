<?php
/**
 * Date: 2020/10/23 12:42
 * User: chenlong <vip_chenlong@163.com>
 */

namespace sdModule\dataBackup\driver;



use sdModule\dataBackup\Backup;

/**
 * Class Mysql
 * @package sdModule\dataBackup\driver
 */
class Mysql
{
    private \PDO $PDO;

    private array $tables;

    private int $batch = 500;

    const INSERT = "INSERT INTO `%s` VALUES %s;";

    /**、
     * Mysql constructor.
     * @param \PDO $PDO
     * @param $type
     */
    public function __construct(\PDO $PDO, $type)
    {
        $this->PDO = $PDO;
        $this->tables = $this->PDO->query('SHOW TABLES')->fetchAll(\PDO::FETCH_COLUMN);
    }

    /**
     * 备份
     */
    public function backup()
    {
        $time = date('Y-m-d H:i:s');
        Backup::backupFileWrite("/** 数据备份时间  {$time} */\r\n\r\n");
        self::tipPrint('开始备份..');
        self::tipPrint("内存：" . ($start = memory_get_usage() / 8192) . '.开始备份..');

        $start_time = microtime(true);
        Backup::backupFileWrite("SET NAMES utf8mb4;\r\nSET FOREIGN_KEY_CHECKS = 0;\r\n\r\n");
        $this->structure();
        self::tipPrint("内存：" . ($start = memory_get_usage() / 8192) . ".备份结束,耗时:" . (microtime(true) - $start_time));
    }

    /**
     * 数据结构备份
     */
    public function structure()
    {
        Backup::backupFileWrite("-- 数据备份开始 \r\n\r\n");
        self::tipPrint("数据备份开始..");
        $start_time = microtime(true);

        foreach ($this->tables as $table) {
            Backup::backupFileWrite("-- {$table}数据结构备份开始 \r\n\r\n");
            $data = $this->PDO->prepare("SHOW CREATE TABLE `$table`");
            $data->execute();
            $sql = $data->fetch(\PDO::FETCH_ASSOC)['Create Table'] . ";\r\n\r\n";
            Backup::backupFileWrite($sql);
            Backup::backupFileWrite("-- {$table}数据结构备份完成 \r\n\r\n");
            $this->data($table);
        }

        Backup::backupFileWrite("-- 数据份结束 \r\n\r\n");
        self::tipPrint("数据备份结束,耗时" . (microtime(true) - $start_time));
    }

    /**
     * 数据备份
     * @param $table
     */
    public function data($table)
    {
        Backup::backupFileWrite("-- {$table} 数据备份开始。\r\n\r\n");
        self::tipPrint("{$table} 数据备份开始。请稍候....");
        $start_time = microtime(true);
        $sql = $this->PDO->prepare("SELECT * FROM `{$table}`", [\PDO::ATTR_CURSOR => \PDO::CURSOR_SCROLL]);
        $sql->execute();
//        $data = $sql->fetchAll(\PDO::FETCH_ASSOC);
        $fd = fopen(Backup::$filename, 'a');
        while ($row = $sql->fetch(\PDO::FETCH_NUM)) {
            $s = sprintf(self::INSERT, $table, implode(',', $row)) . "\r\n";
            fwrite($fd, $s);
        }
        fclose($fd);

//        if ($data) {
//            Backup::backupFileWrite($this->insertIntoValueHandle($data, $table));
//            Backup::backupFileWrite("-- {$table} 数据备份结束。\r\n\r\n");
//        }

        self::tipPrint("{$table} 数据完成。耗时" . (microtime(true) - $start_time));
    }

    /**
     * 数据处理
     * @param array $data
     * @param string $table
     * @return string
     */
    private function insertIntoValueHandle(array $data, string $table)
    {
        $insert_into_field = '`' . implode('`,`', array_keys($data[0])) . '`';
        $str = '';
        foreach ($data as $value) {
            $str .= sprintf(self::INSERT, "`{$table}`", $insert_into_field, $this->valueSplice($value)) . "\r\n";
        }
        return $str .  "\r\n";
    }

    /**
     * 值处理
     * @param $value
     * @return false|string
     */
    private function valueSplice($value)
    {
        $v_str = '';
        foreach ($value as $val) {
            if (is_numeric($val)) {
                $v_str .= ', ' . $val;
            }elseif (is_null($val)){
                $v_str .= ', null';
            }else{
                $v_str .= ", '{$val}'";
            }
        }
        return substr($v_str, 1);
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
