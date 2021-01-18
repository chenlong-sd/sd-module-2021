<?php
/**
 *
 * RequestMerge.php
 * User: ChenLong <vip_chenlong@163.com>
 * DateTime: 2020/6/18 11:59
 */


namespace app\common\traits\admin;

use app\common\BasePage;
use app\common\service\BackstageListService;
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
     */
    public function index()
    {
        if ($this->request->isPost() || $this->request->isAjax()) {
            return method_exists($this, 'listData') ? $this->listData(new BackstageListService()) : $this->listsRequest();
        }
        return $this->lists();
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
     * @return mixed
     * @throws \ReflectionException
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

        $data = $this->getModel()::getDataById($this->request->param('id', 0))->getData();
        return $this->fetch($this->getPage()->form_template, [
                'form' => $this->getPage()->formData($page, $data)
            ]);
    }

}

