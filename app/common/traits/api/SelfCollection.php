<?php
/**
 * Date: 2020/11/6 16:38
 * User: chenlong <vip_chenlong@163.com>
 */

namespace app\common\traits\api;


use app\common\ResponseJson;
use app\common\SdException;
use think\facade\Db;
use think\facade\Log;
use think\Request;

/**
 * Trait SelfCollection
 * @property-read Request $request
 * @package app\common\traits\api
 */
trait SelfCollection
{
    /**
     * @var array|string[]
     */
    public $exclude_field = [
        'create_time',
        'update_time',
        'delete_time'
    ];

    /**
     * @return array
     */
    abstract protected function provideTableInfo():array ;

    /**
     * 获取表
     * @return \think\response\Json
     * @throws SdException
     */
    public function getTable()
    {
        $this->block();
        return ResponseJson::success([
            'info' => $this->provideTableInfo(),
            'field_link' => '{{base_url}}/sc-field-info?table={{table}}',
            'message' => '[info] 里面包含可取的table字段信息，键为table，值为描述；[field_link] 为获取字段信息的链接地址，{{base_url}} 为根地址，{{table}} 为你要获取的表字段信息的表名'
        ]);
    }

    /**
     * 获取字段
     * @param string $table
     * @return \think\response\Json
     * @throws SdException
     */
    public function getFieldInfo(string $table = '')
    {
        $this->block();
        if (empty($table)  || !in_array($table, array_keys($this->provideTableInfo())) || !($field_info = Db::name($table)->getFields())) {
            return ResponseJson::fail( '缺少query参数table,或table参数不支持此接口');
        }

        $field_info = array_filter($field_info, function ($v) {
            return !in_array($v['name'], $this->exclude_field);
        });

        return ResponseJson::success([
            'data_link' => "{{base_url}}/sc-data?table={$table}&field={{field}}&id={{id}}",
            'field_info' => array_map(function ($v) {
                return $v['comment'];
            }, $field_info),
            'message' => '[field_info] 包含字段信息及描述， [data_link] 包含获取数据的链接地址， {{base_url}} 为根地址，'
                .'{{field}}为字段(多个以逗号分割) ,{{id}}为获取数据标识(数字类型)。',
        ]);
    }

    /**
     * 返回数据
     * @param string $table  表名
     * @param string $field  查询字段
     * @param int    $id     id条件
     * @var   array  $search 搜索参数
     * @return \think\response\Json
     */
    public function getData(string $table = '', string $field = '', int $id = 0)
    {
        try {
            if (!in_array($table, array_keys($this->provideTableInfo()))) {
                throw new \Exception('table参数不支持此接口');
            }

            $search = $this->request->get('search', []);
            $search = is_array($search) ? $search : [];
            $where  = $id ? [['id', '=', $id]] : [];

            foreach ($search as $field => $value) {
                $exp     = is_numeric($value) ? '=' : 'like';
                $where[] = [$field, $exp, $value];
            }

            $data = $where ? Db::name($table)->field($field)->where($where)->find() : [];
            return ResponseJson::success($data);
        } catch (\Throwable $exception) {
            Log::error($exception->getMessage());
            return ResponseJson::fail();
        }
    }

    /**
     * 非debug模式阻断接口
     * @throws SdException
     */
    private function block()
    {
        if (!env('APP_DEBUG')) {
            throw new SdException('fail');
        }
    }
}
