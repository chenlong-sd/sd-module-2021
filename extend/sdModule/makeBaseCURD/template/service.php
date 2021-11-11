/**
* //=={Table}==//.php
* DateTime: //=={date}==//
*/

namespace //=={namespace}==//;

use app\admin\AdminBaseService;
use app\common\service\BackstageListsService;
//=={use}==//

/**
* //=={describe}==// 服务层
* Class //=={Table}==//Service
* @package //=={namespace}==//\//=={Table}==//Service
*/
class //=={Table}==//Service extends AdminBaseService
{
    /**
     * 列表数据
     * @param BackstageListsService $service
     * @return \think\response\Json
     * @throws \app\common\SdException
     */
    public function listData(BackstageListsService $service): \think\response\Json
    {
        $model = MyModel::field('//=={list_field}==//')
            //=={list_join}==//;

        return $service->setModel($model)->getListsData();
    }

}
