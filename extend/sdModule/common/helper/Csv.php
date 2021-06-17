<?php
/**
 * Date: 2021/1/7 9:46
 * User: chenlong <vip_chenlong@163.com>
 */

namespace sdModule\common\helper;


use think\facade\Log;

class Csv
{
    /**
     * @var string[]
     */
    private $header = [
        "Content-Type" => "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet",
        "Content-Disposition" => "attachment;filename=\"%s\"",
        "Cache-Control" => "max-age=0"
    ];

    /**
     * @var mixed|\Iterator|array
     */
    private $data;

    /**
     * 设置header数据
     * @param string $key
     * @param string $value
     * @return Csv
     */
    public function addHeader(string $key, string $value): Csv
    {
        $this->header[$key] = $value;
        return $this;
    }

    /**
     * 删除指定响应头数据
     * @param string $key
     * @return Csv
     */
    public function removeHeader(string $key): Csv
    {
        unset($this->header[$key]);
        return $this;
    }

    /**
     * 设置数据
     * @param array|\Iterator|mixed $data
     * @return Csv
     */
    public function setData($data): Csv
    {
        $this->data = $data;
        return $this;
    }

    /**
     * 保存csv文件
     * @param string $path
     */
    public function save(string $path)
    {
        $this->output($path);
    }

    /**
     * 下载csv文件
     * @param string $filename
     */
    public function download(string $filename)
    {
        $this->header['Content-Disposition'] = sprintf($this->header['Content-Disposition'], $filename);
        $this->headerOutput()->output('php://output');
    }

    /**
     * 读取数据
     * @param string $path 文件路径
     * @param callable $handle 数据处理回调
     * @param int $number 每次回调处理的条数
     */
    public function read(string $path, callable $handle, int $number = 1000)
    {
        $fd  = fopen($path, 'r');
        $num = 0;
        $data = [];
        while (($row = fgetcsv($fd)) !== false) {
            $num++;
            $data[] = array_map(function ($v) {
                return iconv('gbk', 'utf-8', $v);
            }, $row);
            if ($num >= $number) {
                call_user_func($handle, $data);
                $data = [];
                $num = 0;
            }
        }
        call_user_func($handle, $data);
        fclose($fd);
    }

    /**
     * @param string $path
     */
    private function output(string $path)
    {
        $fd = fopen($path, 'a');
        $i  = 0;
        foreach ($this->data as $value) {
            $value = $value instanceof \ArrayAccess ? $value->toArray() : $value;
            fputcsv($fd, array_map(function ($v) {
                return iconv('utf-8', 'gbk', $v);
            }, $value));
            $i++;
            if ($i >= 10000) {
                ob_flush();
                flush();
            }
        }
        ob_flush();
        flush();
        fclose($fd);
    }

    private function headerOutput(): Csv
    {
        foreach ($this->header as $key => $value){
            header("{$key}:{$value}");
        }
        return $this;
    }
}
