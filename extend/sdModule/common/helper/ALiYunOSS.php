<?php


namespace sdModule\common\helper;


use OSS\Core\OssException;
use OSS\OssClient;

/**
 * Class ALiYunOSS
 * @package sdModule\common\helper
 */
class ALiYunOSS
{
    private string $accessKeyId = "";
    private string $accessKeySecret = "";
    private string $endpoint = "";

    private string $bucket = "";
    private string $dir = "";

    /**
     * @var OssClient
     */
    private OssClient $ossClient;

    /**
     * ALiYunOSS constructor.
     * @throws OssException
     */
    public function __construct()
    {
        $this->config();
        $this->OssClient();
        $this->createBucket();
    }

    /**
     * 基本配置
     */
    private function config()
    {
        $this->accessKeyId = env('A_LI_YUN_OSS.ACCESS_KEY_ID', '');
        $this->accessKeySecret = env('A_LI_YUN_OSS.ACCESS_KEY_SECRET', '');
        $this->endpoint = env('A_LI_YUN_OSS.ENDPOINT', '');
        $this->bucket = env('A_LI_YUN_OSS.BUCKET', '');
        $this->dir = env('A_LI_YUN_OSS.DIR', '');
    }

    /**
     * 初始化OSS
     * @return OssClient
     * @throws OssException
     */
    private function OssClient()
    {
        if (!$this->ossClient) {
            $this->ossClient = new OssClient($this->accessKeyId, $this->accessKeySecret, $this->endpoint);
        }
        return $this->ossClient;
    }

    /**
     * 创建存储桶
     * @throws \Exception
     */
    private function createBucket()
    {
        try {
            // 设置存储空间的存储类型为低频访问类型，默认是标准类型。
            $options = array(
                OssClient::OSS_STORAGE => OssClient::OSS_STORAGE_IA
            );
            $res = $this->ossClient->doesBucketExist($this->bucket);
            if ($res !== true) {
                // 设置存储空间的权限为公共读，默认是私有读写。
                $this->ossClient->createBucket($this->bucket, OssClient::OSS_ACL_TYPE_PUBLIC_READ, $options);
            }
        } catch (OssException $e) {
            throw new \Exception($e->getMessage());
        }
    }


    /**
     * 上传文件
     * @param string $file    文件李路径或上传的name值
     * @param bool $is_upload   是否是上传
     * @return string
     * @throws OssException
     */
    public function uploadFile(string $file, $is_upload = true)
    {
        if ($is_upload) {
            $Suffix = substr($_FILES[$file]['name'], strrpos($_FILES[$file]['name'], '.'));
            $object = $this->dir . md5_file($_FILES[$file]['tmp_name']) . $Suffix;
            $filePath = $_FILES[$file]['tmp_name'];
        }else{
            $object = $this->dir . md5_file($file);
            $filePath = $file;
        }
        $result = $this->ossClient->uploadFile($this->bucket, $object, $filePath);

        return is_array($result) ? $result['oss-request-url'] : '';
    }


}

