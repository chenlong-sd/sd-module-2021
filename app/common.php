<?php
// 应用公共文件

use app\admin\AdminLoginSession;

if (!function_exists('data_except')) {
    /**
     * 排除 $data 里面 key值在 $filter 里面的数据
     * @param array $data
     * @param array $filter
     * @return array
     * @author chenlong <vip_chenlong@163.com>
     */
    function data_except(array $data, array $filter)
    {
        return array_diff_key($data, array_flip($filter));
    }
}

if (!function_exists('data_only')) {
    /**
     * 只要 $data 里面 key值在 $filter 里面的数据
     * @param array $data
     * @param array $filter
     * @return array
     * @author chenlong <vip_chenlong@163.com>
     */
    function data_only(array $data, array $filter)
    {
        return array_intersect_key($data, array_flip($filter));
    }
}


if (!function_exists('resourceZipDownload')) {
    /**
     * 打包zip
     * @param array  $data     数据
     * @param string $filename 文件名
     * @return string|\think\response\File
     */
    function resourceZipDownload($data, $filename)
    {
        $root = \think\facade\App::getRootPath();
        $zipName = $root . 'public/zip/ss.zip';
        if (!file_exists(dirname($zipName))) {
            mkdir(dirname($zipName), 777);
        }
        if (!file_exists($zipName)) file_put_contents($zipName, '');

        $zip = new ZipArchive();

        $zipHandle = $zip->open($zipName, ZipArchive::OVERWRITE);
        if ($zipHandle !== true) {
            return '文件打包失败';
        }

        foreach ($data as $name => $photo) {
            $file = explode(',', $photo);
            $file = array_filter($file);
            foreach ($file as $index => $value) {
                $zip->addFile(strtr($root . '/public/' . trim($value), ['\\' => '/']), $name . "{$index}" . basename($value));
            }
        }
        if (!$zip->close()) {
            return '文件打包失败!';
        }

        if (!file_exists($zipName)) {
            return '资源缺失！打包失败！';
        }

        return download($zipName, $filename . '.zip');
    }
}


if (!function_exists('admin_url')) {
    /**
     * 后台路由地址生成
     * @param string $url    路由地址
     * @param array  $vars   变量
     * @param bool   $suffix 生成的URL后缀
     * @param bool   $domain 域名
     * @return string|\think\route\Url
     */
    function admin_url(string $url = '', array $vars = [], $suffix = true, $domain = false)
    {
        if (config('app.domain_bind')) {
            return url('/' .  $url, $vars, $suffix, $domain);
        }
        return url(sprintf("/%s/{$url}", env('APP.ADMIN_ALIAS') ?: 'admin'), $vars, $suffix, $domain);
    }
}


if (!function_exists('lang_load')) {
    /**
     * 语言问价加载
     * @param string $dir
     * @param string $file_name
     * @return array
     */
    function lang_load(string $dir, string $file_name)
    {
        $lang_module = [];

        if (is_dir($dir) && ($dh = opendir($dir))) {
            while (($file = readdir($dh)) !== false) {
                if ($file == '.' || $file == '..' || !is_dir($dir . DIRECTORY_SEPARATOR . $file)) continue;

                $lang_file = $dir . DIRECTORY_SEPARATOR . $file . DIRECTORY_SEPARATOR . $file_name;

                $lang_module[$file] = file_exists($lang_file) ? include $lang_file : [];
            }
            closedir($dh);
        }
        return $lang_module;
    }
}

if (!function_exists('datetime')) {
    /**
     * 日期常用格式
     * @param string $format    格式
     * @return false|string
     */
    function datetime($format = 'Y-m-d H:i:s')
    {
        return date($format);
    }
}



if (!function_exists('access_control')) {

    /**
     * 权限访问控制
     * @param \think\route\Url|string $url
     * @param bool $exception
     * @return bool
     * @throws \app\common\SdException
     */
    function access_control($url, $exception = false)
    {
        if (\app\admin\service\system\AdministratorsService::isSuper()) {
            return true;
        }
        if (!$pathinfo = pathinfo($url)) {
            goto end;
        }

        $domain_bind = config('app.domain_bind', []);
        $app_map = config('app.app_map', []);
        if (in_array('admin', $app_map) && !in_array('admin', $domain_bind) && $pathinfo['dirname'] === '/admin') {
            $route = array_column(\think\facade\Route::getRuleList(), 'route', 'rule');
            $logo = $route[$pathinfo['filename']];
        } else {
            $root = in_array('admin', $domain_bind) ? request()->domain() : request()->root();
            $root = strtr(dirname(request()->baseFile()) . $root, ['\\' => '/']);
            $root = addcslashes(preg_replace('/\/+/', '/', $root), '/');
            $logo = preg_replace("/^$root\//", '', implode('/', [$pathinfo['dirname'], $pathinfo['filename']]));
        }
        $logo     = parse_name(preg_replace_callback('/\.[a-z]/', function ($v){
            return strtoupper($v[0]);
        }, $logo), 1);
        $all_node = cache(config('admin.route_cache')) ?: [];
        $node_id  = array_search($logo, $all_node);
        if ((!$node_id && !in_array($pathinfo['filename'], ['create', 'update', 'del']))
            || ($node_id && in_array($node_id, AdminLoginSession::getRoute([])))
        ) {
            return true;
        }
        end:
        if ($exception) {
            throw new \app\common\SdException('No access');
        }
        return false;
    }
}

if (!function_exists('get_class_attr')) {

    /**
     * 调用类的私有或受保护的属性
     * @param string|object $class 类实例|全命名空间的类名
     * @param string        $attr 属性名
     * @param null|mixed    $default 默认值
     * @return mixed|null
     */
    function get_class_attr($class, string $attr, $default = null)
    {
        if (!is_object($class) && class_exists($class)) {
            $class = app($class);
        }

        if (is_object($class)) {
            return (function () use ($attr, $default) {
                return $this->$attr ?? $default;
            })->call($class);
        }
        return $default;
    }

}

if (!function_exists('data_filter')) {

    /**
     * 数据过滤
     * @param array $data
     * @return array
     */
    function data_filter(array $data): array
    {
        $data = array_map(function ($v) {
            return is_array($v) ? $v : trim($v);
        }, $data);
        return array_filter($data, function ($v) {
            return !empty($v) || $v === 0 || $v === '0';
        });
    }
}


if (!function_exists('base_config')) {
    /**
     * 获取基础配置
     * @param string $key 配置组和标识 group_id.key_id
     * @param null|mixed $default 默认值
     * @return mixed|null
     */
    function base_config(string $key, $default = null)
    {
        return \app\common\service\BaseConfigService::get($key, $default);
    }
}

