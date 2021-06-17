<?php
namespace sdModule\common\helper;
/**
 * 仿写CodeIgniter的FTP类
 * FTP基本操作：
 * 1) 登陆;           connect
 * 2) 当前目录文件列表;  filelist
 * 3) 目录改变;         chgdir

 *
 * @author quanshuidingdang
 */
class Ftp
{

    private $hostname   = '';
    private $username   = '';
    private $password   = '';
    private $port       = 21;
    private $passive    = true;
    private $debug      = true;
    /**
     * @var bool|int
     */
    private $conn_id    = true;

    /**
     * Ftp constructor.
     * @param string $host
     * @param string $username
     * @param string $password
     * @param int $port
     * @throws \Exception
     */
    public function __construct(string $host, string $username, string $password, int $port = 21)
    {
        $this->hostname = $host;
        $this->username = $username;
        $this->password = $password;
        $this->port = $port;
        if (!extension_loaded('ftp')){
            throw new \Exception('没有检测到FTP扩展');
        }
    }

    /**
     * FTP连接
     *
     * @access  public
     * @param   array   配置数组
     * @return  boolean
     */
    public function connect() {
        if(false === ($this->conn_id = ftp_connect($this->hostname,$this->port))) {
            $this->_error("ftp_unable_to_connect");
            return false;
        }

        if( ! $this->_login()) {
            $this->_error("ftp_unable_to_login");
            return false;
        }

        ftp_set_option($this->conn_id,FTP_USEPASVADDRESS,false);

        if($this->passive === true) {
            ftp_pasv($this->conn_id, true);
        }

        return true;
    }
    /**
     * 获取目录文件列表
     *
     * @access  public
     * @param   string  $path 目录标识(ftp)
     * @return  array
     */
    public function fileList(string $path)
    {
        return $this->checkConnect() ? ftp_nlist($this->conn_id, $path) : [];
    }

    /**
     * 递归查询所有目录下的文件
     * @param string $path
     * @return array
     */
    public function dirSwitch(string $path = '.')
    {
        //查看目录文件
        $catalog = $this->filelist($path);
        $list = [];
        foreach ($catalog as $val){
            if($this->isFtpDir($val)){
                $list = array_merge($list, $this->dirSwitch($val));
            }else{
                $list[] = $val;
            }
        }
        return $list;
    }

    /**
     * 目录改变
     * @param string $path
     * @param bool $suppress_debug
     * @return bool|false|string
     */
    public function changeDir($path = '', $suppress_debug = false)
    {
        if ($path == '' || !$this->checkConnect()) {
            return false;
        }
        $result = ftp_chdir($this->conn_id, $path);
        if ($result === false) {
            if ($suppress_debug === false) {
                $this->_error("ftp_unable_to_chgdir:dir[" . $path . "]");
            }
            return false;
        }
        return ftp_pwd($this->conn_id);
    }

    /**
     * 下载文件
     * @param string $origin_path 原路径
     * @param string $save_path 新路径
     * @param int $model
     * @return bool
     */
    public function download(string $origin_path, string $save_path, $model = FTP_BINARY)
    {
        is_dir(dirname($save_path)) or mkdir(dirname($save_path), 0777, true);
        return ftp_get($this->conn_id, $save_path, $origin_path, $model);
    }

    /**
     * 查看文件是否存在
     * @param string $filename
     * @return bool
     */
    private function isFtpDir(string $filename)
    {
        return ftp_size($this->conn_id, $filename) === -1;
    }

    /**
     * 关闭FTP
     *
     * @access  public
     * @return  bool
     */
    public function close()
    {
        return $this->checkConnect() ? ftp_close($this->conn_id) : false;
    }

    /**
     * FTP登陆
     *
     * @access  private
     * @return  boolean
     */
    private function _login()
    {
        return ftp_login($this->conn_id, $this->username, $this->password);
    }

    /**
     * 判断con_id
     *
     * @access  private
     * @return  boolean
     */
    private function checkConnect()
    {
        if( ! is_resource($this->conn_id)) {
            $this->_error("ftp_no_connection");
            return false;
        }
        return true;
    }

    /**
     * 从文件名中获取后缀扩展
     *
     * @access  private
     * @param   string  目录标识
     * @return  string
     */
    private function _getext(string $filename) {
        if(false === strpos($filename, '.')) {
            return 'txt';
        }

        $extarr = explode('.', $filename);
        return end($extarr);
    }

    /**
     * 从后缀扩展定义FTP传输模式  ascii 或 binary
     *
     * @access  private
     * @param   string  后缀扩展
     * @return  string
     */
    private function setType(string $ext) {
        $text_type = [
            'txt',
            'text',
            'php',
            'phps',
            'php4',
            'js',
            'css',
            'htm',
            'html',
            'phtml',
            'shtml',
            'log',
            'xml'
        ];

        return in_array($ext, $text_type) ? 'ascii' : 'binary';
    }

    /**
     * 错误日志记录
     *
     * @access  prvate
     * @param string $msg
     * @return  boolean
     */
    private function _error(string $msg)
    {
        if (!$this->debug) return false;
        $dir = app()->getRuntimePath() . '/ftp';
        is_dir($dir) or mkdir($dir, 0777, true);
        return file_put_contents(app()->getRuntimePath() . '/ftp/ftp_err.log', "date[".date("Y-m-d H:i:s")."]-hostname[".$this->hostname."]-username[".$this->username."]-password[".$this->password."]-msg[".$msg."]\n", FILE_APPEND);
    }
}