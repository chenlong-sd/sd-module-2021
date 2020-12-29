<?php
// 应用公共文件

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
        return url(sprintf("/%s/{$url}", env('APP.ADMIN_ALIAS', 'admin')), $vars, $suffix, $domain);
    }
}


if (!function_exists('soft_delete_sql')) {
    /**
     * 软删除查询
     * @param \think\Model|\think\db\Query $sql   模型实例
     * @param string                       $alias 别名
     * @deprecated
     * @return \think\Model|\think\db\Query
     */
    function soft_delete_sql($sql, string $alias = 'i')
    {
        $sql = $sql->alias($alias);
        $prefix = env('DATABASE.PREFIX');
        $table = parse_name(preg_replace("/^{$prefix}/", '', $sql->getTable()), 1);
        if (config('admin.soft_delete') && in_array(config('admin.soft_delete.field'), array_keys(get_model_schema($table)))) {
            $sql = $sql->where(implode('.', [$alias, config('admin.soft_delete.field')]), config('admin.soft_delete.default'));
        }

        return $sql;
    }
}

if (!function_exists('soft_delete_join')) {
    /**
     * 软删除关联组建
     * @param array $join
     * @return array
     */
    function soft_delete_join(array $join)
    {
        if (config('admin.soft_delete')) {
            list($tables, $join_where) = $join;
            $tables = strtr(strtolower(trim($tables)), [' as ' => ' ']);
            if (strpos($tables, ' ') !== false && $tables = explode(' ', $tables)) {
                $table = $tables[0];
                $alias = end($tables);
            } else {
                $table = $alias = $tables;
            }

            if (in_array(config('admin.soft_delete.field'), array_keys(get_model_schema(parse_name($table, 1))))) {
                $join_where .= config('admin.soft_delete.default') === null
                    ? sprintf(" AND {$alias}.%s IS NULL", config('admin.soft_delete.field'))
                    : sprintf(" AND {$alias}.%s = %s", config('admin.soft_delete.field'), config('admin.soft_delete.default'));
                $join[1] = $join_where;
            }
        }
        return $join;
    }
}

if (!function_exists('data_auth_join')) {

    /**
     * 数据权限的join处理
     * @param array $join
     * @return array
     * @throws \app\common\SdException
     */
    function data_auth_join(array $join)
    {
        if (env('APP.DATA_AUTH')){
            $tables = explode('=', preg_replace(['/\sas\s/', '/\s+/',], ['=', ''], $join[0]));
            $alias  = empty($tables[1]) ? $tables[0] : $tables[1];

            $primary = strtr(config('admin.primary'), ['{table}' => $tables[0]]);
            if ($where   = \app\common\BaseModel::dataAuthWhere($tables[0])){
                $join[1] .= " AND {$alias}.$primary IN ($where)";
            }
        }

        return $join;
    }
}




if (!function_exists('get_model_schema')) {
    /**
     * 获取模型的schema
     * @param $model
     * @return array|mixed
     * @author chenlong <vip_chenlong@163.com>
     */
    function get_model_schema($model)
    {
        $object = $model;
        if (is_string($model)) {
            $model = in_array($model, ['Administrators', 'Log', 'Power', 'Role', 'Route', 'Resource', 'DataAuth', 'AdministratorsRole'])
                ? '\\app\\admin\\model\\system\\' . $model
                : '\\app\\admin\\model\\' . $model;
            $object = class_exists($model) ? new $model : null;
        }

        return $object ? (function () {
            return $this->schema;
        })->call($object) : [];
    }
}


if (!function_exists('admin_session')) {
    /**
     * 后台管理员session获取
     * @param      $key
     * @param null $default
     * @return mixed|null
     */
    function admin_session($key = null, $default = null)
    {
        return \app\admin\model\system\Administrators::getSession($key) ?? $default;
    }
}

if (!function_exists('soft_delete_query')) {
    /**
     * @param \think\db\Query|string $object
     * @return \think\db\Query
     * @author chenlong <vip_chenlong@163.com>
     */
    function soft_delete_query($object)
    {
        if (is_string($object)) $object = new $object();

        if(!$object->getOptions('alias') ){
            $object = $object->alias($object->getTable());
        }

        $soft_handle_call = function () {
            $soft_delete_field = config('admin.soft_delete.field');
            $soft_delete_default = config('admin.soft_delete.default');
            $table_alias = $this->getOptions('alias')
                ? $this->getOptions('alias')[$this->getTable()]
                : $this->getTable();
            $option = [];

            foreach ($this->getOptions('join') ?: [] as $item) {
                $table = parse_name(strtr(array_keys($item[0])[0], [env('DATABASE.PREFIX') => '']), 1);
                $alias = array_values($item[0])[0];
                if (in_array(config('admin.soft_delete.field'), array_keys(get_model_schema($table)))) {
                    $item[2] .= config('admin.soft_delete.default') === null
                        ? sprintf(" AND `{$alias}`.`%s` IS NULL", $soft_delete_field)
                        : sprintf(" AND `{$alias}`.`%s` = %s", $soft_delete_field, $soft_delete_default);
                }
                $option[] = $item;
            }
            $this->setOption('join', $option);

            $this->where($table_alias . '.' . $soft_delete_field, $soft_delete_default);
        };

        $soft_handle_call->call($object);

        return $object;
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
        if (admin_session('id') == 1 || !config('admin.access_control')) {
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
            $root = addcslashes(in_array('admin', $domain_bind) ? request()->domain() : request()->root(), '/\\');
            $logo = preg_replace("/^$root\//", '', implode('/', [$pathinfo['dirname'], $pathinfo['filename']]));
        }

        $all_node = cache(config('admin.route_cache')) ?: [];
        $node_id = array_search($logo, $all_node);
        if ((!$node_id && !in_array($pathinfo['filename'], ['create', 'update', 'del']))
            || ($node_id && in_array($node_id, admin_session('route')))
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
    function get_class_attr($class, $attr, $default = null)
    {
        if (!is_object($class) && class_exists($class)) {
            $class = app($class);
        }

        if (is_object($class)) {
            return (fn() => $this->$attr ?? $default)->call($class);
        }
        return $default;
    }

}

