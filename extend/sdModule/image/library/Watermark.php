<?php
/**
 * Date: 2020/8/7 10:32
 * User: chenlong <vip_chenlong@163.com>
 */

namespace sdModule\image\library;

/**
 * 图片加水印
 * Class Watermark
 * @package sdModule\image
 */
class Watermark
{
    /**
     * @var false|resource gd库创建的图片资源
     */
    private $image;
    /**
     * @var string 水印文字
     */
    private $text;
    /**
     * @var array 图片大小
     */
    private $imageSize;
    /**
     * @var array 水印文字颜色
     */
    private $textColor = [
        'red' => 255, 'green' => 255, 'blue' => 255
    ];
    /**
     * @var string 字体文件路径
     */
    private $font;
    /**
     * @var int 水印字体大小
     */
    private $textSize = 10;
    /**
     * @var array 坐标
     */
    private $coordinate = [
        'x' => -100,
        'y' => -10,
    ];

    /**
     * @var string
     */
    private $mime;

    /**
     * 初始化
     * Watermark constructor.
     * @param string $origin_img 来源图片路径，支持可访问的网络路径
     * @param string $text 水印文字
     */
    public function __construct(string $origin_img, string $text)
    {
        $this->image = imagecreatefromstring(file_get_contents($origin_img));
        $this->text = $text;
        list($width, $height, $type, $attr) = getimagesize($origin_img);
        $this->imageSize = compact('width', 'height', 'type', 'attr');

        $this->setMime($origin_img);
        $this->font = dirname(__DIR__) . '/font/xk.ttf';
    }

    /**
     * 设置mime
     * @param $originImg
     */
    private function setMime($originImg)
    {
        if (preg_match('/^http/', $originImg)){
            $c = curl_init();
            curl_setopt($c, CURLOPT_URL, $originImg);
            curl_setopt($c, CURLOPT_HEADER, true);
            curl_setopt($c, CURLOPT_NOBODY, true);
            curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
            $s = curl_exec($c);
            curl_close($c);
            $arr = explode("\r\n", $s);
            foreach ($arr as $item) {
                if (strpos($item, 'Content-Type:') !== false){
                    $this->mime = trim(strtr($item, ['Content-Type:' => '']));
                    break;
                }
            }
        }else{
            $finfo = new \finfo(FILEINFO_MIME);
            $this->mime = $finfo->file($originImg, FILEINFO_MIME_TYPE);
        }
    }

    /**
     * 设置字体颜色 rgb
     * @param int $red
     * @param int $green
     * @param int $blue
     * @return $this
     */
    public function setColor($red, $green, $blue)
    {
        $this->textColor = compact('red', 'green', 'blue');
        return $this;
    }

    /**
     * 设置字体大小
     * @param int|float $size
     * @return Watermark
     */
    public function setSize($size)
    {
        $this->textSize = $size;
        return $this;
    }

    /**
     * 设置字体
     * @param string $fontUrl
     * @return $this
     */
    public function setFont(string $fontUrl)
    {
        $this->font = $fontUrl;
        return $this;
    }

    /**
     * 设置水印坐标
     * @param int $x 正数从左计算，负数从右计算
     * @param int $y 正数从上计算，负数从下计算
     * @return $this
     */
    public function setPosition(int $x, int $y)
    {
        $this->coordinate = compact('x', 'y');
        return $this;
    }

    /**
     * 直接输出图片
     * @return false|string
     */
    public function output()
    {
        ob_start();
        $this->handle();
        $this->returnType();
        $content = ob_get_clean();
        imagedestroy($this->image);
        return response()->data($content)->contentType($this->mime);
    }

    /**
     * 保存文件
     * @param string $save_name
     */
    public function save(string $save_name)
    {
        $this->handle();
        $this->returnType($save_name);
        imagedestroy($this->image);
    }

    /**
     * 图片水印合成
     */
    private function handle()
    {
        $color = imagecolorallocate($this->image, $this->textColor['red'], $this->textColor['green'], $this->textColor['blue']);
        $x = $this->coordinate['x'] > 0 ? $this->coordinate['x'] : $this->imageSize['width'] + $this->coordinate['x'];
        $y = $this->coordinate['y'] > 0 ? $this->coordinate['y'] : $this->imageSize['height'] + $this->coordinate['y'];
        imagefttext($this->image, $this->textSize, 0, $x, $y, $color, $this->font, $this->text);
    }

    /**
     * 返回类型
     * @param null $save_name
     */
    private function returnType($save_name = null)
    {
        switch ($this->imageSize['type']) {
            case 1:
                imagegif($this->image, $save_name);
                break;
            case 2:
                imagejpeg($this->image, $save_name);
                break;
            case 3:
                imagepng($this->image, $save_name);
                break;
            case 6:
                imagewbmp($this->image, $save_name);
                break;
        }
    }
}
