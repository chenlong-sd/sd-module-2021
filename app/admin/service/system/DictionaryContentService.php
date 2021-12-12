<?php
/**
* DictionaryContent.php
* DateTime: 2021-11-24 23:26:33
*/

namespace app\admin\service\system;

use app\admin\AdminBaseService;
use app\admin\enum\NewDictionaryEnumType;
use app\admin\model\system\NewDictionary;
use app\common\service\BackstageListsService;
use app\common\SdException;
use app\admin\model\system\DictionaryContent as MyModel;

/**
* 字典内容 服务层
* Class DictionaryContentService
* @package app\admin\service\system\DictionaryContentService
*/
class DictionaryContentService extends AdminBaseService
{
    /**
     * 列表数据
     * @param BackstageListsService $service
     * @return \think\response\Json
     * @throws \app\common\SdException
     */
    public function listData(BackstageListsService $service): \think\response\Json
    {
        $model = MyModel::field('i.id,i.new_dictionary_id,i.dictionary_content,i.sort,i.update_time');
        $dictionary = NewDictionary::findOrEmpty(request()->get('search.new_dictionary_id'));

        $fields =  [
            ['d_key' => 'value'],
            ['d_key' => 'name'],
        ];

        if ($dictionary->getData('type') == NewDictionaryEnumType::STRONG && $dictionary->customize) {
            $fields = array_map(function ($fc) {
                if (!empty($fc['d_options'])) {
                    $fc['d_options'] = array_column(array_map(function ($v) {
                        return explode('=', $v);
                    }, explode(',', strtr($fc['d_options'], ['，' => ',']))), 1, 0);
                }
                return $fc;
            }, json_decode($dictionary->customize, true));
        }

        return $service->setModel($model)->setEach(function ($v) use ($fields){
            $field_value = json_decode($v->dictionary_content, true);
            foreach ($fields as $field){
                $v->{$field['d_key']} = $field_value[$field['d_key']] ?? '——';
                if (is_array($v->{$field['d_key']})) {
                    $v->{$field['d_key']} = strtr(implode(',', $v->{$field['d_key']}), $field['d_options'] ?? []);
                } elseif (!empty($field['d_options'])) {
                    $v->{$field['d_key']} = strtr($v->{$field['d_key']}, $field['d_options']);
                }

            }
        })->getListsData();
    }

    /**
     * @param array $data
     * @throws SdException
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/11/27
     */
    protected function beforeWrite(array &$data)
    {
        if (!empty($data['id'])) {
            $id = $data['id'];
        }
        $dictionary_id = $data['new_dictionary_id'];
        unset($data['id'], $data['new_dictionary_id']);

        if (!$data) {
            throw new SdException('数据为空');
        }
        // 搜索字段和排序字段处理
        $search = '';
        $sort   = 0;
        $dictionary = NewDictionary::findOrEmpty($dictionary_id);
        if ($dictionary->customize && $dictionary->getData('type') == NewDictionaryEnumType::STRONG) {
            foreach (json_decode($dictionary->customize, true) as $customize){
                if (empty($customize['d_search'])) continue;

                if (in_array(1, $customize['d_search']) && !$search){
                    $search = $data[$customize['d_key']] ?? '';
                }

                if (in_array(2, $customize['d_search']) && !$sort){
                    $sort = $data[$customize['d_key']] ?? '';
                }
            }

        }

        $data = ['dictionary_content' => json_encode($data, JSON_UNESCAPED_UNICODE)];
        empty($id) or $data['id'] = $id;
        $data['new_dictionary_id'] = $dictionary_id;
        $data['search']            = $search;
        $data['sort']              = $sort;
    }

    /**
     * 数据过滤
     * @param array $data
     * @return array
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/11/27
     */
    public function fieldFilter(array $data): array
    {
        $dictionary = NewDictionary::findOrEmpty($data['new_dictionary_id']);
        $allowField = ['value', 'name'];
        if ($dictionary->customize && $dictionary->getData('type') == NewDictionaryEnumType::STRONG) {
            $allowField = array_column(json_decode($dictionary->customize, true), 'd_key');
        }

        return data_only($data, array_merge(['id', 'new_dictionary_id'], $allowField));
    }
}
