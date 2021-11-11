<?php
/**
 * Api.php
 * User: ChenLong
 * DateTime: 2020-12-11 11:09:22
 */

namespace app\admin\controller\system;

use app\admin\model\system\Api as ApiM;
use app\admin\model\system\ApiModule as ApiModuleModel;
use app\admin\model\system\QueryParams as QueryParamsModel;
use app\admin\page\system\ApiPage as ApiPage;
use app\admin\service\system\ApiService;
use app\admin\validate\system\Api as ApiValidate;
use \app\common\controller\Admin;
use app\common\ResponseJson;
use app\common\SdException;
use think\facade\Db;
use think\facade\View;


/**
 * Class Api
 * @package app\admin\controller\Api
 * @author chenlong <vip_chenlong@163.com>
 */
class Api extends Admin
{
    /**
     * @title('接口列表')
     * @param ApiService $service
     * @param ApiM $model
     * @param ApiPage $page
     * @return \think\response\Json|\think\response\View
     * @throws SdException
     * @throws \ReflectionException
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/11/9
     */
    public function index(ApiService $service, ApiM $model, ApiPage $page)
    {
        return parent::index_($service, $model, $page);
    }

    /**
     * @title('接口新增')
     * @param ApiService $service
     * @param ApiM $model
     * @param ApiPage $page
     * @return \think\response\Json|\think\response\View
     * @throws SdException
     * @throws \ReflectionException
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/11/9
     */
    public function create(ApiService $service, ApiM $model, ApiPage $page)
    {
        if (!$this->request->isAjax()) {
            $page->form_template = 'add';
            View::assign('data', ApiModuleModel::findOrEmpty(request()->get('api_module_id', 0))->toArray());
        }

        return parent::create_($service, $model, $page, ApiValidate::class);
    }

    /**
     * @title('接口更新')
     * @param ApiService $service
     * @param ApiM $model
     * @param ApiPage $page
     * @return \think\response\Json|\think\response\View
     * @throws SdException
     * @throws \ReflectionException
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/11/9
     */
    public function update(ApiService $service, ApiM $model, ApiPage $page)
    {
        if (!$this->request->isAjax()) {
            $page->form_template = 'add';
            [
                'apiData'       => $apiData,
                'apiModuleData' => $apiModuleData,
                'newParam'      => $newParam,
            ] = $service->updatePageData($this->request->get('id'));

            View::assign('data',  $apiModuleData);
            View::assign('api',   $apiData);
            View::assign('param', $newParam);
        }

        return parent::update_($service, $model, $page, ApiValidate::class);
    }

    /**
     * @title('保存api数据')
     * @param ApiService $service
     * @return \think\response\Json
     * @throws SdException
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/11/9
     */
    public function save(ApiService $service): \think\response\Json
    {
        $data = $this->request->post();
        // 验证数据
        $this->validate($body = data_filter($data['body']), ApiValidate::class . '.create');
        // 保存数据
        $service->apiSave($data['get'] ?? [], $data['post'] ?? [], $data['head'] ?? [], $body);

        return ResponseJson::success();
    }

    /**
     * @title('改接口为已对接')
     * @param ApiM $api
     * @param int $id
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function docking(ApiM $api, $id = 0): \think\response\Json
    {
        $api_info = $api->find($id);
        $api_info->status = 2;
        $api_info->save();

        return ResponseJson::success();
    }
}
