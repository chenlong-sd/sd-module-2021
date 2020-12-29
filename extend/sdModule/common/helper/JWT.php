<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/2/28
 * Time: 10:35
 */

namespace sdModule\common\helper;
use think\helper\Str;

/**
 * Class JWT
 * @example
 *         JWT::getToken($var); 直接获取token ，$var 为自己的参数可不传
 *         JWT::getRefresh(7, $var)::getToken($var);需要获取refreshToken参数时
 *         JWT::setExp(86400)::getToken($var); 设置过期时间
 * @package app\interactive\controller
 */

class JWT
{
    /** @var string 秘钥 */
    const SECRET = 'SD_CL-CS_LOVE**KK';

    /** @var string token类型 */
    const TYPE = 'JWT';

    /** @var string 加密算法类型 */
    const ALG = 'sha256';

    /** @var string 更新token的秘钥 */
    const REFRESH = 'SD_LO_VE_CL_CS';

    /** @var int 加密串前置随机子串 */
    const START_LEN = 3;

    /** @var string 加密串插入子串 */
    const MIDDLE_LEN = "8:3";

    /** @var int 加密串后置随机子串 */
    const END_LEN = 3;

    /**
     * token的基本数据
     * @var array
     */
    private array $payload = [];

    /**
     * @var array refreshToken 数据
     */
    private array $refresh = [];

    /**
     * 携带的数据
     * @var array
     */
    private array $data;

    /**
     * JWT constructor.
     * @param array $data 载荷，有效信息
     * @example
     *        以下为data的默认参数，可以有额外参数
     *           $data = [
     *              'iss' => 'jwt_admin',               // 签发者
     *              'iat' => time(),                    // 签发时间
     *              'exp' => time()+7200,               //  jwt的过期时间，这个过期时间必须要大于签发时间
     *              'nbf' => time()+60,                 // 定义在什么时间之前，该jwt都是不可用的
     *              'sub' => 'www.admin.com',           // 主题
     *              'aud' => 'www.admin.com',           //  接收jwt的一方
     *              'jti' => md5(uniqid('JWT').time())  // 该Token唯一标识
     *              'rsh' => 'asdasd'                   // 需要刷新操作的时候,refreshToken的唯一标识jti值，必须
     *           ]
     */
    public function __construct(array $data = [])
    {
        $this->data = $data;

        // 生成签发时间，生效时间，过期时间,唯一标识
        $this->setIat()->setNbf()->setExp()->setIss()->setJti();
    }


    /**
     * 获取token
     * @return array
     */
    public function getToken()
    {
        $payload = self::base64UrlEncode(array_merge($this->payload, $this->data));
        $header  = self::getHeader();
        $sign    = hash_hmac(self::ALG, $header . '.' . $payload, env('JWT.JWT_SECRET', self::SECRET));
        $token   = implode('.', [$header, $payload, $this->strInject($sign)]);

        $tokenData = [
            'token' => $token,
            'token_exp' => $this->payload['exp'] ?? 0
        ];
        return array_merge($tokenData, $this->refresh);
    }

    /**
     * 获取刷新token 的 refreshToken，当token过期的时候可以用此refreshToken来刷新token
     * 返回refreshToken 以及他的唯一标识 jti
     * @param int|bool $exp 过期时间（单位：天）
     * @param array $fill_data 额外参数
     * @return self
     */
    public function getRefresh($exp = 30, $fill_data = [])
    {
        $data = [
            'exp' => $_SERVER['REQUEST_TIME'] + 60 * 60 * 24 * $exp,
            'jti' => md5(uniqid(env('JWT.JWT_SECRET',self::REFRESH)) . mt_rand(0, 99))
        ];

        $data = array_merge($data, $fill_data);
        // 加密
        $base64UrlEncode = self::base64UrlEncode($data);
        //        签名
        $sign = hash_hmac(self::ALG, $base64UrlEncode, env('JWT.JWT_SECRET',self::REFRESH));

        $refreshToken = $base64UrlEncode . '.' . self::base64UrlEncode($sign);

        $this->refresh = [
            'refresh_token' => $refreshToken,
            'refresh_token_exp' => $data['exp']
        ];

        $this->payload['rsh'] = $data['jti'];
        return $this;
    }

    /**
     * 刷新token并返回新的token
     * @param string $refreshToken 刷新token 需要用到的refreshToken参数
     * @param string $token 原token
     * @return array
     * @throws \Exception
     */
    public function refreshToken($refreshToken, $token)
    {
        $refreshToken = explode('.', $refreshToken);
        $token_payload = $this->verify($token, false);

        // 数据格式不对 或 token 验证失败
        if (count($refreshToken) != 2 || empty($token_payload)) {
            throw new \Exception(lang('token error') . 'RT1101');
        }

        list($data, $sign) = $refreshToken;
        $data = json_decode(self::base64UrlDecode($data), true);

        // 已超时
        if (empty($data['exp']) || $data['exp'] < $_SERVER['REQUEST_TIME']) {
            throw new \Exception(lang('token error') . 'RT1102');
        }
        // refreshToken的唯一ID和当前的token对不上
        if (empty($data['jti']) || empty($token_payload['rsh']) || $data['jti'] != $token_payload['rsh']) {
            throw new \Exception(lang('token error') . 'RT1103');
        }
        $refreshSign = hash_hmac(self::ALG, self::base64UrlEncode($data), env('JWT.JWT_SECRET',self::REFRESH));

        // 签名不对
        if (self::base64UrlEncode($refreshSign) != $sign) {
            throw new \Exception(lang('token error') . 'RT1104');
        }

        unset($token_payload['iat'],$token_payload['exp'],$token_payload['nbf']);
        if ($this->refresh) {
            unset($token_payload['rsh']);
        }

        $this->data = $token_payload;
        return $this->getToken();
    }

    /**
     * token 验证外部接口
     * @param string $token token值
     * @param bool  $time_verify 时间验证
     * @return mixed
     * @throws \Exception
     */
    public function tokenVerify(string $token = '', $time_verify = true)
    {
        return $this->verify($token, $time_verify);
    }

    /**
     * 设置签发时间
     * @param int $time
     * @return JWT
     */
    public function setIat(int $time = 0)
    {
        if(empty($time)){
            $this->payload['iat'] = $this->payload['iat'] ?? $_SERVER['REQUEST_TIME'];
        }else{
            $this->payload['iat'] = $_SERVER['REQUEST_TIME'] + $time;
        }
        return $this;
    }

    /**
     * 设置过期时间
     * @param int $time 单位秒,设置null则不过期
     * @return JWT
     */
    public function setExp(int $time = 0)
    {
        if ($time ===  null) {
            $this->payload['exp'] = null;
        }else if (empty($time)){
            $exp = ($this->payload['iat'] ?? $_SERVER['REQUEST_TIME']) + 60;
            $this->payload['exp'] = $this->payload['exp'] ?? $exp;
        }else{
            $this->payload['exp'] = $_SERVER['REQUEST_TIME'] + $time;
        }
        return $this;
    }

    /**
     * 设置生效时间
     * @param int $time
     * @return JWT
     */
    public function setNbf(int $time = 0)
    {
        if (!empty($time)) {
            $this->payload['nbf'] = $time;
        }
        return $this;
    }

    /**
     * 设置token唯一标识
     * @param string $jti
     * @return JWT
     */
    public function setJti($jti = '')
    {
        if (empty($jti)){
            $this->payload['jti'] = $this->payload['jti'] ?? uniqid('jti') . mt_rand(0, 99);
        }else{
            $this->payload['jti'] = $jti;
        }
        return $this;
    }

    /**
     * 设置签发者
     * @param string $iss
     * @return JWT
     */
    public function setIss($iss = '')
    {
        if(empty($iss)){
            $this->payload['iss'] = $this->payload['iss'] ?? 'SD_CL';
        }else{
            $this->payload['iss'] = $iss;
        }
        return $this;
    }

    /**
     * token验证并返回payload数据
     * @param $token
     * @param bool $time_verify 是否验证时效性
     * @return mixed
     * @throws \Exception
     */
    private function verify($token, $time_verify = true)
    {
        $data = explode('.', $token);

        //  格式对不上，失败
        if (count($data) != 3) {
            throw new \Exception(lang('token error') . 'T01');
        }

        list($header, $payload, $sign) = $data;
        $header  = json_decode(self::base64UrlDecode($header), true);
        $payload = json_decode(self::base64UrlDecode($payload), true);

        // 数据对不上， 失败
        if (!$header || empty($header['alg']) || !$payload){
            throw new \Exception(lang('token error') . 'T02');
        }

        // 未达到使用时间
        if (!empty($payload['nbf']) && $payload['nbf'] > $_SERVER['REQUEST_TIME'] && $time_verify) {
            throw new \Exception(lang('token error') . 'T03');
        }

        // 时间已过期
        if (!empty($payload['exp']) && $payload['exp'] < $_SERVER['REQUEST_TIME'] && $time_verify){
            throw new \Exception(lang('token error') . 'T04');
        }

        $sign_base = hash_hmac($header['alg'], self::base64UrlEncode($header) . '.' . self::base64UrlEncode($payload), env('JWT.JWT_SECRET', self::SECRET));

        // 和我们自己的签名对不上
        if (self::base64UrlEncode($sign_base) !== $this->strDetach($sign)){
            throw new \Exception(lang('token error') . 'T05');
        }

        return $payload;
    }
    /**
     * 获取头部信息
     * @return string
     */
    private function getHeader()
    {
        $header = [
            'alg' => self::ALG,
            'typ' => self::TYPE
        ];

        return self::base64UrlEncode($header);
    }

    /**
     * 对数据进行 base64Url 加密
     * @param array|string $data
     * @return string
     */
    private static function base64UrlEncode($data)
    {
        if (is_array($data)) {
            $data = json_encode($data, JSON_UNESCAPED_UNICODE);
        }
        $base64 = base64_encode($data);

        return strtr(rtrim($base64, '='), ['/' => '_', '+' => '-']);
    }

    /**
     * baseUrl 解密
     * @param string $data
     * @return bool|string
     */
    private static function base64UrlDecode(string $data)
    {
        $data   = $data . str_repeat('=', strlen($data) % 4);
        $base64 = strtr($data, ['_' => '/', '-' => '+']);
        return base64_decode($base64);
    }

    /**
     * 签名字符串注入
     * @param string $sign
     * @return string
     */
    private function strInject(string $sign)
    {
        $base64_sign = self::base64UrlEncode($sign);
        $start_len   = env('JWT.START_LEN', self::START_LEN);
        $end_len     = env('JWT.END_LEN', self::END_LEN);
        $middle_len  = env('JWT.MIDDLE_LEN', self::MIDDLE_LEN);
        list($middle_start, $middle_len) = explode(':', $middle_len);
        $base64_sign = substr_replace($base64_sign,  Str::random($middle_len), $middle_start, 0);

        return implode([Str::random($start_len), $base64_sign, Str::random($end_len)]);
    }

    /**
     * 签名字符串分离
     * @param string $str
     * @return string|string[]
     */
    private function strDetach(string $str)
    {
        $start_len   = env('JWT.START_LEN', self::START_LEN);
        $end_len     = env('JWT.END_LEN', self::END_LEN);
        $middle_len  = env('JWT.MIDDLE_LEN', self::MIDDLE_LEN);
        list($middle_start, $middle_len) = explode(':', $middle_len);
        $str         = substr(substr($str, $start_len), 0, -$end_len);
        return substr_replace($str,  '', $middle_start, $middle_len);
    }
}
