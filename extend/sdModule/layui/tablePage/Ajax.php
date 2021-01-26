<?php
/**
 * Date: 2021/1/26 15:22
 * User: chenlong <vip_chenlong@163.com>
 */

namespace sdModule\layui\tablePage;


class Ajax
{
    /**
     * @var array
     */
    private array $confirm = [];

    /**
     * @var string
     */
    private string $method = 'GET';

    /**
     * @var string
     */
    private string $url = '';

    /**
     * @var string
     */
    private string $data = '';

    private ?string $batch = null;


    public function __construct(string $url)
    {
        $this->url = $url;
    }

    /**
     * @param string $tip
     * @return Ajax
     */
    public function setTip(string $tip): Ajax
    {
        $this->confirm['tip'] = TableAux::pageTitleHandle($tip);
        return $this;
    }

    /**
     * @param string $isBatch
     * @return $this
     */
    public function setBatch(string $isBatch = 'id'): Ajax
    {
        $this->batch = $isBatch;
        return $this;
    }

    /**
     * @param array $config
     * @return $this
     */
    public function setConfig(array $config = []): Ajax
    {
        $this->confirm['config'] = array_merge($this->confirm['config'] ?? [], $config);
        return $this;
    }

    /**
     * @param string $data
     * @return Ajax
     */
    public function dataCode(string $data = '')
    {
        $this->data = $data;
        return $this;
    }

    /**
     * @return $this
     */
    public function noConfirm(): Ajax
    {
        $this->confirm = [];
        return $this;
    }

    /**
     * @param string $method
     * @return $this
     */
    public function method(string $method): Ajax
    {
        $this->method = $method;
        return $this;
    }


    public function __toString()
    {
        if ($this->confirm) {
            $config = json_encode($this->confirm['config'] ?? []);
            $code = <<<JS
        ScXHR.confirm('{$this->confirm['tip']}',{$config}).ajax({url:"{$this->url}",type:"{$this->method}",data:{$this->data},success(res){
                layer.close(window.load___);
                if (res.code === 200) {
                    layNotice.success('成功');
                    table.reload('sc');
                } else {
                    layNotice.warning(res.msg);
                } 
            }
        });
JS;
            return $this->batch ? sprintf(' function batch_js(id){%s} %s', $code, $this->batchData()) : $code;
        }

        $code = <<<JS
        let load = custom.loading();
        layui.jquery.ajax({url:"{$this->url}",type:"{$this->method}",data:{$this->data},success(res){
                layer.close(load);
                if (res.code === 200) {
                    layNotice.success('成功');
                    table.reload('sc');
                } else {
                    layNotice.warning(res.msg);
                } 
            }
        });
JS;
        return $this->batch ? sprintf(' function batch_js(id){%s} %s', $code, $this->batchData()) : $code;
    }


    private function batchData()
    {
        $please = lang('please select data');
        return  <<<JS
            let checkStatus = table.checkStatus('sc');
            if (checkStatus.data.length) {
                let id = [];
                for (let i in checkStatus.data) {
                    if (checkStatus.data.hasOwnProperty(i) && checkStatus.data[i].hasOwnProperty("{$this->batch}")) {
                        id.push(checkStatus.data[i].{$this->batch})
                    }
                }
                batch_js(id);
            }else{
                notice.warning('{$please}');
            }
JS;

    }
}

