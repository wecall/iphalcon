<?php

class MailsService{

	private $mail;
	private $config;
	private $channel;

	public function __construct($channel='qq.exmail'){
		require_once(APP_PATH."/services/PHPMailer/PHPMailerAutoload.php");

		$this->mail = new PHPMailer;
		// debug 
		$this->mail->SMTPDebug = 3;
		$this->channel = sprintf("email.%s",$channel);
	}

	/**
	 * 邮件服务基本设置
	 */
	private function setting(){
		$this->config  = config($this->channel);
		
		// 发邮件服务器
		$this->mail->Host = $this->config["host"];
		// 端口
		$this->mail->Port = $this->config["port"];
		// 发件人主机
		$this->mail->Hostname = 'ronchen.me';
		// Enable SMTP authentication
		$this->mail->SMTPAuth = true;
		//设置发送的邮件的编码 
		$this->mail->CharSet = 'UTF-8';
		// 邮件服务器用户
		$this->mail->Username = $this->config["username"];
		// 邮件服务器密码
		$this->mail->Password = $this->config["password"];
		// 安全协议
		$this->mail->SMTPSecure = "ssl";
		// 邮件发送人
		$this->mail->setFrom($this->config["fromMailer"],"来源：");
		$this->mail->addReplyTo($this->config["replyTo"], "回复:");
	}

	/**
	 * 单个地址发送
	 * @address  收件人地址
	 * @subject  邮件主题
	 * @content  内容
	 * @attachments 附件存放地址
	 */
	public function sendmail($address,$subject="",$content="",$attachments = array()){
		
		$this->setting();

		$this->mail->addAddress($address);
		$this->mail->addAddress("839828198@qq.com");
		
		if (count($attachments) > 0) {
			foreach ($attachments as $item_url) {
				$this->mail->addAttachment($item_url);
			}
		}
		$this->mail->isHTML(true);
		$this->mail->Subject = $subject;
		$this->mail->Body    = $content;
		$this->mail->AltBody = $subject;

		if(!$this->mail->send()) {
		    return $this->mail->ErrorInfo;
		} else {
		    return true;
		}
	}
}