<?php


namespace app\testApi\controller;


use app\BaseController;
use app\common\middleware\BeComplicatedBy;
use app\common\middleware\Token;
use app\common\ResponseJson;
use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\SdMailer;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use sdModule\common\Sc;
use sdModule\dataBackup\Backup;
use sdModule\image\Image;
use sdModule\layui\formMake\FormMake;
use think\facade\App;
use think\facade\Db;
use think\facade\Log;

class Index extends BaseController
{
    public $middleware = [BeComplicatedBy::class];
    const DD = 33;
    const FD = 233;

    public function index()
    {
        return <<<HTML
    <style>
        body{
            background: #efefef;
        }
        div{
            font-size: 50px;
            position: absolute;
            width:100%; 
            height: 100%;
            line-height: 100%;
        }
        p:hover{
            font-size: 100px;
            opacity: 0;
            cursor: pointer;
        }
        p,h3{
            position: absolute;
            top: 50%;
            left: 0;
            height: 50px;
            text-align: center;
            font-weight: bold;
            width: 100%;
            transition: .5s;
            color: red;
            margin: -25px 0 0 0;
            text-shadow: white -1px -1px, black 1px 1px;
            user-select: none;
        }
    </style>
    <div>
        <p>系统维护中！</p>
    </div>
HTML;
    }
    /**
     * @title 你好
     * @return string|\think\response\Json
     * @throws \ReflectionException
     */
    public function aux()
    {
        var_dump($this->request->middleware('token.user_id'));
        var_dump($this->request->middleware('token.s'));
    }

    public function test()
    {
//        return ResponseJson::Status404();
        halt(Sc::binarySystem()->notAppointTo(time(), 'd'));
        return ResponseJson::success(JWT::getToken(['user_id' => 23232323]));
        return ResponseJson::success(JWT::getRefresh()::getToken(['user_id' => 23232323]));
    }


    public function excel()
    {
//        halt(Db::name('advance_detail_copy8')->limit(1,10)->select()->toArray());
        $inputFileName = App::getRootPath() . '102ss9.xls';
        $excel = Sc::excel($inputFileName, 'write', 'Xls');
        $count = Db::name('advance_detail_copy8')->count();
        for ($i = 0; $i <= $count; $i+= 500){
            $data = Db::name('advance_detail_copy8')->limit($i, 500)->select()->toArray();
            $excel->writeFromArray($data);
        }
        $excel->download();

    }


    public function xt()
    {
        for ($i = 0; $i <= 1000; $i++) {
            $msg = openssl_random_pseudo_bytes(25);
            $q1 = Sc::Ciphertext()->encrypt($msg, '', 'aes-192-ofb');
            $q2 = Ciphertext::encrypt($msg, '2e', 'aes-192-ccm');
            if ($msg === Ciphertext::decrypt($q1, '', 'aes-192-ofb')
                && $msg === Ciphertext::decrypt($q2, '2e', 'aes-192-ccm')) {

            }else{
                dump(1111);
            }
        }
    }

    public function file()
    {
//        halt(2 & 1, 3 & 1);
//        $backup = new Backup('127.0.0.1', 'golf');
        $backup = new Backup('127.0.0.1', 'golf_test');
//        $backup->connect('root', 'Admin@123')->backup(Backup::DATA);
        $backup->connect('root', 'Admin@123')->dataRecovery('database_20210125172709.sql');
    }

    public function wx()
    {
        $putForward = new \weChat\pay\PutForward();
        $putForward->openid = 'eqwe'; // 用户的openid
        $putForward->amount = 1;  // 要提取的金额
        $putForward->desc = '支付提取描述';    // 支付提取描述
        halt($putForward->request());
    }

    public function image()
    {
        if (!is_dir($dir = App::getRootPath() . '/public/tmp'))
            mkdir($dir, 0777, true);

        $file = tempnam($dir, 'download_');

        $url = 'http://seopic.699pic.com/photo/50058/6067.jpg_wh1200.jpg';
        $exp = explode('.', $url);
        $fp = fopen($file, 'w');

        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_FILE, $fp);
        curl_exec($curl);
        curl_close($curl);
        fclose($fp);

        $type = finfo_open(FILEINFO_MIME_TYPE, '');

        $do_file = 'avatar.' . end($exp);
        header("Content-type: {$type}");
        header("Content-Disposition: attachment;filename={$do_file}");
        header("Content-Transfer-Encoding: binary");
        header('Pragma: no-cache');
        header('Expires: 0');

        set_time_limit(0);
        readfile($file);
        unlink($file);
    }

    public function pdf()
    {
        $s = IOFactory::load('E:\phpstudy_pro\WWW\sd-module\public\ccc.pdf');
        halt($s->getActiveSheet()->toArray());
    }

    public function res()
    {
        Log::write("ADU==".memory_get_usage(true), 'error');
        Sc::resource()->remote('http://127.0.0.1/t.mp4');
//        Sc::resource()->localhost('C:\Users\Administrator\Desktop\ccc.pdf');
//        Sc::resource()->localhost('E:\phpstudy_pro\WWW\A01___01_Genesis_____ENGESVO2DA.mp3');
//        Sc::resource()->localhost('E:\phpstudy_pro\WWW\t.mp4');
//        Log::write("ADU==".memory_get_usage(true), 'error');
//        Log::write("----------------------", 'error');
//        return Sc::resource()->remote('http://127.0.0.1/A01___01_Genesis_____ENGESVO2DA.mp3');
//        // 读取资源文件
//        Sc::resource()->read('E:\phpstudy_pro\WWW\t.mp4');
    }

    public function sms()
    {
        halt(Sc::aLiYunSms()->batchSend(['18380108033', '18328021672'], [
            ['code' => '123'], ['code' => '456']
        ]));
    }

    public function redisSubscribe()
    {
        ob_clean();
        echo 1111111;
        Sc::redis()->getRedis()->subscribe(['test'], function ($redis, $channel, $message) {
            dump($redis, $channel, $message);
        });
    }
}

