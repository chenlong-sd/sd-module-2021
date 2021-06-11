<?php
/**
 * Date: 2020/11/20 12:27
 * User: chenlong <vip_chenlong@163.com>
 */

namespace sdModule\common\helper;

/**
 * 资源读取
 * Class Resources
 * @package sdModule\common\helper
 */
class Resources
{
    /**
     * 读取资源
     * @param string $file_path 文件路径
     * @param string $file_name 文件名字
     */
    public function localhost(string $file_path, string $file_name = '')
    {
        $size = filesize($file_path);
        $mime = finfo_file(finfo_open(FILEINFO_MIME), $file_path);

        $this->output($mime, $size, $file_path, $file_name);
    }

    /**
     * 远程读取文件
     * @param string $url
     * @param string $file_name
     */
    public function remote(string $url, string $file_name = '')
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt($ch, CURLOPT_NOBODY, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_exec($ch);
        $mime = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
        $size = curl_getinfo($ch, CURLINFO_CONTENT_LENGTH_DOWNLOAD);

        $this->output($mime, $size, $url, $file_name);
    }

    /**
     * 输出
     * @param string $mime 文件mime类型
     * @param int $size 文件大小
     * @param string $url 文件路径
     * @param string $file_name 文件名字
     */
    private function output(string $mime, int $size, string $url, string $file_name = '')
    {
        if (isset($_SERVER['HTTP_RANGE'])) {
            header("HTTP/1.1 206 Partial Content");
            list($name, $range) = explode('=', $_SERVER['HTTP_RANGE']);
            list($begin, $end)  = explode('-', $range);
            if ($end == 0) {
                $end = $size - 1;
            }
        }else{
            $begin = 0;
            $end   = $size - 1;
        }
        $length   = $end - $begin + 1;
        $filename = $file_name ?: basename($url);

        header("Content-Type:{$mime}");
        header("Accept-Range:bytes");
        header("Content-Length:{$length}");
        header("Content-Disposition:filename={$filename}");
        header("Content-Range:bytes {$begin}-{$end}/{$size}");

        ob_end_flush();
        $fp = fopen($url, 'rb');
        stream_get_contents($fp, 0, $begin);
        while (!feof($fp)) {
            echo stream_get_contents($fp, 8192);
        }
        fclose($fp);
    }

}
