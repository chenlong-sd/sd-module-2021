<?php
/**
 * datetime: 2021/11/5 23:51
 * user    : chenlong<vip_chenlong@163.com>
 **/

namespace app\admin\service\system;

use app\admin\AdminBaseService;
use app\admin\model\system\BaseConfig as BaseConfigM;
use app\admin\validate\system\BaseConfig;
use app\common\ResponseJson;
use app\common\SdException;
use sdModule\dataBackup\Backup;
use think\facade\Db;
use think\facade\Log;
use think\helper\Arr;

/**
 * Class SystemService
 * @package app\admin\service\system
 * @author chenlong<vip_chenlong@163.com>
 * @date 2021/11/6
 */
class SystemService extends AdminBaseService
{

    /**
     * 基础信息配置的保存
     * @param array $data
     * @return mixed
     * @throws SdException
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/11/6
     */
    public function baseConfigSave(array $data)
    {
        // 解析分组和键值对应的值
        $group = explode('=', $data['group']);
        $key   = explode('=', $data['key']);
        if (count($group) != 2 || count($key) != 2) {
            throw new SdException('分组信息或者参数信息错误');
        }
        list($data['group_id'], $data['group_name']) = $group;
        list($data['key_id'], $data['key_name'])     = $key;
        unset($data['group'], $data['key']);

        // 如果有选项参数的时候
        if (!empty($data['options'])) {
            $optionArr = [];
            foreach (array_filter(explode("\n", $data['options'])) as $options){
                $optionsResolve = explode('=', $options);
                $optionArr[$optionsResolve[0]] = $optionsResolve[1] ?? $optionsResolve[0];
            }
            $data['options'] = json_encode($optionArr, JSON_UNESCAPED_UNICODE);
        }

        // 声明对象实例
        $baseConfigModel = new BaseConfigM();

        // 如果id值不为空，更新对象实例
        if (!empty($data['id'])) {
            $baseConfigModel = $baseConfigModel->findOrEmpty($data['id']);
            if ($baseConfigModel->isEmpty()) {
                throw new SdException('原始数据不存在');
            }

            unset($data['id']);
        }
        $baseConfigModel->save($data);

        // 返回数据id值
        return $baseConfigModel->id;
    }

    /**
     * 基础信息配置的数据保存
     * @param array $data
     * @throws SdException
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/11/8
     */
    public function baseInfoConfigDataSave(array $data)
    {
        Db::startTrans();
        try {
            foreach ($data as $id => $value){
                if (substr($id, 0, 2) !== 'id') continue;
                $new_id = substr($id, 2);
                $baseConfig = BaseConfigM::find($new_id);

                $baseConfig->key_value = is_array($value) ? implode(',', $value) : $value;
                $baseConfig->save();
            }

            Db::commit();
        } catch (\Exception $exception) {
            Db::rollback();
            throw new SdException($exception->getMessage());
        }
    }

    /**
     * 获取目录下的备份文件
     * @param string $dir
     * @return array
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/11/8
     */
    public function getCurrentBackupFile(string $dir): array
    {
        if (!is_dir($dir)){
            return [];
        }
        $handler = opendir($dir);
        $files = [];
        while (($filename = readdir($handler)) !== false) {//务必使用!==，防止目录下出现类似文件名“0”等情况
            if (is_file($file = realpath($dir . '/' . $filename))) {
                $files[] = [
                    'filename' => $filename,
                    'size'     => filesize($file),
                    'time'     => date('Y-m-d H:i:s', filectime($file))
                ];
            }
        }
        closedir($handler);
        return $files;
    }

    /**
     * 数据文件判断，并返回文件路径
     * @param string $filename 文件名字
     * @param string $table    数据表名
     * @return false|string
     * @throws SdException
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/11/8
     */
    public static function dataFileCheckAndReturnFilePath(string $filename, string $table = '')
    {
        if (!$filename) {
            throw new SdException('文件错误');
        }

        $dir = \env('DATABASE.BACK_UP_LINK');
        if ($table) {
            $dir = implode(DIRECTORY_SEPARATOR, [$dir, 'table', $table]);
        }
        $path = realpath($dir . '/' . $filename);
        if (!is_file($path)) {
            throw new SdException('文件不存在');
        }

        return $path;
    }

    /**
     * 获取数据备份的实例
     * @return Backup
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/11/8
     */
    public static function getBackUpInstance(): Backup
    {
        $backup = new Backup(env('DATABASE.HOSTNAME'), env('DATABASE.DATABASE'));
        $backup->connect(env('DATABASE.USERNAME'), env('DATABASE.PASSWORD'));
        return $backup;
    }

    /**
     * 基础信息设置的初始数据
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/11/8
     */
    public function basicInformationSetInitData(): array
    {
        try {
            $base = BaseConfigM::select()->toArray();
        } catch (\Throwable $exception) {
            $base = [];
            \think\facade\Log::write($exception->getMessage(), 'error');
        }
        foreach ($base as &$value) {
            if ($value['options']) {
                $op = '';
                foreach (json_decode($value['options'], true) as $k => $v){
                    $op .= "$k=$v\n";
                }
                $value['options'] = $op;
            }
            $value['group'] = "{$value['group_id']}={$value['group_name']}";
            $value['key']   = "{$value['key_id']}={$value['key_name']}";
        }

        return $base;
    }
}

