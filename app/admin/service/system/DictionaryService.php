<?php
/**
 * datetime: 2021/11/8 17:51
 * user    : chenlong<vip_chenlong@163.com>
 **/

namespace app\admin\service\system;

use app\admin\AdminBaseService;
use app\admin\model\system\Dictionary as DictionaryModel;
use app\common\service\BackstageListsService;

class DictionaryService extends AdminBaseService
{
    /**
     * 列表数据
     * @param BackstageListsService $service
     * @return \think\response\Json
     * @throws \app\common\SdException
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/11/8
     */
    public function listData(BackstageListsService $service): \think\response\Json
    {
        $mode = DictionaryModel::where('pid', 0)
            ->field('i.id,i.sign,i.name,i.status,i.status status_1,i.update_time');

        return $service->setModel($mode)->getListsData();
    }

    /**
     * 字典配置列表数据
     * @return \think\response\Json
     * @throws \app\common\SdException
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/11/8
     */
    public function dictionaryConfigListData(): \think\response\Json
    {
        $model = DictionaryModel::join('dictionary', 'i.pid = dictionary.id ', 'left')
            ->field('i.id,i.dictionary_value,i.dictionary_name,i.update_time,i.status,i.status status_1');

        $service = new BackstageListsService();

        return $service->setModel($model)->getListsData();
    }

    /**
     * 字典值新增
     * @param array $data
     * @return DictionaryModel|\think\Model
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/11/8
     */
    public function dictionaryValueAdd(array $data)
    {
        return DictionaryModel::create([
            'pid'               => $data['pid'],
            'dictionary_value'  => $data['dictionary_value'],
            'dictionary_name'   => $data['dictionary_name'] ?? $data['dictionary_value'],
            'status'            => $data['status'],
        ]);
    }

    /**
     * 字典值更新
     * @param array $data
     * @param int $id
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/11/8
     */
    public function dictionaryValueUpdate(array $data, int $id)
    {
        DictionaryModel::update([
            'dictionary_value'  => $data['dictionary_value'],
            'dictionary_name'   => $data['dictionary_name'] ?? $data['dictionary_value'],
            'status'            => $data['status'],
        ], compact('id'));
    }
}
