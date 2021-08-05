<?php
/**
 * Date: 2020/11/10 15:13
 * User: chenlong <vip_chenlong@163.com>
 */

namespace sdModule\common\helper;


use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Reader\IReader;
use PhpOffice\PhpSpreadsheet\Reader\IReadFilter;
use PhpOffice\PhpSpreadsheet\Settings;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use think\facade\Cache;
use function Matrix\identity;

/**
 * Excel 一些常见操作，更复杂的请自行调用office库代码进行处理
 * Class Excel
 * @package sdModule\common\helper
 */
class Excel
{
    /**
     * @var Spreadsheet
     */
    private $office;
    /**
     * @var IReader|null
     */
    private $read;

    /**
     * @var string 文件路径
     */
    private $excel_path;

    /**
     * @var string 文件格式
     */
    private $format;

    /**
     * @var int 当前写入行
     */
    private $currentWriteLine = 1;

    /**
     * @var string 临时路劲
     */
    private $tmp_path;
    /**
     * @var bool
     */
    private $stop_read = false;
    /**
     * @var int 数据开始行
     */
    private $data_start_row = 2;

    /**
     * Excel constructor.
     * @param string $excel_path excel 文件路径
     * @param string $mode 模式 read | write
     * @param string $format 文件格式： Xlsx | Xls | Xml | Ods | Slk | Gnumeric | Html | Csv
     * @throws \PhpOffice\PhpSpreadsheet\Reader\Exception
     */
    public function __construct(string $excel_path, string $mode = 'read', string $format = '')
    {
        $this->excel_path = $excel_path;
        $this->format     = $format;
        $this->tmp_path   = app()->getRuntimePath() . '/tmp/';

        if ($mode === 'read') {
            $this->format = $this->format ?: IOFactory::identify($excel_path);
            $this->read   = IOFactory::createReader($this->format);
            $this->office = $this->read->load($excel_path);
        }else{
            $this->office = new Spreadsheet();
        }
    }

    /**
     * 获取活动sheet页面的数据
     * @param bool $multiple 是否是多个页面
     * @return array
     */
    public function getActiveData(bool $multiple = false)
    {
        return $multiple ? $this->getActivesData() : $this->office->getActiveSheet()->toArray();
    }

    /**
     * 分批次读取处理
     * @param callable $callable 回调函数，两个参数，第一个是当前批次数据，$data，第二个是当前sheet的标题，及数据标题
     * @param int $number 每次处理的数量
     */
    public function batchRead(callable $callable, int $number = 500)
    {
        $sheet = $this->office->getActiveSheet();
        $this->batchHandle($sheet, $callable, $number);
    }

    /**
     * 分批次读取处理(所有sheet
     * @param callable $callable 回调函数，两个参数，第一个是当前批次数据，$data，第二个是当前sheet的标题，及数据标题
     * @param int $number 每次处理的数量
     */
    public function allActiveBatchRead(callable $callable, int $number = 500)
    {
        foreach ($this->office->getAllSheets() as $worksheet) {
            if ($this->stop_read) {
                break;
            }
            $this->batchHandle($worksheet, $callable, $number);
        }
    }

    /**
     * 下载文件
     * @return mixed
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     */
    public function download()
    {
        $this->downloadHeader();
        return IOFactory::createWriter($this->office, $this->format)->save("php://output");
    }

    /**
     * 写入数据,可多次调用（用于分批次
     * @param array $data
     * @param string $sheet_title
     * @return Excel
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     */
    public function writeFromArray(array $data, string $sheet_title = null)
    {
        $this->office->getActiveSheet()->fromArray($data, null, sprintf('A%d', $this->currentWriteLine));
        if (($count = count($data)) === count($data, 1)) {
            $count = 1;
        }

        $this->currentWriteLine += $count;
        if ($sheet_title !== null) {
            $this->office->getActiveSheet()->setTitle($sheet_title);
        }

        return $this;
    }

    /**
     * 到文件名数据
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     */
    public function save()
    {
        $this->dirCheckAndMake();
        IOFactory::createWriter($this->office, $this->format)->save($this->excel_path);
    }

    /**
     * 停止读取数据
     */
    public function stopRead()
    {
        $this->stop_read = true;
    }

    public function newBatchHandle($c)
    {
        $chunkFilter = $this->chuckReadFilter('A', 'K');
        $this->read->setReadFilter($chunkFilter);
        /**  Loop to read our worksheet in "chunk size" blocks  **/
        for ($startRow = 2; $startRow <= 1000; $startRow += 500) {
            /**  Tell the Read Filter which rows we want this iteration  **/
            $chunkFilter->setRows($startRow, 500);
            /**  Load only the rows that match our filter  **/
            $spreadsheet = $this->read->load($this->excel_path);
            //    Do some processing here
            call_user_func($c, $spreadsheet->getActiveSheet()->toArray(),  $spreadsheet->getActiveSheet()->getTitle());
        }
    }


    /**
     * 下载文件头
     */
    private function downloadHeader()
    {
        $file_name = basename($this->excel_path);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-Disposition: attachment;filename=\"{$file_name}\"");
        header('Cache-Control: max-age=0');
    }

    /**
     * 文件夹判断并创建
     * @param string|null $path
     */
    private function dirCheckAndMake(string $path = null)
    {
        $path = $path ?: dirname($this->excel_path);
        is_dir($path) or mkdir($path, 0777, true);
    }


    /**
     * 获取多个sheet页面的数据
     * @return array
     */
    private function getActivesData()
    {
        $data = [];
        foreach ($this->office->getAllSheets() as $worksheet) {
            $data[] = [
                'sheet_title' => $worksheet->getTitle(),
                'data' => $worksheet->toArray()
            ];
        }
        return $data;
    }

    /**
     * 分批量处理
     * @param Worksheet $sheet
     * @param callable $callable
     * @param int $number
     */
    private function batchHandle(Worksheet $sheet, callable $callable, int $number)
    {
        list('row' => $row, 'column' => $column) = $sheet->getHighestRowAndColumn();

        $sheet_param = [
            'sheet_title' => $sheet->getTitle(),
            'data_title'  => current($sheet->rangeToArray(sprintf("A1:%s1", $column))),
        ];

        for ($i = $this->data_start_row; $i <= $row; $i += $number) {
            $end = ($row > ($i + $number)) ? $i + $number - 1 : $row;
            if ($this->stop_read) {
                break;
            }
            call_user_func($callable, $sheet->rangeToArray(sprintf("A%d:%s%d", $i, $column, $end)), $sheet_param, $this);
        }
    }


    /**
     * 过滤器
     * @param string $start_line
     * @param string $end_line
     * @return mixed
     */
    private function chuckReadFilter(string $start_line, string $end_line)
    {
        return new class($start_line, $end_line) implements IReadFilter
        {
            private $startRow = 1;

            private $endRow = 1;

            private $start_line;

            private $end_line;

            public function __construct($start_line, $end_line)
            {
                $this->start_line = $start_line;
                $this->end_line   = $end_line;
            }

            /**  Set the list of rows that we want to read
             * @param int $startRow
             * @param int $chunkSize
             */
            public function setRows(int $startRow, int $chunkSize)
            {
                $this->startRow = $startRow;
                $this->endRow   = $startRow + $chunkSize;
            }

            public function readCell($column, $row, $worksheetName = '')
            {
                if (($row == 1) || ($row >= $this->startRow && $row < $this->endRow)) {
                    if ($column >= $this->start_line && $column <= $this->end_line) {
                        return true;
                    }
                }
                return false;
            }
        };
    }

    /**
     * 设置数据开始的行数
     * @param int $data_start_row
     * @return Excel
     */
    public function setDataStartRow(int $data_start_row): Excel
    {
        $this->data_start_row = $data_start_row;
        return $this;
    }
}
