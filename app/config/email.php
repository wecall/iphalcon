<?php
//设置默认连接
$config['qq'] = array(
	// 个人邮箱
	"email"  => array(
		"host" 		=> "smtp.qq.com",
		"port" 		=> "465",
		"username" 	=> "wecall@ronchen.me",
		"password"  => "iytiimvadfvocbec",
		"fromMailer"=> "416994628@qq.com",
		"replyTo"   => "416994628@qq.com"
	),
	// 企业邮箱
	"exmail" => array(
		"host" 		=> "smtp.exmail.qq.com",
		"port" 		=> "465",
		"username" 	=> "admin@wecall.me",
		"password"  => "ZD6&TF/2`jQX~2h`F',",
		"fromMailer"=> "admin@wecall.me",
		"replyTo"   => "admin@wecall.me",
	)
);


return $config;