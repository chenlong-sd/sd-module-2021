<?php
/**
 *
 * System.php
 * User: ChenLong
 * DateTime: 2020/4/28 16:45
 */


namespace app\admin\controller\system;

use app\admin\model\system\Resource;
use app\common\controller\Admin;
use app\common\ResponseJson;
use sdModule\makeAdminBasics\Basics;

/**
 * Class System
 * @package app\admin\controller\system
 * @author chenlong <vip_chenlong@163.com>
 */
class System extends Admin
{
    /**
     * @return bool|int|string|\think\response\Json
     * @throws \ReflectionException
     */
    public function aux()
    {
        return Basics::auxCall();
    }

    /**
     * @param Resource $resource
     * @param int $page
     * @param int $limit
     * @return \think\response\Json|\think\response\View
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function resource(Resource $resource, $page = 1, $limit = 10)
    {
        if ($this->request->isAjax()) {
            return ResponseJson::mixin($resource::addSoftDelWhere()->page($page, $limit)->field('path,id,tag,type')->select());
        }

        return view('', [
            'count' => $resource::addSoftDelWhere()->count(),

        ]);
    }

}
