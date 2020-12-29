<?php
/**
 * Date: 2020/8/7 11:30
 * User: chenlong <vip_chenlong@163.com>
 */

namespace sdModule\image;

use sdModule\common\StaticCallGetInstance;
use sdModule\image\library\Thumbnail;
use sdModule\image\library\Watermark;

/**
 * 图片处理 GD库
 * Class Image
 * @method static Thumbnail thumbnail(string $origin_src, $percent)
 * origin_src 源图路径, percent 缩放比例，源图为 1
 * @method static Watermark watermark(string $origin_image, string $text)
 * origin_image 来源图片路径，支持可访问的网络路径, text 水印文字
 * @package sdModule\image
 */
class Image extends StaticCallGetInstance
{
    protected function getNamespace(): string
    {
        return "sdModule\\image\\library\\";
    }

}
