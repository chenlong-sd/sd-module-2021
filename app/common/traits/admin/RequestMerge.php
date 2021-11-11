<?php
/**
 *
 * RequestMerge.php
 * User: ChenLong <vip_chenlong@163.com>
 * DateTime: 2020/6/18 11:59
 */


namespace app\common\traits\admin;

use app\admin\AdminBaseService;
use app\common\BaseModel;
use app\common\BasePage;
use app\common\ResponseJson;
use app\common\SdException;
use app\common\service\BackstageListsService;
use think\response\Json;

/**
 * 请求合并，方便权限设置，如需拆开即可
 * Trait RequestMerge
 * @package app\common\controller\traits
 */
trait RequestMerge
{
    /**
     * 列表页数据
     * @param AdminBaseService $service 对应 service
     * @param BaseModel $model          对应 model
     * @param BasePage $page            对应 page
     * @return \think\response\Json|\think\response\View
     * @throws SdException
     * @throws \ReflectionException
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/11/5
     */
    protected function index_(AdminBaseService $service, BaseModel $model, BasePage $page)
    {
        if ($this->request->isAjax()) {
            $BackstageListsService = new BackstageListsService();

            return method_exists($service, 'listData')
                ? $service->listData($BackstageListsService)
                : $BackstageListsService->setModel($model)->getListsData();
        }

        return view($page->list_template, [
            'table'  => $page->listPageData(),
            'search' => $page->listSearchFormData()
        ]);
    }


    /**
     * 数据新增页面
     * 默认不可访问，需要访问，重载此方法，权限改为 public, 继承此代码即可
     * @param AdminBaseService $service 对应 service
     * @param BaseModel $model          对应 model
     * @param BasePage $page            对应 page
     * @param string $validateName      对应 验证器名字
     * @return Json|\think\response\View
     * @throws SdException
     * @throws \ReflectionException
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/11/8
     */
    protected function create_(AdminBaseService $service, BaseModel $model, BasePage $page, string $validateName)
    {
        if ($this->request->isPost()) {
            $data = data_filter($this->request->post());
            $this->validate($data, "$validateName.create");

            if (method_exists($service, 'dataCreate')) {
                $service->dataCreate($data);
            }

            $service->dataSave($data, $model);

            return ResponseJson::success();
        }

        return view($page->form_template, [
                'form' => $page->formPageData('create')
            ]);
    }

    /**
     * 数据更新
     * 默认不可访问，需要访问，重载此方法，权限改为 public, 继承此代码即可
     * @param AdminBaseService $service 对应 service
     * @param BaseModel $model 对应 model
     * @param BasePage $page 对应 page
     * @param string $validateName 对应 验证器名字
     * @return Json|\think\response\View
     * @throws SdException
     * @throws \ReflectionException
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/11/8
     */
    protected function update_(AdminBaseService $service, BaseModel $model, BasePage $page, string $validateName)
    {
        if ($this->request->isPost()) {
            $data = data_filter($this->request->post());
            $this->validate($data, "$validateName.update");

            if (method_exists($service, 'dataUpdate')) {
                $service->dataUpdate($data);
            }

            $service->dataSave($data, $model);

            return ResponseJson::success();
        }

        $data = $model->findOrEmpty($this->request->param('id', 0))->getData();
        return view($page->form_template, [
                'form' => $page->formPageData('update', $data)
            ]);
    }

    /**
     * 数据删除
     * 默认不可访问，需要访问，重载此方法，权限改为 public, 继承此代码即可
     * @param AdminBaseService $service 对应 service
     * @param BaseModel $model 对应 model
     * @return \think\response\Json
     * @throws SdException
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/11/5
     */
    protected function delete_(AdminBaseService $service, BaseModel $model): \think\response\Json
    {
        $ids = (array)$this->request->post('id');

        $service->delete($ids, $model);

        return ResponseJson::success();
    }

    /**
     * 开关操作数据处理,
     * 默认不可访问，需要访问，重载此方法，权限改为 public, 继承此代码即可
     * @param AdminBaseService $service 对应 service
     * @param BaseModel $model          对应 model
     * @return Json
     * @throws SdException
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/11/5
     */
    protected function switchHandle_(AdminBaseService $service, BaseModel $model): \think\response\Json
    {
        $data = $this->request->post();
        if (empty($data['id']) || empty($data['field']) || empty($data['handle_value'])) {
            throw new SdException('数据错误');
        }

        $service->switchValueUpdate($data['id'], $data['field'], $data['handle_value'], $model);

        return ResponseJson::success();
    }
}

