<?php
/**
 * datetime: 2021/11/10 15:10
 * user    : chenlong<vip_chenlong@163.com>
 **/

namespace sdModule\makeBaseCURD\item;

use app\common\SdException;
use sdModule\makeBaseCURD\CURD;

abstract class Item implements ItemI
{
    /**
     * @var CURD
     */
    protected $CURD;
    /**
     * @var array 替换值
     */
    protected $replace;

    /**
     * 添加 use 代码块
     * @param string $useClass
     */
    protected function useAdd(string $useClass)
    {
        if (empty($this->replace['use'])) {
            $this->replace['use'] = [];
        }
        if ($useClass && !in_array("use $useClass;", $this->replace['use'])) {
            $this->replace['use'][] = "use $useClass;";
        }
    }

    /**
     * 引入控制器
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/11/10
     */
    protected function useController()
    {
        $this->useAdd($this->getMakeClassName('controller'));
    }

    protected function useModel()
    {
        $this->getMakeClassName('model')
            ? $this->useAdd($this->getMakeClassName('model'))
            : $this->useAdd($this->getMakeClassName('common_model'));
    }

    protected function usePage()
    {
        $this->useAdd($this->getMakeClassName('page'));
    }

    protected function useService()
    {
        $this->useAdd($this->getMakeClassName('service'));
    }

    protected function useValidate()
    {
        $this->useAdd($this->getMakeClassName('validate'));
    }

    /**
     * 替换字符串处理
     * @return array
     */
    protected function replaceHandle(): array
    {
        $replace = [];
        foreach ($this->replace as $key => $value) {
            $replace["//=={{$key}}==//"] = is_array($value)
                ? implode($key  === "use" ? "\r\n" : $this->CURD->indentation(3), $value)
                : $value;
        }
        return $replace;
    }

    /**
     * @param string $tag
     * @return string
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/11/10
     */
    private function getMakeClassName(string $tag): string
    {
        $this->useAdd(SdException::class);
        $moduleMap = $this->getModuleMap();
        $className = $this->CURD->getNamespace($this->CURD->config("namespace.$tag")) . '\\' . parse_name($this->CURD->table, 1);

        //  不创建admin模块model同时对应的admin模块的model类也不存在的时候
        if ($tag === 'model' && !class_exists($className) && !$this->CURD->isMakeAdminModel) {
            return '';
        }

        if (class_exists($className) || ($moduleMap[$tag] & $this->CURD->makeModule) > 0){
            $tag = $tag == 'common_model' ? 'model' : $tag;
            if ($tag === 'service') $className .= 'Service';
            if ($tag === 'page') $className .= 'Page';

            return $className . ' as My' . ucfirst($tag);
        }
        return '';
    }

    /**
     * 获取创建的模块值映射
     * @return int[]
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/11/10
     */
    private function getModuleMap(): array
    {
        return [
            'controller'   => 1,
            'model'        => 2,
            'common_model' => 2,
            'validate'     => 4,
            'page'         => 8,
            'service'      => 16,
        ];
    }
}
