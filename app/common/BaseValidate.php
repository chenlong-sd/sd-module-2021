<?php
/**
 * Date: 2020/11/24 9:10
 * User: chenlong <vip_chenlong@163.com>
 */

namespace app\common;


use think\Validate;

class BaseValidate  extends Validate
{
    /**
     * 重写》验证是否唯一，增加额外字段条件
     * @example "name" => "unique:user,openid=qwe&zxc=23"
     * @access public
     * @param mixed $value 字段值
     * @param mixed $rule 验证规则 格式：数据表,字段名,排除ID,主键名
     * @param array $data 数据
     * @param string $field 验证字段名
     * @return bool
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function unique($value, $rule, array $data = [], string $field = ''): bool
    {
        if (is_string($rule)) {
            $rule = explode(',', $rule);
        }

        if (false !== strpos($rule[0], '\\')) {
            // 指定模型类
            $db = new $rule[0];
        } else {
            $db = $this->db->name($rule[0]);
        }

        $key = $rule[1] ?? $field;
        $map = [];

        if (strpos($key, '^')) {
            // 支持多个字段验证
            $fields = explode('^', $key);
            foreach ($fields as $key) {
                if (isset($data[$key])) {
                    $map[] = [$key, '=', $data[$key]];
                }
            }
        }elseif (strpos($key, '=')){ // 新增的额外字段验证
            parse_str($key, $verify);
            $map[] = [$field, '=', $data[$field]];
            foreach ($verify as $field_ => $value_){
                $map[] = [$field_, '=', $value_];
            }
        } elseif (isset($data[$field])) {
            $map[] = [$key, '=', $data[$field]];
        } else {
            $map = [];
        }

        $pk = !empty($rule[3]) ? $rule[3] : $db->getPk();

        if (is_string($pk)) {
            if (isset($rule[2])) {
                $map[] = [$pk, '<>', $rule[2]];
            } elseif (isset($data[$pk])) {
                $map[] = [$pk, '<>', $data[$pk]];
            }
        }

        if ($db->where($map)->where('delete_time', 0)->field($pk)->find()) {
            return false;
        }

        return true;
    }
}
