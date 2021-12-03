<?php
/**
 *
 * DateDelete.php
 * User: ChenLong
 * DateTime: 2020/4/13 13:50
 */


namespace app\common\traits\admin;

use app\common\BaseModel;
use app\common\SdException;
use think\exception\HttpResponseException;

/**
 * 数据删除
 * Trait DateDelete
 * @package app\common\controller\traits
 * @author  chenlong <vip_chenlong@163.com>
 */
trait DataDelete
{

    /**
     * 数据删除
     * @param array $ids        要删除的主键集合
     * @param BaseModel $model  对应 model
     * @throws SdException
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/11/8
     */
    public function delete(array $ids, BaseModel $model)
    {
        $model->startTrans();
        try {
            // 删除数据之前的处理
            $this->beforeDelete($ids);

            // 更改软删除字段为当前时间戳，TP 的模型软删除为先查询后循环删除，不采用
            $model->where(['id' => $ids])->update(['delete_time' => time()]);

            // 删除数据之后的处理
            $this->afterDelete($ids);

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
     * 删除之前的数据处理
     * @param $ids
     */
    protected function beforeDelete(&$ids){}

    /**
     * 删除后的代码执行
     * @param $ids
     */
    protected function afterDelete($ids){}
}

