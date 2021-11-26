<?php
/**
 *
 * Install.php
 * User: ChenLong
 * DateTime: 2020/4/28 16:53
 */


namespace app\admin\controller\system;


use app\common\ResponseJson;
use app\common\SdException;
use sdModule\common\Sc;
use think\facade\App;

/**
 * Class Install
 * @package app\admin\controller\system
 * @author chenlong <vip_chenlong@163.com>
 */
class Install
{
    /**
     * @var array 默认配置
     */
    private $default = [
        'type'          => 'mysql',
        'host'          => '127.0.0.1',
        'database'      => 'sd_cms_s',
        'user'          => 'root',
        'password'      => 'root',
        'port'          => '3306',
        'prefix'        => 'sd_',
        'company'       => '',
        'admin_alias'   => 'admin'
    ];

    /**
     * @return string
     */
    private function getDatabaseSqlFile()
    {
        return App::getRootPath() . 'sd_module.sql';
    }

    /**
     * @return string
     */
    private function getEnvFile()
    {
        return App::getRootPath() . '.example.env';
    }

    /**
     * @return \think\response\Json|\think\response\Redirect|\think\response\View
     * @throws SdException
     */
    public function index()
    {
        if (file_exists(App::getRootPath() . '.env')) {
            return redirect(admin_url());
        }
        if (request()->isPost()) {
            if (!file_exists($this->getDatabaseSqlFile())) {
                return ResponseJson::fail('数据库文件sd_module.sql缺失！');
            }
            if (!file_exists($this->getEnvFile())) {
                return ResponseJson::fail('.example.env 文件缺失！');
            }

            return $this->init(array_filter(request()->post()));
        }

        return view();
    }


    /**
     * @param $data
     * @return \think\response\Json
     * @throws SdException
     */
    private function init($data)
    {
        $config = array_merge($this->default, $data);

        if (!$this->initDatabase($config)) {
            return ResponseJson::fail('数据库初始化失败！');
        }

        if ($this->initEnv($config)) {
            $middleware_config_path    = App::getRootPath() . 'app/middleware.php';
            $middleware_config_content = file_get_contents($middleware_config_path);
            $install_middleware_close  = [
                '\\app\\common\\middleware\\Install::class' => '// \\app\\common\\middleware\\Install::class'
            ];

            $result = file_put_contents($middleware_config_path, strtr($middleware_config_content, $install_middleware_close));

            if ($result === false) {
                return ResponseJson::fail("文件:{$middleware_config_path},无写权限");
            }
            return ResponseJson::success('数据库初始化成功！初始账号：admin，密码：123456。');
        }

        return ResponseJson::success('数据库初始化成功！初始账号：admin，密码：123456。配置文件设置失败，请手动配置，参考:' . $this->getEnvFile());
    }

    /**
     * 数据库初始化
     * @param $config
     * @return bool
     * @throws SdException
     */
    private function initDatabase($config)
    {
        if (empty($config['database'])) {
            throw new SdException('请填写数据库名字');
        }

        try {
            $pdo = new \PDO("{$config['type']}:host={$config['host']};port={$config['port']}", $config['user'], $config['password']);
        } catch (\PDOException $exception) {
            throw new SdException($exception->getMessage());
        }

        if (!$pdo->prepare("CREATE DATABASE IF NOT EXISTS `{$config['database']}` CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;")->execute()) {
            throw new SdException('数据库创建失败！');
        }

        $pdo->prepare("USE `{$config['database']}`;")->execute();

        $sql = strtr(file_get_contents($this->getDatabaseSqlFile()), ['`sd_' => '`' . $config['prefix']]);
        return $pdo->prepare($sql)->execute();
    }

    /**
     * 初始化env文件
     * @param $config
     * @return bool|int
     */
    private function initEnv($config)
    {
        $replace = array_combine(array_map(function ($value) {
            return (':SD__' . $value);
        }, array_keys($this->default)), $config);

        $range_arr = range(1, 9) + range('a', 'z') + range('A', 'Z');
        shuffle($range_arr);

        $replace[':SD__no_token_value'] = md5(Sc::binarySystem()->notAppointTo(time(), $range_arr[0]));
        $replace[':SD_jwt_secret']      = Sc::binarySystem()->notAppointTo(time(), $range_arr[1]);
        $replace[':SD_jwt_refresh']     = Sc::binarySystem()->notAppointTo(time(), $range_arr[2]);
        $replace[':SD__upload_dir']     = 'upload_resource';
        $replace[':SD__data_back_up_link'] = App::getRootPath() . 'dataBackUp';

        $content = strtr(file_get_contents($this->getEnvFile()), $replace);
        $file    = strtr($this->getEnvFile(), ['.example.env' => '.env']);

        return file_put_contents($file, $content);
    }
}
