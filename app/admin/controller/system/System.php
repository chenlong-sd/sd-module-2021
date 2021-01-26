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
use app\common\SdException;
use app\common\service\BackstageListsService;
use sdModule\dataBackup\Backup;
use sdModule\layui\defaultForm\Form;
use sdModule\layui\Layui;
use sdModule\layui\TablePage;
use sdModule\layui\tablePage\TableAux;
use think\facade\Db;
use think\facade\Env;
use think\Model;

/**
 * Class System
 * @package app\admin\controller\system
 * @author chenlong <vip_chenlong@163.com>
 */
class System extends Admin
{

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
            return ResponseJson::mixin($resource::page($page, $limit)->field('path,id,tag,type')->select());
        }

        return view('', [
            'count' => $resource::count(),
        ]);
    }


    public function databaseBackUp(BackstageListsService $service)
    {
        $database = Env::get('DATABASE.DATABASE');
        $tables   = Db::query("select TABLE_NAME name,DATA_LENGTH length,TABLE_COMMENT comment from information_schema.tables WHERE table_schema = '{$database}'");

        if ($this->request->isAjax()) {
            return json([
                'code' => 0,
                'msg' => "success",
                'data' => $tables
            ]);
        }
        $table = TablePage::create([
            TableAux::column("name", '表名'),
            TableAux::column("comment", '表注释'),
            TableAux::column("length", '数据长度', function () {
                return "return (obj.length / 1024) + ' KB'";
            }),
        ]);
        $table->removeEvent(['update', 'delete']);
        $table->removeBarEvent(['create', 'delete']);

        $table->addBarEvent('all_back', Layui::button('备份全部数据', 'slider')->setEvent('all_back')->warm('sm'));
        $table->setBarEventJs('all_back', TableAux::openPage(url('system.System/backUp'), '备份数据中')->setConfirm('确认备份数据吗？', ['icon' => 3]));

        $table->addBarEvent('see_all', Layui::button('查看备份数据', 'slider')->setEvent('see_all')->normal('sm'));
        $table->setBarEventJs('see_all', TableAux::openPage(url('system.System/viewBackupFiles'), '已备份的文件'));

        $table->addEvent('see', Layui::button('备份文件', 'read')->setEvent('see')->normal('xs'));
        $table->setEventJs('see', TableAux::openPage([url(  'system.System/viewBackupFiles'), 'name'], '【{comment}】已备份的文件'));

        $table->addEvent('back_up', Layui::button('开始备份', 'slider')->setEvent('back_up')->warm('xs'));
        $table->setEventJs('back_up', TableAux::openPage([url('system.System/backUp'), 'name'], '备份{comment}数据中')->setConfirm('确认备份{comment}数据吗？', ['icon' => 3]));

        $table->setHandleWidth(220);

        return $this->fetch('common/list_page', [
            'table' => $table,
            'search' => Form::create([])->setNoSubmit()->complete(),
        ]);
    }

    /**
     * 备份数据
     * @throws \ReflectionException
     */
    public function backUp()
    {
        $backup = $this->dataBackUpConnect();
        if (!$table = $this->request->get('name')) {
            $backup->backup(Backup::ALL);
        }else{
            $backup->backupTable($table, Backup::ALL);
        }
    }

    /**
     * 查看备份文件
     */
    public function viewBackupFiles()
    {
        if ($this->request->isAjax()) {
            if (!$table = $this->request->get('name')) {
                $files = $this->getFi(env('DATABASE.BACK_UP_LINK'));
            }else{
                $files = $this->getFi(implode(DIRECTORY_SEPARATOR, [env('DATABASE.BACK_UP_LINK'), 'table', $table]));
            }
            return json([
                'code' => 0,
                'msg' => "success",
                'data' => $files
            ]);
        }

        $tables = TablePage::create([
            TableAux::column('filename', '文件'),
            TableAux::column('size', '文件大小',  function (){
                return "return (obj.size / 1024) + ' KB'";
            })
        ]);

        $tables->removeEvent(['update', 'delete']);
        $tables->removeBarEvent(['create', 'delete']);
        $tables->addEvent('recover', Layui::button('恢复', 'time')->setEvent('recover')->normal('xs'));
        $tables->addEvent('del', Layui::button('删除', 'delete')->setEvent('del')->danger('xs'));

        $tables->setEventJs('del', TableAux::ajax(url('system.System/backUpDelete?table=' . $this->request->get('name')), '确认删除数据？')
            ->setConfig(['title' => '警告']));
        $tables->setEventJs('recover', TableAux::openPage([url('system.System/dataRecover?table=' . $this->request->get('name')), 'filename'], '数据恢复中....')
            ->setConfirm('确认恢复当前数据吗？', ['icon' => 3, 'title' => '提示']));
        $tables->setConfig(['page' => false]);
        return $this->fetch('common/list_page', [
            'table' => $tables,
            'search' => Form::create([])->setNoSubmit()->complete(),
        ]);
    }

    /**
     * 数据恢复
     * @param string $table
     * @param string $filename
     * @throws SdException
     */
    public function dataRecover(string $table = '', string $filename = '')
    {
        $path   = $this->dataFileCheck($table, $filename);
        $backup = $this->dataBackUpConnect();
        $backup->dataRecovery($filename, $table);
    }

    /**
     * 备份文件删除
     * @param string $table
     * @param string $filename
     * @return \think\response\Json
     * @throws SdException
     */
    public function backUpDelete(string $table = '', string $filename = '')
    {
        unlink($this->dataFileCheck($table, $filename));
        return ResponseJson::success();
    }

    /**
     * @param $dir
     * @return array
     */
    private function getFi($dir)
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
                    'size' => filesize($file)
                ];
            }
        }
        closedir($handler);
        return $files;
    }

    /**
     * 数据文件判断
     * @param string $table
     * @param string $filename
     * @return false|string
     * @throws SdException
     */
    private function dataFileCheck(string $table = '', string $filename = '')
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
     * 数据备份的数据库连接
     * @return Backup
     */
    private function dataBackUpConnect()
    {
        $backup = new Backup(env('DATABASE.HOSTNAME'), env('DATABASE.DATABASE'));
        $backup->connect(env('DATABASE.USERNAME'), env('DATABASE.PASSWORD'));
        return $backup;
    }

}
