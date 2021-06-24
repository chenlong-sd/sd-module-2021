<?php
/**
 * Dictionary.php
 * User: ChenLong
 * DateTime: 2021-05-06 21:52:58
 */

namespace app\admin\controller\system;

use app\admin\model\system\Dictionary as DictionaryModel;
use app\admin\validate\system\Dictionary as DictionaryValidate;
use \app\common\controller\Admin;
use app\common\ResponseJson;
use app\common\service\BackstageListsService;


/**
 * Class Dictionary
 * @package app\admin\controller\system\Dictionary
 * @author chenlong <vip_chenlong@163.com>
 */
class Dictionary extends Admin
{
    /**
     * 列表数据接口
     * @param BackstageListsService $service
     * @return false|mixed|\think\response\Json
     * @throws \app\common\SdException
     */
    public function listData(BackstageListsService $service)
    {
        $mode = $this->getModel()->where('pid', 0)->field('i.id,i.sign,i.name,i.status,i.status status_1,i.update_time');

        return $service->setModel($mode)->getListsData();
    }

    /**
     * 字典配置页面
     * @return false|mixed|\think\response\Json|\think\response\View
     * @throws \app\common\SdException
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/6/24
     */
    public function dictionary()
    {
        if ($this->request->isAjax()) {
            $model = $this->getModel()->join('dictionary', 'i.pid = dictionary.id ', 'left')
                ->field('i.id,i.dictionary_value,i.dictionary_name,i.update_time,i.status,i.status status_1');

            $service = new BackstageListsService();
            return $service->setModel($model)->getListsData();
        }

        return $this->fetch('common/list_page', [
            'table'     => $this->getPage()->getDictionaryPageData(),
            'search'    => $this->getPage()->dictionarySearchFormData(),
            'page_name' => "字典配置",
        ]);
    }

    /**
     * 字典值新增
     * @return \think\response\Json|\think\response\View
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/6/24
     */
    public function dictionaryAdd()
    {
        if ($this->request->isAjax()) {
            $this->validate($data = data_filter($this->request->post()), DictionaryValidate::class . '.value_add');
            DictionaryModel::create([
                'pid' => $data['pid'],
                'dictionary_value'  => $data['dictionary_value'],
                'dictionary_name'   => $data['dictionary_name'] ?? $data['dictionary_value'],
                'status'            => $data['status'],
                'create_time'       => datetime(),
                'update_time'       => datetime(),
            ]);
            return ResponseJson::success();
        }

        return $this->fetch('common/save_page', [
            'form' => $this->getPage()->formData('value_add')
        ]);
    }

    /**
     * 字典值修改
     * @param int $id
     * @return \think\response\Json|\think\response\View
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function dictionaryEdit(int $id = 0)
    {
        if ($this->request->isAjax()) {
            $this->validate($data = data_filter($this->request->post()), DictionaryValidate::class . '.value_edit');
            DictionaryModel::update([
                'dictionary_value'  => $data['dictionary_value'],
                'dictionary_name'   => $data['dictionary_name'] ?? $data['dictionary_value'],
                'status'            => $data['status'],
                'update_time'       => datetime(),
            ], compact('id'));
            return ResponseJson::success();
        }

        $defaultValue = DictionaryModel::find($id)->getData();

        return $this->fetch('common/save_page', [
            'form' => $this->getPage()->formData('value_edit', $defaultValue)
        ]);
    }
}
