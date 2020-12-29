<?php
/**
 * 
 * Power.php
 * User: ChenLong
 * DateTime: 2020-04-03 15:28
 */

namespace app\admin\model\system;

use app\common\BaseModel;
use app\common\SdException;
use think\Model;

/**
 * Class Power
 * @package app\admin\model\system
 * @author chenlong <vip_chenlong@163.com>
 */
class Power extends Model
{
    use BaseModel;

    protected $schema = [
        'id' => 'int',
        'route_id' => 'int',
        'role_id' => 'int',
        'create_time' => 'datetime',
        'update_time' => 'datetime',
        'delete_time' => 'int',
    ];

    /**
     * 设置权限
     * @param array $power_route 设置的权限的包含路由的数组
     * @param int   $role_id    角色ID
     * @return bool
     * @throws SdException
     */
    public function setPower($power_route, $role_id)
    {
        $power_data = [];
        foreach ($power_route as $item) {
            $power_data[] = [
                'route_id' => $item['id'],
                'role_id' => $role_id,
                'create_time' => date('Y-m-d H:i:s'),
                'update_time' => date('Y-m-d H:i:s')
            ];
        }

        $this->startTrans();
        try {
            $this->where('role_id', $role_id)->update([
                'delete_time' => time()
            ]);

            $this->insertAll($power_data);

            $this->commit();
        } catch (\Exception $exception) {
            $this->rollback();
            throw new SdException($exception->getMessage());
        }

        return true;
    }
   
}