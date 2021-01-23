<?php
/**
 *
 * DateDelete.php
 * User: ChenLong
 * DateTime: 2020/4/13 13:50
 */


namespace app\common\traits\admin;

use app\common\BaseModel;
use app\common\ResponseJson;
use app\common\SdException;
use think\db\Query;
use think\facade\Db;
use think\Model;

/**
 * 数据删除
 * @method Query|Model|BaseModel getModel
 * Trait DateDelete
 * @package app\common\controller\traits
 * @author  chenlong <vip_chenlong@163.com>
 */
trait DataDelete
{

    /**
     * 数据删除
     * @param array $id
     * @return \think\response\Json
     * @throws \Throwable
     */
    public function del($id = [])
    {
        Db::startTrans();
        try {
            $this->beforeDelete($id);
            if (method_exists(static::class, 'delete')) {
                $this->delete($id);
            } else {
                $this->getModel()->destroy((array)$id);
            }
            $this->afterDelete($id);
            Db::commit();
        } catch (\Throwable $exception) {
            Db::rollback();
            throw $exception;
        }

        return ResponseJson::mixin(true);
    }

    /**
     * 删除之前的数据处理
     * @param $id
     */
    public function beforeDelete(&$id){}

    /**
     * 删除后的代码执行
     * @param $id
     */
    public function afterDelete($id){}
}

