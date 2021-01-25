<?php
/**
 * Date: 2021/1/24 11:40
 * User: chenlong <vip_chenlong@163.com>
 */

namespace sdModule\dataBackup;

/**
 * 备份驱动接口
 * Interface Driver
 * @package sdModule\dataBackup
 */
interface Driver
{
    /**
     * 初始化操作
     * Driver constructor.
     * @param \PDO $PDO
     * @param int $type 类型，取值{@see Backup::ALL, Backup::STRUCTURE, Backup::DATA}
     */
    public function __construct(\PDO $PDO, int $type);

    /**
     * 备份操作
     * @param null $table
     * @return mixed|void
     */
    public function backup($table = null);
}
