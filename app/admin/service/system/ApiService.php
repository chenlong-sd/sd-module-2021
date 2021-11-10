<?php
/**
 * datetime: 2021/11/9 11:57
 * user    : chenlong<vip_chenlong@163.com>
 **/

namespace app\admin\service\system;

use app\admin\AdminBaseService;
use app\admin\enum\ApiEnumParamType;
use app\admin\model\system\Api as ApiM;
use app\admin\model\system\ApiModule as ApiModuleModel;
use app\admin\model\system\QueryParams as QueryParamsModel;
use app\common\SdException;
use app\common\service\BackstageListsService;
use think\facade\Db;

class ApiService extends AdminBaseService
{
    /**
     * 列表数据
     * @param BackstageListsService $service
     * @return \think\response\Json
     * @throws \app\common\SdException
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/11/9
     */
    public function listData(BackstageListsService $service): \think\response\Json
    {
        $model = ApiM::field('i.id,i.api_name,i.path,i.describe,i.update_time,i.method,i.status,i.status status_1')
            ->order('status', 'asc')
            ->order('update_time', 'desc');
        return $service->setModel($model)->setPagination(false)->getListsData();
    }

    /**
     * 更新接口的页面数据
     * @param int $api_id
     * @return array
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/11/9
     */
    public function updatePageData(int $api_id): array
    {
        try {
            $apiData       = ApiM::findOrEmpty($api_id);
            $apiModuleData = ApiModuleModel::findOrEmpty($apiData->api_module_id)->getData();
            $apiParam      = QueryParamsModel::where(['api_id' => $api_id])->select();
            $newParam = [];
            foreach ($apiParam as $item) {
                $newParam[$item->getData('method')][] = $item;
            }
            $apiData = $apiData->getData();
        } catch (\Throwable $exception) {
            $apiData = $apiModuleData = $newParam = [];
        }

        return compact('apiData', 'apiModuleData', 'newParam');
    }

    /**
     * api模块的列表数据
     * @param BackstageListsService $service
     * @return \think\response\Json
     * @throws SdException
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/11/9
     */
    public function apiModuleListData(BackstageListsService $service): \think\response\Json
    {
        $model = ApiModuleModel::field('i.id,i.item_name,url_prefix,i.update_time,count(a.id) api_number')
            ->join('api a', 'a.api_module_id = i.id', 'left')
            ->group('i.id')
            ->with('api');
        return $service->setModel($model)->setEach(function ($v) {
            $v->url_prefix = implode(', ', explode('|-|', $v->url_prefix));
        })->getListsData();
    }

    /**
     * 保存api
     * @param array $get    get参数
     * @param array $post   post参数
     * @param array $header header参数
     * @param array $body   api主要数据
     * @throws SdException
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/11/9
     */
    public function apiSave(array $get, array $post, array $header, array $body)
    {
        Db::startTrans();
        try {
            $body['status'] = 1;
            $body['response']  = $body['response'] ?? '';
            if (empty($body['id'])) {
                $api = ApiM::create($body);
                if (!$api->id) throw new SdException('保存失败！');
            }else{
                $api = ApiM::findOrEmpty($body['id']);
                $api->save($body);
            }
            QueryParamsModel::update(['delete_time' => time()], ['api_id' => $api->id]);

            $param = array_merge(
                $this->paramDataMake($get,    $api->id, ApiEnumParamType::create(ApiEnumParamType::GET)),
                $this->paramDataMake($post,   $api->id, ApiEnumParamType::create(ApiEnumParamType::POST)),
                $this->paramDataMake($header, $api->id, ApiEnumParamType::create(ApiEnumParamType::HEADER))
            );

            QueryParamsModel::insertAll($param);

            Db::commit();
        } catch (\Exception $exception) {
            Db::rollback();
            throw new SdException('保存失败！' . $exception->getMessage() . $exception->getLine());
        }
    }

    /**
     * 参数数据创建
     * @param array $data  参数数据
     * @param int $api_id  API 接口的id
     * @param ApiEnumParamType $method 请求参数类型
     * @return array
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/11/9
     */
    private function paramDataMake(array $data, int $api_id, ApiEnumParamType $method): array
    {
        foreach ($data as &$item) {
            $item['api_id'] = $api_id;
            $item['method'] = (string)$method;
            $item['create_time'] = datetime();
            $item['update_time'] = datetime();
        }
        return $data;
    }
}
