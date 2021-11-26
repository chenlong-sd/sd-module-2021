<?php
/**
* NewDictionary.php
* DateTime: 2021-11-24 23:14:45
*/

namespace app\admin\service\system;

use app\admin\AdminBaseService;
use app\common\service\BackstageListsService;
use app\common\SdException;
use app\admin\model\system\NewDictionary as MyModel;

/**
* 新字典表 服务层
* Class NewDictionaryService
* @package app\admin\service\system\NewDictionaryService
*/
class NewDictionaryService extends AdminBaseService
{
    /**
     * 列表数据
     * @param BackstageListsService $service
     * @return \think\response\Json
     * @throws \app\common\SdException
     */
    public function listData(BackstageListsService $service): \think\response\Json
    {
        $model = MyModel::field('i.id,i.type type_true,i.type,i.sign,i.name,i.image,i.introduce,i.update_time');

        return $service->setModel($model)->getListsData();
    }

    /**
     * @param array $data
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/11/26
     */
    protected function beforeWrite(array &$data)
    {
        if (!empty($data['customize'])) {
            $data['customize'] = array_filter($data['customize'], function ($v){
                return trim($v['d_key']) && trim($v['d_title']) && trim($v['d_type']);
            });

            $data['customize'] = json_encode($data['customize'], JSON_UNESCAPED_UNICODE);
        }
    }
}
