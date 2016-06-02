<?php

class MailsService{

	private $mail;

	public function __construct(){
		require_once(APP_PATH."/services/PHPMailer/PHPMailerAutoload.php");

		$this->mail = new PHPMailer;
	}

	public function send(){
		$this->mail->Host = 'smtp.qq.com';  // Specify main and backup SMTP servers
		$this->mail->SMTPAuth = true;                               // Enable SMTP authentication
		$this->mail->Username = 'admin@wecall.me';                 // SMTP username
		$this->mail->Password = 'Zxcvb@12345';                           // SMTP password
		$this->mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
		$this->mail->Port = 25;                                    // TCP port to connect to

		$this->mail->setFrom('help@wecall.me', 'Mailer');
		$this->mail->addAddress('416994628@qq.com', '416994628');     // Add a recipient
		$this->mail->addReplyTo('admin@wecall.me', 'replayToAdmin');
		// $this->mail->addCC('cc@example.com');
		// $this->mail->addBCC('bcc@example.com');

		// $this->mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
		// $this->mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name
		$this->mail->isHTML(true);                                  // Set email format to HTML

		$this->mail->Subject = 'Here is the subject';
		$this->mail->Body    = 'This is the HTML message body <b>in bold!</b>';
		$this->mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

		if(!$this->mail->send()) {
		    echo 'Message could not be sent.';
		    echo 'Mailer Error: ' . $this->mail->ErrorInfo;
		} else {
		    echo 'Message has been sent';
		}
	}
}