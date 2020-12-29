<?php
/**
 *
 * SdMailer.php
 * User: ChenLong
 * DateTime: 2020/5/8 15:35
 */


namespace PHPMailer;


use PHPMailer\PHPMailer\PHPMailer;

/**
 * 邮件常用配置发送
 * Class SdMailer
 * @package PHPMailer
 * @example
 *          SdMailer::getInstance('ChenLong', 'vip_chenlong@163.com', '*******')
 *              ->wy163Provider()
 *              ->setAddress('_____@qq.com', 'nihao ')
 *              ->send('Ke you NASO NONONO BOBOBO NOS', <<<SSS
 *      <div>
 *          <h1>系统维护中！</h1>
 *      </div>
 * SSS
 * );
 */
class SdMailer
{
    private $charset = 'utf8';

    /**
     * @var bool 是否是海外
     */
    private $is_overseas = false;

    /**
     * @var string 用户
     */
    private $user;

    /**
     * @var string 用户邮箱
     */
    private $mail;

    /**
     * @var string 密码
     */
    private $password;


    private $config = [
        '163' => [
            'smtp' => 'smtp.163.com',
            'imap' => 'imap.163.com',
            'smtp_port' => 994,
            'imap_port' => 993,
        ],
        'qq' => [
            'smtp' => 'smtp.exmail.qq.com',
            'imap' => 'imap.exmail.qq.com ',
            'hwsmtp' => 'hwsmtp.exmail.qq.com',
            'hwimap' => 'hwimap.exmail.qq.com',
            'smtp_port' => 465,
            'imap_port' => 993,
        ],

    ];

    private static $instance;

    /**
     * @var PHPMailer
     */
    private $mailer;

    /**
     * 获取类实例
     * @param string $user
     * @param string $mail
     * @param string $password
     * @return SdMailer
     */
    public static function getInstance(string $user, string $mail, string $password)
    {
        if (!self::$instance) {
            self::$instance = new self();
            self::$instance->mailer = new PHPMailer(true);
        }

        self::$instance->user = $user;
        self::$instance->mail = $mail;
        self::$instance->password = $password;

        return self::$instance;
    }


    /**
     * 网易163服务
     * @param array $config
     * @param null  $charset
     * @return SdMailer
     * @throws \Exception
     */
    public function wy163Provider(array $config = [], $charset = null)
    {
        return $this->provider(array_merge($this->config['163'], $config), $charset);
    }

    /**
     * 海外
     * @return $this
     */
    public function overseas()
    {
        $this->is_overseas = true;
        return $this;
    }

    /**
     * 设置编码方式
     * @param string|null $charset
     * @return $this
     */
    private function charset(string $charset = null)
    {
        $charset and $this->charset = $charset;
        return $this;
    }

    /**
     * @param array $config
     * @param null  $charset
     * @return $this
     * @throws PHPMailer\Exception|\Exception
     */
    public function provider($config = [], $charset = null)
    {
        $this->charset($charset);

        $this->mailer->CharSet = $this->charset;
        $this->mailer->Host = $this->getSMTP($config);// 发送方的SMTP服务器地址
        $this->mailer->SMTPAuth = true;// 是否使用身份验证
        $this->mailer->Username = $this->mail;// 发送方的163邮箱用户名
        $this->mailer->Password = $this->password;// 发送方的邮箱密码，注意用163邮箱这里填写的是“客户端授权密码”而不是邮箱的登录密码！
        $this->mailer->SMTPSecure = "ssl";// 使用ssl协议方式
        $this->mailer->setFrom($this->mail, $this->user);
        $this->mailer->Port = $config['smtp_port'];

        return $this;
    }

    /**
     * 设置发送到达地址
     * @param        $email
     * @param string $name
     * @return $this
     * @throws PHPMailer\Exception|\Exception
     */
    public function setAddress($email, $name = '')
    {
        $this->mailer->addAddress($email, $name);
        return $this;
    }

    /**
     *  设置回复地址
     * @param        $email
     * @param string $name
     * @return $this
     * @throws PHPMailer\Exception|\Exception
     */
    public function setReplyAddress($email, $name = '')
    {
        $this->mailer->addReplyTo($email, $name);
        return $this;
    }

    /**
     * 发送
     * @param string $title
     * @param string $content
     * @return bool|string
     */
    public function send(string $title, string $content)
    {
        $this->mailer->Subject = $title;
        $this->mailer->Body = $content;
        $this->mailer->isHTML(true);
        $this->mailer->isSMTP();

        try {
            return $this->mailer->send();
        } catch (\Exception $exception) {
            return $exception->getMessage();
        }
    }

    public function getPHPMailer()
    {
        return $this->mailer;
    }


    /**
     * 获取smtp服务器
     * @param array $config
     * @return mixed
     */
    private function getSMTP(array $config)
    {
        return $this->is_overseas && !empty($config['hwsmtp']) ? $config['hwsmtp'] : $config['smtp'];
    }


    private function providerInstance(array $config)
    {


        return 11;
    }
}

