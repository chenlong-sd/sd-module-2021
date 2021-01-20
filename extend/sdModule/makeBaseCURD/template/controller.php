/**
 * //=={Table}==//.php
 * User: ChenLong
 * DateTime: //=={date}==//
 */

namespace //=={namespace}==//;

use \app\common\controller\Admin;
//=={use}==//

/**
 * Class //=={Table}==//
 * @package //=={namespace}==//\//=={Table}==//
 * @author chenlong <vip_chenlong@163.com>
 */
class //=={Table}==// extends Admin
{
    /**
     * 列表数据接口
     * @return mixed|string|\think\Collection|\think\response\Json
     * @throws \app\common\SdException
     */
    public function listData()
    {
        return $this//=={list_join}==//
            ->setField('//=={list_field}==//')
            ->listsRequest();
    }

}
