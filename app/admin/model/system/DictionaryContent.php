<?php
/**
 *
 * DictionaryContent.php
 * User: ChenLong
 * DateTime: 2021-11-24 23:26:33
 */


namespace app\admin\model\system;

use app\common\BaseModel;


/**
 * 字典内容 模型
 * Class DictionaryContent
 * @property $id
 * @property $new_dictionary_id
 * @property $dictionary_content
 * @property $search
 * @property $sort
 * @property $create_time
 * @property $update_time
 * @property $delete_time
 * @package app\common\model\system\DictionaryContent
 * @author chenlong <vip_chenlong@163.com>
 */
class DictionaryContent extends BaseModel
{

    protected $schema = [
        'id' => 'int',
        'new_dictionary_id' => 'int',
        'dictionary_content' => 'json',
        'search' => 'varchar',
        'sort' => 'int',
        'create_time' => 'datetime',
        'update_time' => 'datetime',
        'delete_time' => 'int',
        
    ];

}

