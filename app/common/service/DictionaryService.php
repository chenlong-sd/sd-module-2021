<?php


namespace app\common\service;


use app\admin\model\system\NewDictionary;
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

            $data['content'] = self::contentHandle($data['content']);
            $customize = $data['customize'] ? json_decode($data['customize'], true) : [];
            // 处理字段的选项
            $data = array_merge($data, self::optionsMap($customize));

            unset($data['customize']);

            return $data;
        });
    }

    /**
     * 获取字典内容
     * @param string $dictionary_sign
     * @return array|false|mixed
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/11/27
     */
    public static function getContent(string $dictionary_sign)
    {
        return self::suppressErrorsExecute(function () use ($dictionary_sign){

            $data = NewDictionary::alias('i')
                ->join('dictionary_content d', 'd.new_dictionary_id = i.id')
                ->field('d.dictionary_content')
                ->where('i.sign', $dictionary_sign)
                ->select()->toArray();

            return self::contentHandle($data);
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
                $datum['content'] = self::contentHandle($datum['content']);
                unset($datum['customize']);
            }
            unset($datum);

            return array_column($data, null, 'sign');
        });
    }

    /**
     * 内容处理
     * @param array $data
     * @return array
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/11/27
     */
    private static function contentHandle(array $data): array
    {
        return array_map(function ($content){
            return array_map(function ($v) {
                return is_array($v) ? array_values($v) : $v;
            }, json_decode($content['dictionary_content'], true));
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
            Log::write($throwable->getMessage());
            $data = [];
        } finally {
            return $data;
        }
    }
}

