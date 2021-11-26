<?php
/**
 *
 * DictionaryContent.php
 * DateTime: 2021-11-24 23:26:33
 */

namespace app\admin\validate\system;

use app\common\BaseValidate;

/**
 * 字典内容 验证器
 * Class DictionaryContent
 * @package app\common\validate\system\DictionaryContent
 */
class DictionaryContent extends BaseValidate
{
    protected $rule = [
        'id|字典内容' => 'require|number',
        'new_dictionary_id|所属字典' => 'require|number',
    ];

    protected $scene = [
        'create' => ['new_dictionary_id'],
        'update' => ['id', 'new_dictionary_id'],
    ];
}
