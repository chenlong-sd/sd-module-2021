<?php
/**
 *
 * NewDictionary.php
 * DateTime: 2021-11-24 23:14:44
 */

namespace app\admin\model\system;

use app\common\BaseModel;
use app\admin\enum\NewDictionaryEnumType;

/**
 * 新字典表 模型
 * @property $id
 * @property $type
 * @property $sign
 * @property $name
 * @property $image
 * @property $introduce
 * @property $customize
 * @property $create_time
 * @property $update_time
 * @property $delete_time
 * Class NewDictionary
 * @package app\admin\model\system\NewDictionary
 */
class NewDictionary extends BaseModel
{

    protected $schema = [
        'id' => 'int',
        'type' => 'tinyint',
        'sign' => 'varchar',
        'name' => 'varchar',
        'image' => 'varchar',
        'introduce' => 'varchar',
        'customize' => 'json',
        'create_time' => 'datetime',
        'update_time' => 'datetime',
        'delete_time' => 'int',

    ];
    
    /**
     * 类型
     * @param $value
     * @return string
     * @throws \Exception
     */   
    public function getTypeAttr($value): string
    {
        return NewDictionaryEnumType::create($value)->getDes();
    }

    /**
     * 字典内容
     * @return \think\model\relation\HasMany
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/11/27
     */
    public function content()
    {
        return $this->hasMany(DictionaryContent::class, 'new_dictionary_id')->field('new_dictionary_id,dictionary_content');
    }

}
