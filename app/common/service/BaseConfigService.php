<?php
/**
 * Date: 2021/4/16 19:52
 * User: chenlong <vip_chenlong@163.com>
 */

namespace app\common\service;


use app\admin\model\system\BaseConfig as BaseConfigModel;
use sdModule\layui\Dom;

/**
 * Class BaseConfig
 * @package app\common\service
 */
class BaseConfigService
{
    /**
     * 获取基础配置
     * @param string $key 配置组和标识 group_id.key_id
     * @param null|mixed $default 默认值
     * @return mixed|null
     */
    public static function get(string $key = '', $default = null)
    {
        list($group_id, $key_id) = array_pad(explode('.', $key), -2, 'default_group');

        $data = BaseConfigModel::where(compact('group_id', 'key_id'))->value('key_value');

        return $data ?: $default;
    }

    /**
     * 获取一组配置
     * @param string $group
     * @param null $default
     * @return array|mixed|null
     */
    public static function getGroup(string $group, $default = []): ?array
    {
        $data = BaseConfigModel::where("group_id", $group)->column('key_value', 'key_id');

        return $data ?: $default;
    }

    /**
     * 获取所有数据
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public static function getAll(): array
    {
        $data = BaseConfigModel::field(['key_value', 'key_id', 'group_id'])->select()->toArray();
        $return_data = [];
        foreach ($data as $datum) {
            $return_data[$datum['group_id']][$datum['key_id']] = $datum['key_value'];
        }
        return $return_data;
    }

    /**
     * DEBUG模式下的显示参数信息
     * @param string $group 所属组
     * @param string $key   该表单的键
     * @param int $sort     该表单的排序值
     * @return Dom|string
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/6/20
     */
    public static function getDebugParamInfo(string $group, string $key, int $sort)
    {
        if (env('APP_DEBUG')) {
            return Dom::create('span')
                ->addAttr('style', 'display:none;position: absolute;left: 0;top: 0;line-height: 35px;padding: 0 5px;background: rgba(0,0,0,.7);color: white;min-width: 100px;z-index: 9999;')
                ->addClass('sc-key')->addContent("key:$group.$key,sort:$sort");
        }

        return '';
    }
}
