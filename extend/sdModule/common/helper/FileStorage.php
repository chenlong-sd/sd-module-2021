<?php
/**
 *
 * FileStorage.php
 * User: ChenLong
 * DateTime: 2020/4/27 17:24
 */


namespace sdModule\common\helper;


use app\common\SdException;
use think\facade\App;

class FileStorage
{
    private const DEFAULT_DIR = 'resource';

    /**
     * @var string 存储的文件夹
     */
    private $storage_dir = '';

    /**
     * @var string 当前组
     */
    private $current_group = '';


    /**
     * 默认文本
     * @param mixed $content 内容
     * @param int $expire 有效期
     * @return string
     */
    private function getWritContent($content, $expire = 0)
    {
        $content = json_encode($content, JSON_UNESCAPED_UNICODE);
        $expire  = (int)$expire;
        return "<?php exit();?>\r\n{$expire}\r\n{$content}";
    }


    /**
     * 保存数据
     * @param array|string $data 数组或保存值的键
     * @param null|mixed $value 保存的值
     * @param int $expire  有效期（秒）
     * @return mixed|void
     * @throws SdException
     */
    public function set($data, $value = null, $expire = 0)
    {
        if (is_array($data)) {
            foreach ($data as $key => $val) {
                $this->set($key, $val, $value);
            }
            return true;
        }else{
            if (!file_put_contents($this->getPath($data), $this->getWritContent($value, $expire))){
                throw new SdException("{$data}存储失败！");
            }
            return true;
        }
    }

    /**
     * 获取数据
     * @param null $key
     * @param null|array $default
     * @return mixed|null
     */
    public function get($key = null, $default = null)
    {
        $content = $this->getData($key);
        if ($content) {
            return is_array($content)
                ? array_map(function($v){return json_decode($v, true);}, $content)
                : json_decode($content, true);
        }

        return $default;
    }

    /**
     * 删除数据
     * @param null $key
     * @return bool
     */
    public function del($key = null)
    {
        if ($key === null && $this->storage_dir !== $this->current_group) {
            $this->delDirFile();
            return (is_dir($this->current_group) and rmdir($this->current_group));
        }

        if ($key !== null) {
            return (file_exists($this->getPath($key)) and unlink($this->getPath($key)));
        }
        return false;
    }

    /**
     * 删除文件夹
     */
    private function delDirFile()
    {
        $handler = opendir($this->current_group);
        while (($filename = readdir($handler)) !== false) {
            if ($filename != "." && $filename != ".." ) {
                unlink($this->current_group . $filename);
            }
        }
        closedir($handler);
    }


    /**
     * 获取对应文件的所有配置
     * @param $key
     * @return array|string
     */
    private function getData($key)
    {
        if (!$key) {
            $data = [];
            $handler = opendir($this->current_group);
            while (($filename = readdir($handler)) !== false) {
                if ($filename != "." && $filename != ".." && !is_dir($this->current_group . $filename )) {
                    $file_key = strtr($filename, ['.php' => '']);
                    $data[$file_key] = $this->getData($file_key);
                }
            }
            closedir($handler);
            return $data;
        }

        if (file_exists($file_path = $this->getPath($key))) {
            $info = file($file_path, FILE_IGNORE_NEW_LINES);
            $valid_time = $info[1] ?? 0;
            $valid = $valid_time ? (filemtime($file_path) + $valid_time) >= time() : true;

            if (!$valid) $this->del($key);

            return $valid ? ltrim($info[2] ?? null) : null;
        }
        return null;
    }

    /**
     * @param null $key
     * @return string
     */
    private function getPath($key = null)
    {
        if (!$this->storage_dir) {
            $this->storage_dir = App::getRootPath() . self::DEFAULT_DIR . DIRECTORY_SEPARATOR;
        }
        if ($key === null) return $this->storage_dir;

        return $this->current_group . $key . '.php';
    }

    /**
     * FileStorage constructor.
     * @param string|null $group
     */
    public function __construct(string $group = null)
    {
        $this->getPath();
        $this->current_group = $this->storage_dir . ($group ?: 'common') . DIRECTORY_SEPARATOR;
        if (!is_dir($this->current_group)) {
            mkdir($this->current_group, 0777, true);
        }
    }

}

