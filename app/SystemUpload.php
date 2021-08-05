<?php


namespace app;


use app\admin\model\system\Resource;
use OSS\Core\OssException;
use sdModule\common\Sc;
use sdModule\image\Image;
use think\facade\App;
use think\file\UploadedFile;
use app\common\SdException;
use app\common\ResponseJson;
use think\helper\Arr;

class SystemUpload
{
    const UPLOAD_DIR = 'upload_resource';

    private $verify_type = 'image';

    private const VERIFY_RULE = [
        'image' => [
            'mime' => ['image/jpeg', 'image/jpg' , 'image/png', 'image/gif', 'image/bmp', 'image/vnd.microsoft.icon', 'image/webp'],
            'ext' => ['jpg', 'jpeg', 'png', 'bmp', 'ico', 'webp', 'gif']
        ],
        'excel' => [
            'mime' => ['application/vnd.ms-excel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'application/zip', 'text/csv'],
            'ext' => ['xls', 'xlsx', '.csv'],
        ],
        'video' => [
            'mime' => ['video/x-msvideo', 'video/mpeg', 'video/ogg', 'video/webm', 'video/mp4'],
            'ext' => ['webm', 'avi', 'mpeg', 'ogv', 'mp4'],
        ],
        'audio' => [
            'mime' => ['audio/mpeg', 'audio/ogg', 'audio/wav', 'audio/webm'],
            'ext' => ['weba', 'wav', 'mp3', 'oga'],
        ],
        'word' => [
            'mime' => ['application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'],
            'ext' => ['doc', 'docx',],
        ],
        'pdf' => [
            'mime' => ['application/pdf'],
            'ext' => ['pdf', 'PDF', 'Pdf',],
        ]
    ];

    /**
     * 文件验证
     * @param string $type
     * @return UploadedFile
     * @throws SdException
     */
    private function fileVerify($type = null)
    {
        if (!$files = request()->file()) {
            throw new SdException('无上传文件');
        }

        if (!class_exists('finfo')) {
            throw new SdException('请安装fileinfo扩展');
        }

        $file_form_name = version_compare(PHP_VERSION,'7.3.0', '<') ?
            array_keys($files)[0] : array_key_first($files);
        $this->verify_type = $type === null ? (string)$file_form_name : $type;
        if (substr($this->verify_type, 0, 6) !== 'limit_') {
            throw new SdException('文件上传接口不被允许。');
        }
        $this->verify_type = substr($this->verify_type, 6);

        /** @var  UploadedFile $file */
        $file = $files[$file_form_name];

        if ($this->verify_type !== 'all') {
            $this->fileTypeVerify();
            $this->mimeVerify($file);
        }

        return $file;
    }

    /**
     * 文件类型验证
     * @throws SdException
     */
    private function fileTypeVerify()
    {
        $allow = false;

        foreach (explode('*', $this->verify_type) as $verify_type) {
            if (isset(self::VERIFY_RULE[$verify_type])) {
                $allow = true;
                break;
            }
        }

        if (!$allow) {
            throw new SdException('上传文件格式不对，请确认');
        }
    }


    /**
     * mime 验证
     * @param UploadedFile $file
     * @throws SdException
     */
    private function mimeVerify(UploadedFile $file)
    {
        $allow = false;
        foreach (explode('*', $this->verify_type) as $verify_type) {
            $rule = self::VERIFY_RULE[$verify_type];
            if (in_array($file->getMime(), $rule['mime']) && in_array($file->getOriginalExtension(), $rule['ext'])) {
                $allow = true;
                break;
            }
        }

        if (!$allow) {
            throw new SdException('上传文件MIME格式错误');
        }
    }


    /**
     * 图片上传
     * @return \think\response\Json
     * @throws SdException
     * @throws \Throwable
     */
    public function imageUpload()
    {
        return $this->localhostUpload($this->fileVerify('limit_image'));
    }

    /**
     * 文件上传
     * @return \think\response\Json
     * @throws SdException
     * @throws \Throwable
     */
    public function fileUpload()
    {
        return $this->localhostUpload($this->fileVerify());
    }


    /**
     * 本地文件存储
     * @param UploadedFile $file
     * @return \think\response\Json
     * @throws SdException
     * @throws \Throwable
     */
    private function localhostUpload($file)
    {
        try {
            // 判断是否已经存在
            $hasFile = Resource::where(['md5' => $file->md5()])->allowEmpty()->find();
            if (!$hasFile->isEmpty() && $this->fileCheck($hasFile->path)) {
                $this->thumbnail($hasFile->path);

                $data = $this->verify_type === 'image' || $this->verify_type === 'video'
                    ? strtr($hasFile->path, ['\\' => '/'])
                    : Arr::only($hasFile->toArray(), ['id', 'tag']);

                return ResponseJson::success($data);
            }

            // 上传到本地服务器
            $save_name = \think\facade\Filesystem::disk(in_array($this->verify_type, ['image', 'video']) ? 'public' : 'file')
                ->putFile( env('UPLOAD_DIR', self::UPLOAD_DIR), $file);

            $this->thumbnail($save_name);

            $data = [
                'tag'  => substr($file->getOriginalName(), 0, 255),
                'path' => strtr($save_name, ['\\' => '/']),
                'md5'  => $file->md5(),
            ];

            if ($hasFile->isEmpty()) {
                $id = Resource::insertGetId(array_merge($data, ['create_time' => datetime(), 'update_time' => datetime()]));
            }else{
                Resource::update($data, ['md5' => $file->md5()]);
                $id = $hasFile->id;
            }

            $data = $this->verify_type === 'image' || $this->verify_type === 'video'
                ? strtr($save_name, ['\\' => '/'])
                : ['id' => $id, 'tag' => $data['tag']];

            return ResponseJson::success($data);
        } catch (\Throwable $e) {
            if (env('APP_DEBUG')) {
                throw $e;
            }
            throw new SdException($e->getMessage());
        }
    }

    /**
     * 文件检测
     * @param string $path
     * @return bool
     */
    private function fileCheck(string $path)
    {
        $root = in_array($this->verify_type, ['image', 'video'])
            ? config('filesystem.disks.public.root')
            : config('filesystem.disks.file.root');

        return file_exists(realpath($root . strtr($path, ['\\' => '/'])));
    }

    /**
     * 生成缩略图
     * @param string $save_name 图片相对根目录路径
     */
    private function thumbnail(string $save_name)
    {
        $file = App::getRootPath() . '/public/' . $save_name;
        if(env('THUMBNAIL') && $this->verify_type === 'image' && file_exists($file)){
            Image::thumbnail($file, 0.5)->compressImg(current(explode('.', $save_name)) . "_thumbnail");
        }
    }


    /**
     * 阿里云文件上传
     * @return \think\response\Json
     * @throws \Exception
     */
    public function AlyUpload()
    {
        try {
            if (empty($_FILES['file'])) {
                return ResponseJson::fail('无上传文件');
            }
            $result = Sc::aLiYunOSS()->uploadFile('file');
        } catch (OssException $e) {
            throw new SdException($e->getErrorMessage());
        }

        return  ResponseJson::success($result);
    }

    /**
     * @return \think\response\Json
     * @throws SdException
     */
    public function base64ImageUpload()
    {
        $resource = request()->post('resource');
        $md5 = md5($resource);

        if (!preg_match('/^(data:\s*image\/(\w+);base64,)/', $resource, $result)) {
            throw new SdException('资源格式错误！');
        }

        $path = implode(DIRECTORY_SEPARATOR, [env('UPLOAD_DIR', self::UPLOAD_DIR), date('Ym'), date('d'), $md5 . '.' . $result[2]]);

        $hasFile = Resource::where(['md5' => $md5])->value('path');

        if ($hasFile) {
            return ResponseJson::success(strtr($hasFile, ['\\' => '/']));
        }

        if (!is_dir(dirname(config('filesystem.disks.public.root') . $path))) {
            mkdir(dirname(config('filesystem.disks.public.root') . $path), 0777, true);
        }


        if (!file_put_contents(config('filesystem.disks.public.root'). $path,
            base64_decode(str_replace($result[1], '', $resource)))){
            throw new SdException('失败！');
        }

        $path = strtr($path, ['\\' => '/']);

        // 记录此文件
        Resource::create([
            'tag' => '',
            'path' => $path,
            'md5' => $md5,
        ]);

        return ResponseJson::success($path);
    }

}
