<?php


namespace app\common\traits\admin;


use app\common\BaseModel;
use app\common\SdException;
use think\exception\HttpResponseException;
use think\facade\Log;

/**
 * 数据写入/更新
 * Trait DataWrite
 * @package app\common\controller
 * @author chenlong <vip_chenlong@163.com>
 */
trait DataUpdate
{
    /**
     * 数据保存操作， 根据数据里面是否含有主键值判断是更新还是创建
     * @param array     $data  数据
     * @param BaseModel $model 对应 model
     * @throws SdException
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/11/8
     */
    public function dataSave(array $data, BaseModel $model)
    {
        $model->startTrans();
        try {
            $this->beforeWrite($data);
            $save_type = 'create';

            if (isset($data[$model->getPk()])) {
                $model = $model->find($data[$model->getPk()]);
                unset($data[$model->getPk()]);
                $save_type = 'update';
            }
            $model->save($data);

            $this->afterWrite($save_type, array_merge($data, [$model->getPk() => $model[$model->getPk()]]));
            $model->commit();
        } catch (\Throwable $exception) {
            $model->rollback();
            if ($exception instanceof HttpResponseException) {
                throw $exception;
            }
            throw new SdException($exception->getMessage());
        }
    }

    /**
     * 数据写入之前处理一些数据
     * @param array $data
     */
    protected function beforeWrite(array &$data){}

    /**
     * 数据写入之后的保存
     * @param string $save_type 保存类型
     * @param array $data       传入的数据
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/11/5
     */
    protected function afterWrite(string $save_type, array $data){}

    /**
     * 更新开关操作的状态值
     * @param int $id 要更新的数据ID
     * @param string $field 要更新的字段
     * @param string|int $handle_value 更新后的状态值
     * @param BaseModel $model
     * @throws SdException
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/11/8
     */
    public function switchValueUpdate(int $id, string $field, $handle_value, BaseModel $model)
    {
        try {
            $model = $model->find($id);
            if (!$model) {
                throw new SdException('数据不存在');
            }

            $model->$field = $handle_value;
            $this->switchValueUpdateCustomize($model);

            $model->save();
        } catch (\Throwable $exception) {
            Log::write($exception->getMessage(), 'error');
            throw new SdException("非法操作，请求数据不完整");
        }
    }

    /**
     * 自定义处理选项切换的数据
     * @param BaseModel $model
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/12/3
     */
    protected function switchValueUpdateCustomize($model){}
}

