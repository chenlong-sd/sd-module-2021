<?php


namespace app\common\traits\admin;


use app\common\ResponseJson;
use think\db\Query;
use think\facade\Db;
use think\Request;

/**
 * 数据写入
 * @property-read Request $request
 * Trait DataWrite
 * @package app\common\controller
 * @author chenlong <vip_chenlong@163.com>
 */
trait DataWrite
{
    /**
     * @var bool|array 写入数据时验证是否，值为布尔或包含要验证的场景值数组 ['add', 'edit']
     */
    public $validate = true;

    /**
     * 数据操作
     * @param string $type 类型，add | edit
     * @return \think\response\Json
     * @throws \Throwable
     */
    private function dataHandle(string $type = 'add')
    {
        $data = $this->dataBeforeHandle($this->verify($type));

        if (method_exists($this, 'custom' . ucfirst($type))) {
            $method = 'custom' . ucfirst($type);
            $result = call_user_func([$this, $method], $data);
        } else {
            if (method_exists($this->getModel(), $method = $type . 'Handle')) {
                $result = call_user_func([$this->getModel(), $method], $data);
            }else{
                $result = $this->directWrite($data, $type);
            }
        }

        return ResponseJson::mixin($result);
    }

    /**
     * 直接写入数据
     * @param array  $data
     * @param string $type
     * @return bool|mixed|string
     * @throws \Throwable
     */
    private function directWrite(array $data, string $type)
    {
        Db::startTrans();
        try {
            if ($type === 'add'){
                $write_model = $this->getModel();
                $data = data_only($data, array_keys(get_class_attr($write_model, 'schema', [])));
                $id   = $write_model->insertGetId($data);
                $this->afterAdd($id, $data);
            }else{
                $write_model = $this->getModel()->find($data[$this->primary]);
                $data = data_only($data, array_keys(get_class_attr($write_model, 'schema', [])));
                $write_model->save($data);
                $primary = $this->primary;
                $id      = $write_model->$primary;
                $this->afterUpdate($id, $data);
            }

            $this->afterWrite($id, $data);
            Db::commit();
        } catch (\Throwable $exception) {
            Db::rollback();
            throw $exception;
        }

        return true;
    }

    /**
     * 验证
     * @param string $type
     * @return mixed
     */
    protected function verify(string $type)
    {
        $data = $this->filter($this->request->post());

        if ($this->validate === true || (is_array($this->validate) && in_array($type, $this->validate))) {
            $validate = strtr(static::class, ['controller' => 'validate']);

            if (!class_exists($validate)) {
                $validate = strtr($validate, ['admin' => 'common']);
            }

            $this->validate($data, $validate . '.' . $type);
        }

        return $data;
    }

    /**
     * 数据写入之前处理一些数据
     * @param $data
     */
    protected function beforeWrite(&$data){}

    protected function afterWrite($id, $data){}

    protected function afterAdd($id, $data){}

    protected function afterUpdate($id, $data){}

    /**
     * 数据写入之前的处理
     * @param $data
     * @return mixed
     */
    private function dataBeforeHandle($data)
    {
        $this->beforeWrite($data);

        $value = [
            'datetime'  => date('Y-m-d H:i:s'),
            'timestamp' => time()
        ];

        $time_info = config('admin.time_field');

        $data[$time_info['update_time']['field']] = $value[$time_info['update_time']['type']];
        if (empty($data[$this->primary])) {
            $data[$time_info['create_time']['field']] = $value[$time_info['create_time']['type']];
        }

        return $data;
    }
}

