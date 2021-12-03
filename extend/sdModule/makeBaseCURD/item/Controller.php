<?php


namespace sdModule\makeBaseCURD\item;


use sdModule\makeBaseCURD\CURD;

class Controller extends Item
{
    /**
     * 模块文件创建
     * Controller constructor.
     * @param CURD $CURD
     */
    public function __construct(CURD $CURD)
    {
        $this->CURD       = $CURD;
        $this->replace    = [
            'table_name'   => $this->CURD->table,
            'page_name'    => $this->CURD->pageName ?: $this->CURD->tableComment,
            'Table'        => parse_name($this->CURD->table, 1),
            'date'         => date('Y-m-d H:i:s'),
            'use'          => [],
            'namespace'    => $this->CURD->getNamespace($this->CURD->config('namespace.controller')),
            'describe'     => $this->CURD->pageName ?: $this->CURD->tableComment
        ];

        $this->methodCode();
    }

    /**
     * @return mixed|void
     */
    public function make()
    {
        $file_content = file_get_contents($this->CURD->config('template.controller'));

        return "<?php\r\n" . strtr($file_content, $this->replaceHandle());
    }

    /**
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/11/10
     */
    private function methodCode()
    {
        foreach ($this->CURD->accessible as $methodIndex) {
            switch ($methodIndex) {
                case '1':
                    $this->replace['method'][] = $this->indexMethod();
                    break;
                case '2':
                    $this->replace['method'][] = $this->createMethod();
                    break;
                case '3':
                    $this->replace['method'][] = $this->updateMethod();
                    break;
                case '4':
                    $this->replace['method'][] = $this->deleteMethod();
                    break;
                case '5':
                    $this->replace['method'][] = $this->switchMethod();
                    break;
            }
        }
    }

    /**
     * @return string
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/12/3
     */
    private function indexMethod(): string
    {
        $this->useService();
        $this->useModel();
        $this->usePage();

        return <<<CODE

    /**
     * @title("{$this->replace['describe']}列表")
     * @param MyService \$service
     * @param MyModel \$model
     * @param MyPage \$page
     * @return \\think\\response\\Json|\\think\\response\\View
     * @throws SdException
     * @throws \\ReflectionException
     */
    public function index(MyService \$service, MyModel \$model, MyPage \$page)
    {
        return parent::index_(\$service, \$model, \$page);
    }
    
CODE;
    }

    /**
     * @return string
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/12/3
     */
    private function createMethod(): string
    {
        $this->useService();
        $this->useModel();
        $this->usePage();
        $this->useValidate();

        return <<<CODE

    /**
     * @title("新增{$this->replace['describe']}")
     * @param MyService \$service
     * @param MyModel \$model
     * @param MyPage \$page
     * @return \\think\\response\\Json|\\think\\response\\View
     * @throws SdException
     * @throws \\ReflectionException
     */
    public function create(MyService \$service, MyModel \$model, MyPage \$page)
    {
        return parent::create_(\$service, \$model, \$page, MyValidate::class);
    }

CODE;
    }


    /**
     * @return string
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/12/3
     */
    private function updateMethod(): string
    {
        $this->useService();
        $this->useModel();
        $this->usePage();
        $this->useValidate();

        return <<<CODE

    /**
     * @title("更新{$this->replace['describe']}")
     * @param MyService \$service
     * @param MyModel \$model
     * @param MyPage \$page
     * @return \\think\\response\\Json|\\think\\response\\View
     * @throws SdException
     * @throws \\ReflectionException
     */
    public function update(MyService \$service, MyModel \$model, MyPage \$page)
    {
        return parent::update_(\$service, \$model, \$page, MyValidate::class);
    }

CODE;
    }

    /**
     * 删除方法
     * @return string
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/11/10
     */
    private function deleteMethod(): string
    {
        $this->useService();
        $this->useModel();

        return <<<CODE

    /**
     * @title("删除{$this->replace['describe']}")
     * @param MyService \$service
     * @param MyModel \$model
     * @return \\think\\response\\Json
     * @throws SdException
     */
    public function delete(MyService \$service, MyModel \$model): \\think\\response\\Json
    {
        return parent::delete_(\$service, \$model);
    }
CODE;

    }

    private function switchMethod()
    {
        $this->useService();
        $this->useModel();

        return <<<CODE

    /**
     * @title("{$this->replace['describe']}状态更新")
     * @param MyService \$service
     * @param MyModel \$model
     * @return \\think\\response\\Json
     * @throws SdException
     */
    public function switchHandle(MyService \$service, MyModel \$model): \\think\\response\\Json
    {
        return parent::switchHandle_(\$service, \$model);
    }

CODE;

    }


}

