<?php
/**
 * Date: 2020/10/23 12:42
 * User: chenlong <vip_chenlong@163.com>
 */

namespace sdModule\dataBackup\driver;



/**
 * Class Mysql
 * @package sdModule\dataBackup\driver
 */
class Mysql
{
    private \PDO $PDO;

    private array $tables;

    private int $batch = 500;

    const INSERT = "INSERT INTO %s (%s) VALUES (%s);";

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
        $this->contentWrite("/** 数据备份时间  {$time} */\r\n\r\n");
        self::tipPrint('开始备份..');

        $start_time = microtime(true);
        $this->contentWrite("SET NAMES utf8mb4;\r\nSET FOREIGN_KEY_CHECKS = 0;\r\n\r\n");
        $this->structure();
        self::tipPrint("备份结束,耗时:" . (microtime(true) - $start_time));
    }

    /**
     * 数据结构备份
     */
    public function structure()
    {
        $this->contentWrite("-- 数据备份开始 \r\n\r\n");
        self::tipPrint("数据备份开始..");
        $start_time = microtime(true);

        foreach ($this->tables as $table) {
            $this->contentWrite("-- {$table}数据结构备份开始 \r\n\r\n");
            $data = $this->PDO->prepare("SHOW CREATE TABLE `$table`");
            $data->execute();
            $sql = $data->fetch(\PDO::FETCH_ASSOC)['Create Table'] . ";\r\n\r\n";
            $this->contentWrite($sql);
            $this->contentWrite("-- {$table}数据结构备份完成 \r\n\r\n");
            $this->data($table);
        }

        $this->contentWrite("-- 数据份结束 \r\n\r\n");
        self::tipPrint("数据备份结束,耗时" . (microtime(true) - $start_time));
    }

    /**
     * 数据备份
     * @param $table
     */
    public function data($table)
    {
        $this->contentWrite("-- {$table} 数据备份开始。\r\n\r\n");
        self::tipPrint("{$table} 数据备份开始。请稍候...");
        $start_time = microtime(true);
        $sql = $this->PDO->prepare("SELECT * FROM `$table`");
        $sql->execute();
        $data = $sql->fetchAll(\PDO::FETCH_ASSOC);

        if ($data) {
            $this->contentWrite($this->insertIntoValueHandle($data, $table));
            $this->contentWrite("-- {$table} 数据备份结束。\r\n\r\n");
        }

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
     * 内容写入
     * @param string $content
     * @return false|int
     */
    private function contentWrite(string $content)
    {
        return file_put_contents(__DIR__ . '/test.sql', $content, FILE_APPEND);
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
