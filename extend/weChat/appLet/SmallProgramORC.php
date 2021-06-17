<?php
/**
 * 小程序的OCR识别
 * SmallProgram.php
 * User: ChenLong
 * DateTime: 2020/1/17 14:17
 */


namespace weChat\appLet;
use weChat\common\Helper;
use weChat\common\Config;
use weChat\common\AccessToken;

/**
 * Class SmallProgramORC
 * @package app\common\wechat
 */
class SmallProgramORC
{
//    图片识别模式
    const TYPE_PHOTO = 'photo';
    const TYPE_SCAN = 'scan';

//    OCR 类型
    const OCR_ID_CARD = 'idcard';
    const OCR_VEHICLE_LICENSE = 'driving';
    const OCR_BANK_CARD = 'bankcard';

    /**
     *  OCR 类型
     * @var string
     */
    private $ocrType = '';

//  请求路径标本
    private $requestUrl = 'https://api.weixin.qq.com/cv/ocr/%s?type=%s%s&access_token=%s';

    /**
     * SmallProgramORC constructor.
     * @param string $ocrType
     */
    public function __construct(string $ocrType = self::OCR_ID_CARD)
    {
        $this->ocrType = $ocrType;
    }

    /**
     * @param string|\CURLFile  $file   文件网络路径|\CURLFile对象
     * @param string            $type   识别模式：photo|scan
     * @return mixed
     */
    public function OCR($file, $type = self::TYPE_PHOTO)
    {
        $imageUrl   = is_string($file) ? "&img_url=" . urlencode($file) : '';
        $requestUrl = sprintf($this->requestUrl, $this->ocrType, $type, urlencode($imageUrl), AccessToken::getAccessToken());

        return Helper::postRequest($requestUrl, $imageUrl ?: ['img' => $file], true);
    }
}

