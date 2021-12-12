<?php


namespace app\common\service;


use app\admin\model\system\NewDictionary;
use think\exception\HttpResponseException;
use think\facade\Log;

/**
 * Class DictionaryService
 * @package app\common\service
 */
class DictionaryService
{
    /**
     * 获取字典
     * @param string $dictionary_sign 字典标识
     * @return array
     */
    public static function get(string $dictionary_sign)
    {
        return self::suppressErrorsExecute(function () use ($dictionary_sign){

            $data = NewDictionary::alias('i')->with('content')
                ->field('id,sign,name,image,introduce,customize')
                ->where('sign', $dictionary_sign)
                ->find()->toArray();

            $customize = $data['customize'] ? json_decode($data['customize'], true) : [];
            $data['content'] = self::contentHandle($data['content'], $customize);
            // 处理字段的选项
            $data = array_merge($data, self::optionsMap($customize));

            unset($data['customize']);

            return $data;
        });
    }

    /**
     * 获取字典内容
     * @param string $dictionary_sign       字典标识 sign
     * @param string|null $searchContent    搜索内容(匹配方式like) 例： ‘龙%’
     * @param string|null $sort             排序方式，传值： ASC | DESC，默认id倒序
     * @param callable|null $callable       自定义查询的回调函数，返回查询结果（数组），如使用时间排序，
     * @return array|false|mixed
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/11/27
     */
    public static function getContent(string $dictionary_sign, string $searchContent = null, string $sort = null, callable $callable = null)
    {
        return self::suppressErrorsExecute(function () use ($dictionary_sign, $searchContent, $sort, $callable){
            $where = [['i.sign', '=', $dictionary_sign]];
            if ($searchContent) $where[] = ['d.search', 'like', $searchContent];

            $model = NewDictionary::alias('i')
                ->join('dictionary_content d', 'd.new_dictionary_id = i.id')
                ->field('i.customize,d.dictionary_content')
                ->where($where);
            if ($sort) {
                $model->order('d.sort', strtoupper($sort) === 'ASC' ? 'ASC' : 'DESC');
            }
            $model->order('d.id', 'DESC');

            // h获取查询结果
            $data = is_callable($callable) ? call_user_func($callable, $model) : $model->select()->toArray();

            if (!$data) return [];

            $customize = json_decode(current($data)['customize'], true);

            return self::contentHandle($data, $customize);
        });
    }

    /**
     * 获取所有字典
     * @return array
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/11/27
     */
    public static function getAll(): array
    {
        return self::suppressErrorsExecute(function () {

            $data = NewDictionary::alias('i')->with('content')
                ->field('id,sign,name,image,introduce,customize')
                ->select()->toArray();

            foreach ($data as &$datum) {
                $customize = $datum['customize'] ? json_decode($datum['customize'], true) : [];
                // 处理字段的选项
                $datum = array_merge($datum, self::optionsMap($customize));
                // 处理内容
                $datum['content'] = self::contentHandle($datum['content'], $customize);

                unset($datum['customize']);
            }
            unset($datum);
            return array_column($data, null, 'sign');
        });
    }

    /**
     * 内容处理
     * @param array $data
     * @param array $customize
     * @return array
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/11/27
     */
    private static function contentHandle(array $data, array $customize): array
    {
        $fieldConfig = $customize ? array_column($customize, 'd_key') : ['value', 'name'];

        return array_map(function ($content) use ($fieldConfig){
            $dictionary_content = [];
            $haveContent        = json_decode($content['dictionary_content'], true);
            foreach ($fieldConfig as $field){
                if (!isset($haveContent[$field])){
                    $dictionary_content[$field] = null;
                    continue;
                }
                $dictionary_content[$field] = is_array($haveContent[$field])
                    ? array_values($haveContent[$field])
                    : $haveContent[$field];
            }
            return $dictionary_content;
        }, $data);
    }


    /**
     * 选项映射
     * @param array $fieldConfig 字段配置
     * @return array
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/11/27
     */
    private static function optionsMap(array $fieldConfig): array
    {
        $map = [];
        foreach ($fieldConfig as $value) {
            if (empty($value['d_options'])) {
                continue;
            }
            $map["{$value['d_key']}_map"] = array_map(function ($v) {
                return array_combine(['value', 'name'], explode('=', $v));
            }, explode(',', strtr($value['d_options'], ['，' => ','])));
        }
        return $map;
    }

    /**
     * 抑制错误执行代码
     * @param callable $callable 要执行的代码
     * @return array|false|mixed
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/11/27
     */
    private static function suppressErrorsExecute(callable $callable)
    {
        try {
            $data = call_user_func($callable);
        } catch (\Throwable $throwable) {
            $data = [];
            if ($throwable instanceof HttpResponseException) {
                throw $throwable;
            }
            Log::write($throwable->getMessage());
        } finally {
            return $data;
        }
    }
}

