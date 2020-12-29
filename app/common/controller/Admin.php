<?php
/**
 *
 */

namespace app\common\controller;


use app\BaseController;
use app\common\traits\admin\{AdminMiddleware,
    DataDelete,
    ListRequest,
    DataWrite,
    RequestMerge,
    CallNotExistMethod};
use app\common\BaseModel;
use app\common\BasePage;
use app\common\SdException;
use app\common\traits\Lang;
use sdModule\common\Sc;
use sdModule\layuiSearch\Form;
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
    use ListRequest, DataWrite, AdminMiddleware,
        DataDelete, RequestMerge, CallNotExistMethod, Lang;

    /** @var string 带命名空间的模型名，默认为当前控制器对应的模型 */
    private string $model = '';

    /**
     * @var Model|Db
     */
    private $model_instance;

   /**
     * @var BasePage
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
     * @return array|\think\response\View
     * @throws SdException|\Exception
     */
    public function lists()
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
    final public function getPage()
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
    private function getTableName()
    {
        return parse_name(substr(strrchr($this->model, '\\'), 1));
    }

    /**
     * 设置模型名字
     * @param string|null $model
     * @return $this
     */
    public function setModel(string $model = null)
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
     * @return array|\think\response\View
     */
    public function fetch($template = '', $vars = [])
    {
        $vars['primary'] = $this->primary;
        return view($template, $vars);
    }


    /**
     * 数据过滤
     * @param      $data
     * @param bool $all 是否全部过滤,二维数组
     * @return array
     */
    final public function filter($data, $all = true)
    {
        if ($all !== true) {
            return array_filter($data, [$this, 'filterCallback']);
        }

        foreach ($data as $key => $value) {
            $value = is_array($value) ? $this->filter($value) : $value;
            if (!$this->filterCallback($value)) {
                unset($data[$key]);
            }
        }
        return $data;
    }

    /**
     * 过滤数据的回调函数
     * @param $value
     * @return bool
     */
    private function filterCallback($value)
    {
        $value = is_string($value) ? trim($value) : $value;
        $value = is_numeric($value) && !$value ? 0 : $value;
        return is_bool($value) || $value === 0 || $value;
    }

    /**
     * 快捷搜索的文本提示
     * @return string
     */
    private function quickWord()
    {
        if (!$word = $this->getPage()->setQuickSearchField()) {
            return '';
        }

        $word = array_values($word);

        return $word[0] . (empty($word[1]) ? '' : lang('or') . $word[1]);
    }

}

