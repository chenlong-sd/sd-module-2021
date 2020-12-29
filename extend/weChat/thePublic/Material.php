<?php


namespace weChat\thePublic;
use weChat\common\Helper;
use weChat\common\Config;
use weChat\common\AccessToken;

/**
 * 素材管理
 * Class Material
 * @package app\common\wechat
 */
class Material
{
    const MATERIAL_IMAGE = 'image';
    const MATERIAL_VIDEO = 'video';
    const MATERIAL_VOICE = 'voice';
    const MATERIAL_THUMB = 'thumb';

    /**
     * 临时素材上传
     * @param string $url   素材路径
     * @param string $type  素材类型， 可用类型 image | voice | video | thumb
     * @return mixed
     */
    public function uploadMedia($url, $type = self::MATERIAL_IMAGE)
    {
        $uploadUrl = 'https://api.weixin.qq.com/cgi-bin/media/upload?access_token=%s&type=%s';
        $uploadUrl = sprintf($uploadUrl, AccessToken::getAccessToken(), $type);

        $suffix = explode('.', $url);
        $suffix = strtolower(end($suffix));

        $mime = $suffix == 'gif' ? 'image/gif' : 'image/jpeg';
        if ($type == 'voice') {
            $mime = 'audio/mpeg';
        }elseif ($type == 'video'){
            $mime = 'video/mpeg';
        }
        $result = Helper::postRequest($uploadUrl, ['media' => new \CURLFile(realpath($url))], true);

        return $result;
    }


    /**
     * 获取临时素材
     * @param $mediaId
     * @return bool|mixed|string    图片成功后直接是图片资源，设置header 的contentType:image/jpeg
     */
    public function getMedia($mediaId)
    {
        $getUrl = 'https://api.weixin.qq.com/cgi-bin/media/get?access_token=%s&media_id=%s';
        $getUrl = sprintf($getUrl, AccessToken::getAccessToken(), $mediaId);

        return Helper::getRequest($getUrl, true);
    }


    /**
     * 上传永久图文素材
     * @param array|string      $data    图文资源
     * @return mixed
     */
    public function uploadNews($data)
    {
        $newsUrl = 'https://api.weixin.qq.com/cgi-bin/material/add_news?access_token=' . AccessToken::getAccessToken();
        return Helper::postRequest($newsUrl, ['articles' => $data]);
    }

    /**
     * 上传永久图文素材的图片ID
     * @param $imageUrl
     * @return mixed
     */
    public function uploadNewsImage($imageUrl)
    {
        $requestUrl = 'https://api.weixin.qq.com/cgi-bin/media/uploadimg?access_token=' .  AccessToken::getAccessToken();
        return Helper::postRequest($requestUrl, ['media' => new \CURLFile(realpath($imageUrl))], true);
    }

    /**
     * 上传永久素材
     * @param string|array  $url   素材地址（绝对地址,视频的时候包括description的数据）
     * @param string        $type   素材类型， 可用类型 image | voice | video | thumb
     * @return mixed
     */
    public function uploadMaterial($url, $type  = self::MATERIAL_IMAGE)
    {
        $uploadUrl = 'https://api.weixin.qq.com/cgi-bin/material/add_material?access_token=%s&type=%s';
        $uploadUrl = sprintf($uploadUrl, AccessToken::getAccessToken(), $type);

//        是视频上传的时候
        if ($type == 'video') {
            $data = [
                'media' => new \CURLFile(realpath($url['video'])),
                'description' => json_encode($url['description'], JSON_UNESCAPED_UNICODE)
            ];
        }else{
            $data = ['media' => new \CURLFile(realpath($url))];
        }

        return Helper::postRequest($uploadUrl, $data, true);
    }

    /**
     * 删除永久素材，成功返回true 否则返回原数据
     * @param string $media_id 永久素材的media_id
     * @return bool|mixed
     */
    public function delMaterial($media_id)
    {
        $delUrl = 'https://api.weixin.qq.com/cgi-bin/material/del_material?access_token=' . AccessToken::getAccessToken();

        $data = ['media_id' => $media_id];

        $result = Helper::postRequest($delUrl, $data);

        return $result['errcode'] == 0 ? true : $result;
    }

    /**
     * 获取永久素材
     * @param string $media_id 永久素材的media_id
     * @return mixed 素材信息或错误代码
     */
    public function getMaterial($media_id)
    {
        $url = 'https://api.weixin.qq.com/cgi-bin/material/get_material?access_token=' . AccessToken::getAccessToken();

        $data = ['media_id' => $media_id];

        $result = Helper::postResource($url, $data);

        return empty($result['errcode']) ? $result : $result['errcode'];
    }

    /**
     * 获取素材列表
     * @param string $type  素材类型：image | video | voice | news
     * @param int    $page  列表页数
     * @param int    $limit 每页条数,值大于 0
     * @return mixed
     */
    public function getMaterialList($type = 'image', int $page = 1, int $limit = 20)
    {
        $url = 'https://api.weixin.qq.com/cgi-bin/material/batchget_material?access_token=' . AccessToken::getAccessToken();
        $data = [
            'type' => $type,
            'offset' => ($page - 1) * $limit,
            'count' => $limit
        ];
        $result = Helper::postRequest($url, $data);

        return empty($result['errcode']) ? $result : $result['errcode'];
    }

    /**
     * 根据OpenID列表群发【订阅号不可用，服务号认证后可用】
     * @param $openidGroup  array   用户组
     * @param $data string|array    数据，一般为media_id,或者文字内容
     * @param $type string          类型支持： mpnews | image | voice | text | wxcard  | mpvideo
     * @return mixed
     * @throws \Exception
     */
    public function sendOpenidGroup($openidGroup, $data, $type)
    {
        $sendUrl = 'https://api.weixin.qq.com/cgi-bin/message/mass/send?access_token=' . AccessToken::getAccessToken();

        $typeKey = [
            'mpnews' => 'media_id', // 图文
            'image' => 'media_id',  // 图片
            'voice' => 'media_id',  // 音频
            'text' => 'content',    // 文字
            'wxcard' => 'card_id',  // 卡券
        ];

        $typeJudge = array_keys($typeKey);
        $typeJudge[] = 'mpvideo';

//      类型不对，抛出一个异常
        if (!in_array($type, $typeJudge)) {
            throw new \Exception('暂不支持该类型群发，请选择以下类型 mpnews | image | voice | text | wxcard  | mpvideo ');
        }

        $sendData = [
            'touser' => $openidGroup,
            'msgtype' => $type,
            $type => $type == 'mpvideo' ? $data : [$typeKey[$type] => $data]
        ];

        if ($type == 'mpnews') {
            $sendData['send_ignore_reprint'] = 0;
        }

        return Helper::postRequest($sendUrl, $sendData);
    }
}

