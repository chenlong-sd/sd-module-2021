/**
 * //=={Table}==//.php
 * User: ChenLong
 * DateTime: //=={date}==//
 */

namespace //=={namespace}==//;

use app\common\controller\Admin;
use app\common\service\BackstageListsService;
//=={use}==//

/**
 * //=={describe}==// 控制器
 * Class //=={Table}==//
 * @package //=={namespace}==//\//=={Table}==//
 * @author chenlong <vip_chenlong@163.com>
 */
class //=={Table}==// extends Admin
{
    /**
     * 列表数据接口
     * @param BackstageListsService $service
     * @return false|mixed|\think\response\Json
     * @throws \app\common\SdException
     */
    public function listData(BackstageListsService $service)
    {
        $mode = $this->getModel()//=={list_join}==//
            ->field('//=={list_field}==//');

        return $service->setModel($mode)->getListsData();
    }

}
