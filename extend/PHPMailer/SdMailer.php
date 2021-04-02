<?php
/**
 *
 * SdMailer.php
 * User: ChenLong
 * DateTime: 2020/5/8 15:35
 */


namespace PHPMailer;


use PHPMailer\PHPMailer\PHPMailer;
use sdModule\common\Singleton;

/**
 * 邮件常用配置发送
 * Class SdMailer
 * @package PHPMailer
 * @example
 * $result = SdMailer::getInstance()
 * ->setSenderMail('895781173@qq.com', '********')
 * ->setSenderUser('ChenLong')
 * ->useProvider('qq')
 * ->setToAddress('vip_chenlong@163.com')
 * ->send('测试一下', '<h1> I im Chenlong  </h1>');
 */
class SdMailer extends Singleton
{
    private $charset = 'utf8';

    /**
     * @var string 用户
     */
    private $username;

    /**
     * @var string 用户邮箱
     */
    private $email;

    /**
     * @var string 密码
     */
    private $password;

    /**
     * 默认配置
     * @var array[]
     */
    private $provider = [
        '163' => [
            'smtp' => 'smtp.163.com',
            'imap' => 'imap.163.com',
            'smtp_port' => 994,
            'imap_port' => 993,
        ],
        'qq' => [
            'smtp' => 'smtp.qq.com',
            'imap' => 'imap.qq.com',
            'smtp_port' => 465, // 465 | 587
            'imap_port' => 993,
        ],

    ];

    /**
     * @var PHPMailer
     */
    private $mailer;

    /**
     * 设置发送邮件的邮箱
     * @param string $email
     * @param string $password
     * @return $this
     */
    public function setSenderMail(string $email, string $password): SdMailer
    {
        $this->email = $email;
        $this->password = $password;
        return $this;
    }

    /**
     * 设置发送邮件的用户
     * @param string $username
     * @return $this
     */
    public function setSenderUser(string $username): SdMailer
    {
        $this->username = $username;
        return $this;
    }

    /**
     * 使用内置的服务配置
     * @param string $provider qq | 163
     * @param string|null $charset
     * @return $this
     */
    public function useProvider(string $provider, ?string $charset = null): SdMailer
    {
        return $this->provider($this->provider[$provider]['smtp'], $this->provider[$provider]['smtp_port'], $charset);
    }

    /**
     * 设置编码方式
     * @param string|null $charset
     * @return $this
     */
    private function charset(string $charset = null): SdMailer
    {
        if ($charset) {
            $this->mailer->CharSet = $charset;
        }
        return $this;
    }

    /**
     * @param string $smtp
     * @param int $smtp_port
     * @param null $charset
     * @return $this
     * @throws PHPMailer\Exception
     */
    public function provider(string $smtp, int $smtp_port, $charset = null)
    {
        $this->charset($charset);
        $this->mailer->setFrom($this->email, $this->username);

        $this->mailer->Host         = $smtp;
        $this->mailer->SMTPAuth     = true;
        $this->mailer->Username     = $this->email;
        $this->mailer->Password     = $this->password;
        $this->mailer->SMTPSecure   = "ssl";
        $this->mailer->Port         = $smtp_port;

        return $this;
    }

    /**
     * 设置发送到达地址
     * @param        $email
     * @param string $name
     * @return $this
     * @throws PHPMailer\Exception|\Exception
     */
    public function setToAddress($email, $name = ''): SdMailer
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

    /**
     * 获取
     * @return PHPMailer
     */
    public function getPHPMailer(): PHPMailer
    {
        return $this->mailer;
    }

    protected function init()
    {
        $this->mailer = new PHPMailer(true);
        $this->mailer->CharSet = $this->charset;
    }
}

