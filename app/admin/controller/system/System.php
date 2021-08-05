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
use app\common\service\BaseConfigService;
use sdModule\dataBackup\Backup;
use sdModule\layui\Dom;
use sdModule\layui\form\Form;
use sdModule\layui\form\FormUnit;
use sdModule\layui\form\UnitData;
use sdModule\layui\Layui;
use sdModule\layui\TablePage;
use sdModule\layui\tablePage\ListsPage;
use sdModule\layui\tablePage\module\TableAux;
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
        $table = ListsPage::create([
            TableAux::column("name", '表名'),
            TableAux::column("comment", '表注释'),
            TableAux::column("length", '数据长度')->setTemplate("return (obj.length / 1024) + ' KB'"),
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

        $table->setHandleAttr([
            'width' => 220
        ]);

        return $this->fetch('common/list_page', [
            'table' => $table,
            'search' => Form::create([])->setSubmitHtml()->complete(),
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

        $tables = ListsPage::create([
            TableAux::column('filename', '文件'),
            TableAux::column('time', '备份时间'),
            TableAux::column('size', '文件大小')->setTemplate("return (obj.size / 1024) + ' KB'")
        ]);

        $tables->setHandleAttr([
            'align' => 'center',
            'width' => 200
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
            'search' => Form::create([])->setSubmitHtml()->complete(),
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
            $data = Arr::only($this->request->post(), ['group','key','sort','form_type','required','placeholder','short_tip','key_value','options','id' ]);
            $data = array_filter($data);
            $data['update_time'] = datetime();
            $group = explode('=', $data['group']);
            $key   = explode('=', $data['key']);
            if (count($group) != 2 || count($key) != 2) {
                throw new SdException('分组信息或者参数信息错误');
            }
            $data['group_id']   = $group[0];
            $data['group_name'] = $group[1];
            $data['key_id']     = $key[0];
            $data['key_name']   = $key[1];
            unset($data['group'], $data['key']);

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

        $base = BaseConfigM::select()->toArray();
        foreach ($base as &$value) {
            if ($value['options']) {
                $options = json_decode($value['options'], true);
                $op = '';
                foreach ($options as $k => $v){
                    $op .= "{$k}={$v}\n";
                }
                $value['options'] = $op;
            }
            $value['group'] = "{$value['group_id']}={$value['group_name']}";
            $value['key'] = "{$value['key_id']}={$value['key_name']}";
        }

        return $this->fetch('base', [
            'base' => $base
        ]);
    }

    /**
     * 基础信息配置（组页面
     * @param string $group_id
     * @return \think\response\Json|\think\response\View
     * @throws SdException
     * @throws \ReflectionException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function baseConfig(string $group_id = '')
    {
        if ($this->request->isPost()) {
            Db::startTrans();
            try {
                foreach ($this->request->post() as $id => $value){
                    if (substr($id, 0, 2) !== 'id') continue;
                    $new_id = substr($id, 2);
                    BaseConfigM::where(['id' => $new_id])->update([
                        'key_value' => is_array($value) ? implode(',', $value) : $value,
                        'update_time' => datetime()
                    ]);
                }

                Db::commit();
            } catch (\Throwable $exception) {
                Db::rollback();
                throw new SdException($exception->getMessage());
            }
            return ResponseJson::success();
        }

        // 页面数据
        $form_data = $short_form = $inline = $key = [];
        $init_sort_value = 1;
        $data = BaseConfigM::where(compact('group_id'))
            ->field(['id', 'group_id', 'key_id', 'key_name', 'form_type', 'options', 'key_value', 'short_tip', 'placeholder', 'required', 'sort'])
            ->order('sort', 'acs')->order('id', 'asc')
            ->select()->each(function ($v) use (&$form_data, &$short_form, &$init_sort_value, &$inline, &$key){
                $form_type = Str::camel($v->form_type);
                $v->id     = 'id' . $v->id;
                /** @var UnitData $form_unit */
                $form_unit = FormUnit::$form_type($v->id,  $v->key_name . BaseConfigService::getDebugParamInfo($v->group_id, $v->key_id, $v->sort));
                // 选项值设置
                $v->options  and $form_unit->options(json_decode($v->options, true));
                // 必选设置
                $v->getData('required') == 1  and $form_unit->required();
                // placeholder 设置
                $v->placeholder and $form_unit->placeholder($v->placeholder);
                // 短标签设置
                $v->short_tip and $short_form[$v->id] = $v->short_tip;

                // 当前的排序值不等于上一次的排序值
                if ($v->sort != $init_sort_value && $inline) {
                    // 有行内表单的时候，看看行内表单的个数，大于一个则加入行内，否则不处理
                    $form_data[] = count($inline) > 1 ? FormUnit::build(...$inline) : current($inline);
                    $init_sort_value = $v->sort;
                    $inline = [];
                }
                $inline[] = $form_unit;
            })->toArray();
        $form_data[] = count($inline) > 1 ? FormUnit::build(...$inline) : current($inline);
        $form = Form::create($form_data)
            ->setDefaultData(array_column($data, 'key_value', 'id'))
            ->setSkinToPane()
            ->setJs('
            layui.jquery(".layui-form-label").on("mouseover", function(){ 
                if(layui.jquery(this).find(".sc-key")){
                  layui.jquery(this).css({overflow:"visible"}).find(".sc-key").show();
                }
            }).on("mouseout", function(){
                if(layui.jquery(this).find(".sc-key")){
                  layui.jquery(this).css({overflow:"hidden"}).find(".sc-key").hide();
                }
            })
            ');
        // 短标签设置
        $short_form and $form->setShortForm($short_form);

        $form->complete();
        return $this->fetch('common/save_page', compact('form'));
    }

    /**
     * 删除设置
     * @param int $id
     * @return \think\response\Json
     */
    public function deleteConfig(int $id = 0)
    {
        BaseConfigM::destroy([$id], true);
        return ResponseJson::success();
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
            'sql' => current($sql)['Create Table']
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
