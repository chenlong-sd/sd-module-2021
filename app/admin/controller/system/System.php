<?php
/**
 *
 * System.php
 * User: ChenLong
 * DateTime: 2020/4/28 16:45
 */


namespace app\admin\controller\system;

use app\admin\model\system\BaseConfig as BaseConfigM;
use app\admin\model\system\Resource;
use app\admin\page\system\SystemPage;
use app\admin\service\system\SystemService;
use app\admin\validate\system\BaseConfig;
use app\common\controller\Admin;
use app\common\ResponseJson;
use app\common\SdException;
use sdModule\dataBackup\Backup;
use think\facade\Db;
use think\facade\Env;
use think\helper\Arr;

/**
 * Class System
 * @package app\admin\controller\system
 * @author chenlong <vip_chenlong@163.com>
 */
class System extends Admin
{
    /**
     * @title("本地资源数据列表")
     * @param Resource $resource
     * @param int $page
     * @param int $limit
     * @return \think\response\Json|\think\response\View
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/11/6
     */
    public function resource(Resource $resource, int $page = 1, int $limit = 10)
    {
        if ($this->request->isAjax()) {
            return ResponseJson::mixin($resource->page($page, $limit)->field('path,id,tag,type')->select());
        }

        return view('', [
            'count' => $resource->count(),
        ]);
    }

    /**
     * @title('数据备份')
     * @param SystemPage $page
     * @return \think\response\Json|\think\response\View
     * @throws SdException
     * @throws \ReflectionException
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/11/6
     */
    public function databaseBackUp(SystemPage $page)
    {
        if ($this->request->isAjax()) {
            $database = Env::get('DATABASE.DATABASE');
            $tables   = Db::query("select TABLE_NAME name,DATA_LENGTH length,TABLE_COMMENT comment from information_schema.tables WHERE table_schema = '{$database}'");

            return json([
                'code' => 0,
                'msg'  => "success",
                'data' => $tables
            ]);
        }

        return view('common/list_page_3_5', [
            'table' => $page->dataBackUp(),
            'search' => $page->listSearchFormData()
        ]);
    }

    /**
     * @title("开始备份数据")
     * @return string|\think\response\Json|\think\response\View
     * @throws SdException
     * @throws \ReflectionException
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/11/6
     */
    public function backUp()
    {
        if ($this->request->isAjax()) {
            if ($this->request->isPost()) {
                Backup::outputTip(">>>>>>>>>>>>>>>>>>>");
                $backup = SystemService::getBackUpInstance();
                if (!$table = $this->request->post('name')) {
                    $backup->backup(Backup::ALL);
                }else{
                    $backup->backupTable($table, Backup::ALL);
                }
                return ResponseJson::success();
            }

            return ResponseJson::success(Backup::getTip());
        }

        if (Backup::getTip()) {
            return '当前有备份文件正在备份中....';
        }

        return view();
    }

    /**
     * @title("查看备份文件")
     * @param SystemService $service
     * @param SystemPage $page
     * @return \think\response\Json|\think\response\View
     * @throws SdException
     * @throws \ReflectionException
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/11/6
     */
    public function viewBackupFiles(SystemService $service, SystemPage $page)
    {
        if ($this->request->isAjax()) {
            $files = $this->request->get('name')
                ? $service->getCurrentBackupFile(implode(DIRECTORY_SEPARATOR, [env('DATABASE.BACK_UP_LINK'), 'table', $this->request->get('name')]))
                : $service->getCurrentBackupFile(env('DATABASE.BACK_UP_LINK'));

            return json([
                'code' => 0,
                'msg'  => "success",
                'data' => $files
            ]);
        }

        return view('common/list_page_3_5', [
            'table' => $page->viewBackupFiles(),
            'search' => $page->listSearchFormData()
        ]);
    }

    /**
     * @title("数据恢复")
     * @param string $table
     * @param string $filename
     * @return string|\think\response\Json|\think\response\View
     * @throws SdException
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/11/6
     */
    public function dataRecover(string $table = '', string $filename = '')
    {
        if ($this->request->isAjax()) {
            if ($this->request->isPost()){
                Backup::outputTip(">>>>>>>>>>>>>>>>>>>");
                $path   = SystemService::dataFileCheckAndReturnFilePath($filename, $table);
                $backup = SystemService::getBackUpInstance();
                $backup->dataRecovery($filename, $table);
                return ResponseJson::success();
            }

            return ResponseJson::success(Backup::getTip());
        }

        if (Backup::getTip()) {
            return '当前有备份文件正在备份中....';
        }

        return view();
    }

    /**
     * @title("删除备份文件")
     * @param string $table
     * @param string $filename
     * @return \think\response\Json
     * @throws SdException
     */
    public function backUpDelete(string $table = '', string $filename = ''): \think\response\Json
    {
        unlink(SystemService::dataFileCheckAndReturnFilePath($filename, $table));
        return ResponseJson::success();
    }

    /**
     * @title("开发辅助")
     * @return \think\response\View
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/11/6
     */
    public function devAux(): \think\response\View
    {
        return view();
    }

    /**
     * @title("数据表字段详情页")
     * @return \think\response\View
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/11/6
     */
    public function field(): \think\response\View
    {
        return view();
    }

    /**
     * @title("基础信息设置")
     * @param SystemService $service
     * @return \think\response\Json|\think\response\View
     * @throws SdException
     */
    public function basicInformationSet(SystemService $service)
    {
        if ($this->request->isPost()) {
            $this->validate($this->request->post(), BaseConfig::class . '.add');
            $data = Arr::only($this->request->post(), ['group','key','sort','form_type','required','placeholder','short_tip','key_value','options','id' ]);
            $id   = $service->baseConfigSave(array_filter($data));

            return ResponseJson::success(compact('id'));
        }

        return view('base', [
            'base' => $service->basicInformationSetInitData()
        ]);
    }

    /**
     * @title("基础信息配置（组页面")
     * @param SystemService $service
     * @param SystemPage $page
     * @param string $group_id
     * @return \think\response\Json|\think\response\View
     * @throws SdException
     * @throws \ReflectionException
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/11/8
     */
    public function baseConfig(SystemService $service, SystemPage $page, string $group_id = '')
    {
        if ($this->request->isPost()) {

            // 数据保存
            $service->baseInfoConfigDataSave($this->request->post());

            return ResponseJson::success();
        }

        return view('common/save_page_4', [
            'form' => $page->baseInfoItem($group_id),
        ]);
    }

    /**
     * @title("删除基础信息设置")
     * @param int $id
     * @return \think\response\Json
     */
    public function deleteConfig(int $id = 0): \think\response\Json
    {
        BaseConfigM::destroy([$id], true);
        return ResponseJson::success();
    }

    /**
     * @title("数据表字段查询")
     * @param string $table
     * @return \think\response\Json
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/11/8
     */
    public function tableFieldQuery(string $table = ''): \think\response\Json
    {
        $data = Db::query("show COLUMNS FROM `{$table}`");
        $sql  = Db::query("SHOW CREATE TABLE `{$table}`");
        return ResponseJson::success([
            'field' => array_column($data, 'Field'),
            'sql'   => current($sql)['Create Table']
        ]);
    }

    /**
     * @title('代码高亮显示')
     * @return \think\response\View
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/12/6
     */
    public function codeMirror(): \think\response\View
    {
        return view();
    }

    /**
     * @title('开发数据查询')
     * @return \think\response\Json
     * @author chenlong<vip_chenlong@163.com>
     * @date 2022/2/23
     */
    public function getTableData(): \think\response\Json
    {
        try {
            $data  = Db::table($this->request->get('table_name', ''))->page($this->request->get('page'), $this->request->get('limit'))->select();
            $total = Db::table($this->request->get('table_name', ''))->count('id');
        } catch (\Exception $exception) {
            return json([
                'code' => 1,
                'msg'  => $exception->getMessage()
            ]);
        }
        return json([
            'code'  => 0,
            'data'  => $data,
            'count' => $total,
            'msg'   => 'success'
        ]);
    }
}
