<?php
/**
 *
 * BaseConfig.php
 * User: ChenLong
 * DateTime: 2021-04-02 10:49:09
 */


namespace app\admin\model\system;

use app\common\BaseModel;


/**
 * Class BaseConfig
 * @property $id
 * @property $group_id
 * @property $group_name
 * @property $key_id
 * @property $key_name
 * @property $form_type
 * @property $placeholder
 * @property $short_tip
 * @property $sort
 * @property $required
 * @property $options
 * @property $key_value
 * @property $create_time
 * @property $update_time
 * @property $delete_time
 * @package app\common\model\system\BaseConfig
 * @author chenlong <vip_chenlong@163.com>
 */
class BaseConfig extends BaseModel
{

    protected $schema = [
        'id' => 'int',
        'group_id' => 'varchar',
        'group_name' => 'varchar',
        'key_id' => 'varchar',
        'key_name' => 'varchar',
        'form_type' => 'varchar',
        'placeholder' => 'varchar',
        'short_tip' => 'varchar',
        'sort' => 'varchar',
        'required' => 'varchar',
        'options' => 'json',
        'key_value' => 'text',
        'create_time' => 'datetime',
        'update_time' => 'datetime',
        'delete_time' => 'int',
    ];




}

