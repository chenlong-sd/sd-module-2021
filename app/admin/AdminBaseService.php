<?php
/**
 * datetime: 2021/11/5 17:25
 * user    : chenlong<vip_chenlong@163.com>
 **/

namespace app\admin;

use app\common\service\BackstageListsService;
use app\common\traits\admin\DataDelete;
use app\common\traits\admin\DataUpdate;
use think\response\Json;

/**
 * Class AdminBaseService
 * @method Json listData(BackstageListsService $service) 列表数据返回
 * @method void dataCreate(array $data) 自定义数据创建
 * @method void dataUpdate(array $data) 自定义数据更新
 * @package app\admin
 * @author chenlong<vip_chenlong@163.com>
 * @date 2021/11/5
 */
class AdminBaseService
{
    use DataUpdate, DataDelete;

}
