<?php
/**
 *
 * RequestMerge.php
 * User: ChenLong <vip_chenlong@163.com>
 * DateTime: 2020/6/18 11:59
 */


namespace app\common\traits\admin;

use app\common\BasePage;
use app\common\SdException;
use app\common\service\BackstageListService;
use app\common\service\BackstageListsService;
use sdModule\common\Sc;
use sdModule\layui\defaultForm\Form;
use think\Request;

/**
 * 请求合并，方便权限设置，如需拆开即可
 * @property-read Request  $request
 * @method  BasePage getPage()
 * Trait RequestMerge
 * @package app\common\controller\traits
 */
trait RequestMerge
{
    /**
     * 列表页数据
     * @return mixed
     * @throws SdException
     */
    public function index()
    {
        if ($this->request->isPost() || $this->request->isAjax()) {
            $service = new BackstageListsService();
            return method_exists($this, 'listData') ? $this->listData($service) : $service->setModel($this->getModel())->getListsData();
        }
        return $this->lists();
    }

    /**
     * @return array|\think\response\View
     * @throws SdException
     */
    private function lists()
    {
        if (empty($this->getPage()->listPageName())) {
            throw new SdException('please set the page title');
        }

        $assign = [
            'search'            => $this->getPage()->searchFormData(),
            'page_name'         => $this->getPage()->listPageName(),
            'quick_search_word' => $this->quickWord(),
            'table'             => $this->getPage()->getTablePageData()
        ];

        return $this->fetch($this->getPage()->list_template, $assign);
    }

    /**
     * 数据新增
     * @return mixed
     * @throws \ReflectionException
     */
    public function create()
    {
        if ($this->request->isPost()) {
            return $this->dataHandle();
        }

        if (method_exists($this, 'add')) {
            return Sc::reflex()->invoke($this, 'add');
        }

        return $this->fetch($this->getPage()->form_template, [
                'form' => $this->getPage()->formData('add')
            ]);
    }

    /**
     * 数据更新
     * @return array|mixed|\think\response\Json|\think\response\View
     * @throws \ReflectionException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function update()
    {
        $page = 'edit';
        if ($this->request->isPost()) {
            return $this->dataHandle($page);
        }

        if (method_exists($this, $page)) {
            return Sc::reflex()->invoke($this, $page, [$this->primary => $this->request->param('id', 0)]);
        }

        $data = $this->getModel()::find($this->request->param('id', 0))->getData();
        return $this->fetch($this->getPage()->form_template, [
                'form' => $this->getPage()->formData($page, $data)
            ]);
    }

}

