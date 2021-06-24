<?php


namespace app\common\service;


use app\admin\model\system\Dictionary;

/**
 * Class DictionaryService
 * @package app\common\service
 */
class DictionaryService
{
    /**
     * 获取字典
     * @param string $dictionary_sign 字典标识
     * @return array
     */
    public static function get(string $dictionary_sign)
    {
        return Dictionary::alias('i')->where('i.status', 1)->where('i.sign', $dictionary_sign)
            ->join('dictionary d', 'd.pid = i.id AND d.status = 1')
            ->column('d.dictionary_name', 'd.dictionary_value');
    }

    /**
     * 获取所有字典
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public static function getAll()
    {
        $data = Dictionary::alias('i')->where('i.status', 1)
            ->join('dictionary d', 'd.id = i.pid AND d.status = 1')
            ->field(['i.dictionary_name', 'i.dictionary_value', 'd.sign'])->select()->toArray();

        $newData = [];
        foreach ($data as $datum) {
            $newData[$datum['sign']][$datum['dictionary_value']] = $datum['dictionary_name'];
        }
        return $newData;
    }
}

