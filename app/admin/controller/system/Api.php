<?php
/**
 * Api.php
 * User: ChenLong
 * DateTime: 2020-12-11 11:09:22
 */

namespace app\admin\controller\system;

use \app\common\controller\Admin;
use app\common\ResponseJson;
use app\common\SdException;
use think\facade\Db;


/**
 * Class Api
 * @package app\admin\controller\Api
 * @author chenlong <vip_chenlong@163.com>
 */
class Api extends Admin
{
    const PARAM_TYPE_GET  = 1;
    const PARAM_TYPE_POST = 2;
    const PARAM_TYPE_HEAD = 3;


    /**
     * 列表数据接口
     * @return mixed|string|\think\Collection|\think\response\Json
     * @throws \app\common\SdException
     */
    public function listData()
    {
        return $this->setNoPage()
            ->setField('i.id,i.api_name,i.path,i.describe,i.update_time,i.method')
            ->setSort('update_time,desc')
            ->listsRequest();
    }

    /**
     * @return array|\think\response\View
     * @throws SdException
     */
    public function add()
    {
        $api = \app\admin\model\system\ApiModule::getDataById(request()->get('api_module_id', 0))->toArray();
        return $this->fetch('add', [
            'data' => $api,
        ]);
    }

    /**
     * @param $id
     * @return array|\think\response\View
     * @throws SdException
     */
    public function edit($id)
    {
        $data = \app\admin\model\system\Api::getDataById($id)->getData();
        $api = \app\admin\model\system\ApiModule::getDataById($data['api_module_id'] ?? 0)->toArray();
        $param = \app\admin\model\system\QueryParams::getDataByWhere(['api_id' => $id]);

        $new_param = [];
        foreach ($param as $item) {
            $new_param[$item->getData('method')][] = $item;
        }

        return $this->fetch('add', [
            'data'  => $api,
            'api'   => $data,
            'param' => $new_param
        ]);
    }


    public function save()
    {
        $data = $this->request->post();
        $this->validate($body = $this->filter($data['body']), \app\admin\validate\system\Api::class . '.add');

        Db::startTrans();
        try {
            $body['update_time'] = datetime();
            $body['response']  = $body['response'] ?? '';
            if (empty($body['id'])) {
                $body['create_time'] = datetime();
                $id = \app\admin\model\system\Api::insertGetId($body);
                if (!$id) throw new SdException('保存失败！');
            }else{
                $id = $body['id'];
                if (!\app\admin\model\system\Api::update($body, ['id' => $id])) {
                    throw new SdException('更新失败！');
                }
            }
            \app\admin\model\system\QueryParams::softDelete(['api_id' => $id]);

            $param = array_merge(
                $this->paramHandle($data['get'] ?? [], $id, self::PARAM_TYPE_GET),
                $this->paramHandle($data['post'] ?? [], $id, self::PARAM_TYPE_POST),
                $this->paramHandle($data['head'] ?? [], $id, self::PARAM_TYPE_HEAD),
            );

            \app\admin\model\system\QueryParams::insertAll($param);

            Db::commit();
        } catch (\Exception $exception) {
            Db::rollback();
            throw new SdException('保存失败！' . $exception->getMessage() . $exception->getLine());
        }

        return ResponseJson::success();
    }

    /**
     * @param $data
     * @param $api_id
     * @param $method
     * @return mixed
     */
    private function paramHandle($data, $api_id, $method)
    {
        foreach ($data as &$item) {
            $item['api_id'] = $api_id;
            $item['method'] = $method;
            $item['create_time'] = datetime();
            $item['update_time'] = datetime();
        }
        return $data;
    }
}
