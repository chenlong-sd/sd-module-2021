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
use app\admin\validate\system\BaseConfig;
use app\common\controller\Admin;
use app\common\ResponseJson;
use app\common\SdException;
use app\common\service\BackstageListsService;
use sdModule\dataBackup\Backup;
use sdModule\layui\defaultForm\Form;
use sdModule\layui\defaultForm\FormData;
use sdModule\layui\Layui;
use sdModule\layui\TablePage;
use sdModule\layui\tablePage\TableAux;
use think\facade\Db;
use think\facade\Env;
use think\helper\Arr;
use think\helper\Str;
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

    /**
     * 数据备份
     * @param BackstageListsService $service
     * @return \think\response\Json|\think\response\View
     * @throws SdException
     * @throws \ReflectionException
     * @throws \think\db\exception\BindParamException
     * @throws \think\db\exception\PDOException
     */
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

        $table->addBarEvent('all_back')->setWarmBtn('备份全部数据', 'slider', 'sm')
            ->setJs(TableAux::openPage(url('system.System/backUp'), '备份数据中')->setConfirm('确认备份数据吗？', ['icon' => 3]));

        $table->addBarEvent('see_all')->setNormalBtn('查看备份数据', 'slider', 'sm')
            ->setJs(TableAux::openPage(url('system.System/viewBackupFiles'), '已备份的文件'));

        $table->addEvent('see')->setNormalBtn('查看备份', 'read', 'xs')
            ->setJs(TableAux::openPage([url(  'system.System/viewBackupFiles'), 'name'], '【{comment}】已备份的文件'));

        $table->addEvent('back_up')->setWarmBtn('开始备份', 'slider', 'xs')
            ->setJs(TableAux::openPage([url('system.System/backUp'), 'name'], '备份{comment}数据中')->setConfirm('确认备份{comment}数据吗？', ['icon' => 3]));

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
            TableAux::column('time', '备份时间'),
            TableAux::column('size', '文件大小',  function (){
                return "return (obj.size / 1024) + ' KB'";
            })
        ]);

        $tables->removeEvent(['update', 'delete']);
        $tables->removeBarEvent(['create', 'delete']);

        $tables->addEvent('recover')->setNormalBtn('恢复', 'time', 'xs')
            ->setJs(TableAux::openPage([url('system.System/dataRecover?table=' . $this->request->get('name')), 'filename'], '数据恢复中....')
                ->setConfirm('确认恢复当前数据吗？', ['icon' => 3, 'title' => '提示']));

        $tables->addEvent('del')->setDangerBtn('删除', 'delete', 'xs')
            ->setJs(TableAux::ajax(url('system.System/backUpDelete?table=' . $this->request->get('name')), '确认删除数据？')
                ->setConfig(['title' => '警告']));

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
     * 开发辅助
     * @return array|\think\response\View
     */
    public function devAux()
    {
        return $this->fetch('');
    }

    /**
     * 基础信息设置
     * @return \think\response\Json|\think\response\View
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function basicInformationSet()
    {
        if ($this->request->isPost()) {
            $this->validate($this->request->post(), BaseConfig::class . '.add');
            $data = Arr::only($this->request->post(), ['group_id', 'group_name', 'key_id', 'key_name', 'form_type', 'options',  'id']);
            $data = array_filter($data);
            $data['update_time'] = datetime();
            if (!empty($data['options'])) {
                $optionArr = [];
                foreach (array_filter(explode("\n", $data['options'])) as $o){
                    if (count($oArr = explode('=', $o)) < 2 || !$oArr[0]){
                        continue;
                    }
                    $optionArr[$oArr[0]] = $oArr[1];
                }
                $data['options'] = json_encode($optionArr, JSON_UNESCAPED_UNICODE);
            }
            if (!empty($data['id']) && $old = BaseConfigM::where(['id' => $data['id']])->find()) {
                $old->setAttrs($data);
                $old->save();
                $id = $data['id'];
            }else{
                $data['create_time'] = datetime();
                $id = BaseConfigM::insertGetId($data);
            }
            return ResponseJson::success([
                'id' => $id
            ]);
        }

        $base = BaseConfigM::field(['id', 'group_id', 'group_name', 'key_id', 'key_name', 'form_type', 'options'])->select()->toArray();
        foreach ($base as &$value) {
            if ($value['options']) {
                $options = json_decode($value['options'], true);
                $op = '';
                foreach ($options as $k => $v){
                    $op .= "{$k}={$v}\n";
                }
                $value['options'] = $op;
            }
        }

        return $this->fetch('base', [
            'base' => $base
        ]);
    }

    /**
     * 10. 给对象加一个属性， 属性值为另一个对象
     * 11. 取出10题里面的另一个对象的值
     * 12. 打印出对象的每一个属性 及 属性值
     * 13. 申明一个函数，函数有一个参数，参数必须为对象， 不是对象打印不是对象
     * 14. 13题里面函数，如果是对象，则打印出对象的每一个属性 及 属性值 （即实现一个函数的功能为打印对象的每一个值
     * @param string $group_id
     * @return \think\response\View
     * @throws SdException
     * @throws \ReflectionException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function baseConfig(string $group_id = '')
    {
        $form = [];
        $data = BaseConfigM::where(compact('group_id'))
            ->field(['id', 'group_id', 'key_id', 'key_name', 'form_type', 'options', 'key_value'])
            ->select()->each(function ($v) use (&$form){
                if ($v->options){
                    $v->options = json_decode($v->options, true);
                }
                $form_type = Str::camel($v->form_type);
                $form[] = FormData::$form_type($v->id, $v->key_name . " [{$v->group_id}.{$v->key_id}]");
            })->toArray();

        $form = Form::create($form)
            ->setDefaultData(array_column($data, 'kay_value', 'id'))
            ->setJs('
            layui.jquery(".layui-form-label").css({width:"270px"});
            layui.jquery(".layui-input-block").css({marginLeft:"300px"});
            
            ')->setCustomMd(12)->complete();

        return $this->fetch('common/save_page', compact('form'));
    }
    
    
    /**
     * @param string $table
     * @return \think\response\Json
     * @throws \think\db\exception\BindParamException
     * @throws \think\db\exception\PDOException
     */
    public function tableFieldQuery(string $table = '')
    {
        $data = Db::query("show COLUMNS FROM `{$table}`");
        $sql  = Db::query("SHOW CREATE TABLE `{$table}`");
        return ResponseJson::success([
            'field' => array_column($data, 'Field'),
            'sql' => strtr(current($sql)['Create Table'], ["\n" => "<br/>&nbsp;&nbsp;&nbsp;&nbsp;"])
        ]);
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
                    'size' => filesize($file),
                    'time' => date('Y-m-d H:i:s', filectime($file))
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
