<?php
/**
 *邮件 操作类
 *
 *
 * @author ron_chen<ron_chen@hotmail.com>
 * @copyright ron_chen<ron_chen@hotmail.com>
 * @link http://www.ronchen.me/
 */
class MailsService{

	private $mail;
	private $config;
	private $channel;

	public function __construct($channel='qq.exmail'){
		require_once(APP_PATH."/services/PHPMailer/PHPMailerAutoload.php");

		$this->mail = new PHPMailer;
		$this->mail->isSMTP();
		$this->mail->SMTPDebug = 0;
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
		$this->mail->Hostname = $this->config["hostname"];
		// Enable SMTP authentication
		$this->mail->SMTPAuth = true;
		//设置发送的邮件的编码 
		$this->mail->CharSet = 'UTF-8';
		// 邮件服务器用户
		$this->mail->Username = $this->config["username"];
		// 邮件服务器密码
		$this->mail->Password = $this->config["password"];
		// 安全协议
		$this->mail->SMTPSecure = isset($this->config["SMTPSecure"]) ? $this->config["SMTPSecure"] : "tls";
		// 邮件发送人
		$this->mail->setFrom($this->config["fromMailer"],$this->config["fromName"]);
		$this->mail->addReplyTo($this->config["replyTo"],$this->config["fromName"]);
	}

	/**
	 * 检测当前SMTP是否可用
	 */
	public function checkSmtp(){
		$smtp = new SMTP;
		$smtp->do_debug = SMTP::DEBUG_CONNECTION;
		
		$this->config  = config($this->channel);

		try {
		    //Connect to an SMTP server
		    if (!$smtp->connect($this->config["host"], 25)) {
		        throw new Exception('连接失败');
		    }
		    //Say hello
		    if (!$smtp->hello(gethostname())) {
		        throw new Exception('握手失败:' . $smtp->getError()['error']);
		    }
		    //Get the list of ESMTP services the server offers
		    $e = $smtp->getServerExtList();
		    
		    //If server can do TLS encryption, use it
		    if (array_key_exists('STARTTLS', $e)) {
		        $tlsok = $smtp->startTLS();
		        if (!$tlsok) {
		            throw new Exception('Failed to start encryption: ' . $smtp->getError()['error']);
		        }
		        //Repeat EHLO after STARTTLS
		        if (!$smtp->hello(gethostname())) {
		            throw new Exception('EHLO (2) failed: ' . $smtp->getError()['error']);
		        }
		        //Get new capabilities list, which will usually now include AUTH if it didn't before
		        $e = $smtp->getServerExtList();
		    }
		    //If server supports authentication, do it (even if no encryption)
		    if (array_key_exists('AUTH', $e)) {
		        if ($smtp->authenticate($this->config["username"], $this->config["password"])) {
		            echo "连接成功";
		        } else {
		            throw new Exception('验证失败:' . $smtp->getError()['error']);
		        }
		    }
		} catch (Exception $e) {
		    echo '发送邮件服务器初始化失败: ' . $e->getMessage(), "\n";
		}
		//Whatever happened, close the connection.
		$smtp->quit(true);
	}

	/**
	 * 单个地址发送
	 * @address  收件人地址
	 * @subject  邮件主题
	 * @content  内容
	 * @attachments 附件存放地址
	 */
	public function send($address,$subject="",$content="",$attachments = array()){
		
		$this->setting();

		$this->mail->addAddress($address);
		
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