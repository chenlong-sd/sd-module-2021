<?php
/**
 * Date: 2021/1/26 15:22
 * User: chenlong <vip_chenlong@163.com>
 */

namespace sdModule\layui\tablePage\module;


class Ajax
{
    /**
     * 确认框配置
     * @var array
     */
    private $confirm = [];

    /**
     * 请求方式
     * @var string
     */
    private $method = 'GET';

    /**
     * 请求路径
     * @var string
     */
    private $url = '';

    /**
     * 请求数据的代码
     * @var string
     */
    private $data = '';
    /**
     * 是否是批量数据请求处理
     * @var null
     */
    private $batch = null;

    /**
     * 成功后执行的代码
     * @var string
     */
    private $successCallback = null;

    /**
     * 失败后执行的代码
     * @var string
     */
    private $failCallback = "";

    /**
     * 提示输入层
     * @var array
     */
    private $prompt = [];

    /**
     * 权限字符 normal false， 用于判断该操作是否有权限可以操作
     * @var string
     */
    private $power = 'normal';

    /**
     * Ajax constructor.
     * @param string $url
     * @throws \app\common\SdException
     */
    public function __construct(string $url)
    {
        $this->url = $url;
        $this->power = access_control($url) ? 'normal' : 'false';
    }

    /**
     * 设置提示语
     * @param string $tip
     * @return Ajax
     */
    public function setTip(string $tip): Ajax
    {
        $this->confirm['tip'] = TableAux::pageTitleHandle($tip);
        return $this;
    }

    /**
     * 设置批量处理的字段名
     * @param string $isBatch
     * @return $this
     */
    public function setBatch(string $isBatch = 'id'): Ajax
    {
        $this->batch = $isBatch;
        return $this;
    }

    /**
     * 设置弹出层的配置
     * @param array $config
     * @return $this
     */
    public function setConfig(array $config = []): Ajax
    {
        $this->confirm['config'] = array_merge($this->confirm['config'] ?? [], $config);
        return $this;
    }

    /**
     * 设置传输的data js 代码
     * @param string $data
     * @return Ajax
     */
    public function dataCode(string $data = ''): Ajax
    {
        $this->data = $data;
        return $this;
    }

    /**
     * 设置没有提示层
     * @return $this
     */
    public function noConfirm(): Ajax
    {
        $this->confirm = [];
        return $this;
    }

    /**
     * 请求方式
     * @param string $method
     * @return $this
     */
    public function method(string $method): Ajax
    {
        $this->method = $method;
        return $this;
    }


    /**
     * 输入层弹窗
     * @param string $message
     * @param array $config {@link https://www.layui.com/doc/modules/layer.html#layer.prompt}
     * @return $this
     */
    public function prompt(string $message = '', array $config = []): Ajax
    {
        $this->prompt = [
            'title'    => $message,
            'area'     => ['400px', '200px'],
            'formType' => 2,
        ];

        $this->prompt = array_merge($this->prompt, $config);

        return $this;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        if ($this->power === 'false') {
            return $this->power;
        }

        $successExecute = $this->successCallback === null ? "table.reload('sc');" : $this->successCallback;
        $data = $this->data ?: "{}";
        if ($this->confirm) {
            $config = json_encode($this->confirm['config'] ?? []);
            $code = <<<JS
        let sc_data = {$data};
        if (typeof value !== 'undefined')sc_data.prompt_value = value;
        ScXHR.confirm('{$this->confirm['tip']}',{$config}).ajax({url:"{$this->url}",type:"{$this->method}",data:sc_data,success(res){
                layer.close(window.load___);
                if (res.code === 200) {
                    layNotice.success('成功');
                    {$successExecute}
                } else {
                    layNotice.warning(res.msg);
                    {$this->failCallback}
                } 
            }
        });
JS;
            $code = sprintf($this->promptCodeCheck(), $code);
            return $this->batch ? sprintf(' function batch_js(id){%s} %s', $code, $this->batchData()) : $code;
        }

        $code = <<<JS
        let sc_data = {$data};
        if (typeof value !== 'undefined')sc_data.prompt_value = value;
        let load = custom.loading();
        layui.jquery.ajax({url:"{$this->url}",type:"{$this->method}",data:sc_data,success(res){
                layer.close(load);
                if (res.code === 200) {
                    layNotice.success('成功');
                     {$successExecute}
                } else {
                    layNotice.warning(res.msg);
                    {$this->failCallback}
                } 
            }
        });
JS;
        $code = sprintf($this->promptCodeCheck(), $code);
        return $this->batch ? sprintf(' function batch_js(id){%s} %s', $code, $this->batchData()) : $code;
    }

    /**
     * 批量操作数据
     * @return string
     * @author chenlong <vip_chenlong@163.com>
     * @date 2021/6/7
     */
    private function batchData(): string
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

    /**
     * 弹出输入框检查
     * @return string
     * @author chenlong <vip_chenlong@163.com>
     * @date 2021/6/7
     */
    private function promptCodeCheck(): string
    {
        if (empty($this->prompt)) {
            return "%s";
        }
        $config = json_encode($this->prompt, JSON_UNESCAPED_UNICODE);
        return <<<JS
    layer.prompt({$config}, function(value, index, elem){
        if(!value){
            layer.alert('输入值不能为空');
            return false;
        }
        %s
        layer.close(index);
    });
JS;

    }

    /**
     * @param string $successCallback
     * @return Ajax
     */
    public function successCallback(string $successCallback): Ajax
    {
        $this->successCallback = $successCallback;
        return $this;
    }

    /**
     * @param string $failCallback
     * @return Ajax
     */
    public function setFailCallback(string $failCallback): Ajax
    {
        $this->failCallback = $failCallback;
        return $this;
    }
}

