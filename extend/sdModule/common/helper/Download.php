<?php
/**
 *
 * Download.php
 * User: ChenLong
 * DateTime: 2020/5/26 11:19
 */


namespace sdModule\common\helper;

/**
 * 远程文件下载
 * Class Download
 * @package sdModule\common
 */
class Download
{
    /**
     * @param string $url 路径
     * @param string $file 保存本地的文件名
     * @return false|int
     */
    public function get(string $url, string $file)
    {
        return file_put_contents($file, file_get_contents($url));
    }

    /**
     * @param string $url  路径
     * @param string $file 保存本地的文件名
     */
    public function curlGet(string $url,string $file)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_POST, 0);
        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $file_content = curl_exec($ch);
        curl_close($ch);
        $downloaded_file = fopen($file, 'w');
        fwrite($downloaded_file, $file_content);
        fclose($downloaded_file);
    }

    /**
     * @param string $url  路径
     * @param string $file 保存本地的文件名
     */
    public function openGet(string $url, string $file)
    {
        $in = fopen($url, "rb");
        $out = fopen($file, "wb");
        while ($chunk = fread($in,8192))
        {
            fwrite($out, $chunk, 8192);
        }
        fclose($in);
        fclose($out);
    }

    /**
     *
     * 创建目录，支持递归创建目录
     * @param String $dirName 要创建的目录
     * @param int $mode 目录权限
     */
    public function smkdir($dirName , $mode = 0777) {

        $dirs = explode('/' , str_replace('\\' , '/' , $dirName));
        $dir = '';

        foreach ($dirs as $part) {
            $dir.=$part . '/';
            if ( ! is_dir($dir) && strlen($dir) > 0) {
                if ( ! mkdir($dir , $mode)) {
                    return false;
                }
                if ( ! chmod($dir , $mode)) {
                    return false;
                }
            }
        }
        return true;
    }
}

