<?php
/**
 *
 */

namespace app\common\controller;


use app\BaseController;
use app\common\traits\admin\{AdminMiddleware,
    DataDelete,
    DataWrite,
    RequestMerge};
use app\common\BaseModel;
use app\common\BasePage;
use app\common\SdException;
use app\common\traits\Lang;
use think\facade\Db;
use think\Model;

/**
 * Class Admin
 * @method string|bool customAdd($data)     自定义添加
 * @method string|bool customEdit($data)    自定义修改
 * @method bool        delete($id)          自定义删除
 * @method array       edit()               数据更新页面
 * @package app\common\controller
 * @author  chenlong <vip_chenlong@163.com>
 * @version 1.0
 */
class Admin extends BaseController
{
    use DataWrite, AdminMiddleware,
        DataDelete, RequestMerge, Lang;

    /** @var string 带命名空间的模型名，默认为当前控制器对应的模型 */
    private string $model = '';

    /**
     * @var Model|Db
     */
    private $model_instance;

    /**
     * @var BasePage|null
     */
    private ?BasePage $page_instance = null;

    /**
     *  表主键
     * @var string
     */
    public string $primary;


    public function initialize()
    {
        $this->registerMiddleware();
        $this->setModel();
    }

    /**
     * 获取对应的模型
     * @return Db|Model|BaseModel
     * @throws SdException
     */
    final public function getModel()
    {
        if (!$this->model_instance) {
            if (class_exists($this->model)) {
                $this->model_instance = new $this->model;
            } else {
                throw new SdException('class not exist：' . $this->model);
            }
        }
        return $this->model_instance;
    }

    /**
     * 获取对应的page实例
     * @return BasePage
     */
    final public function getPage(): ?BasePage
    {
        if ($this->page_instance instanceof BasePage){
            return $this->page_instance;
        }
        $page_class = strtr(static::class, ['controller' => 'page']);
        return $this->page_instance = new $page_class();
    }

    /**
     * 获取对应DB类
     * @return \think\db\Query|Db
     */
    final public function getDb()
    {
        return Db::name($this->getTableName());
    }

    /**
     * 设置主键
     */
    private function setPrimary()
    {
        $this->primary = strtr(config('admin.primary_key'), ['{table}' => $this->getTableName()]);
    }

    /**
     * 获取当前控制器对应表名
     * @return string
     */
    private function getTableName(): string
    {
        return parse_name(substr(strrchr($this->model, '\\'), 1));
    }

    /**
     * 设置模型名字
     * @param string|null $model
     * @return $this
     */
    public function setModel(?string $model = null): Admin
    {
        if ($model === null) {
            $class       = strtr(static::class, ['controller' => 'model']);
            $this->model = class_exists($class) ? $class : strtr($class, ['admin' => 'common']);
        } else {
            $this->model = $model;
        }
        $this->setPrimary();
        return $this;
    }

    /**
     * 渲染页面
     * @param string $template 模板名称
     * @param array  $vars     模板变量
     * @return \think\response\View
     */
    public function fetch(string $template = '', array $vars = []): \think\response\View
    {
        $vars['primary'] = $this->primary;
        return view($template, $vars);
    }

    /**
     * @param $method
     * @param $vars
     * @return $this|array|\think\response\View
     */
    public function __call($method, $vars)
    {
        if (substr($method, 0, 3) === 'set') {
            $property = parse_name(substr($method, 3), 0, false);
            $this->$property = $vars[0] ?? $vars;
            return $this;
        }
        return $this->fetch($method, $vars);
    }
}

