<?php
//设置默认连接
$config = array(
	"qq" => array(
		// 个人邮箱
		"email"  => array(
			"host" 		=> "smtp.qq.com",
			"hostname"  => "ronchen.me",
			"port" 		=> "465",
			"username" 	=> "wecall@ronchen.me",
			"password"  => "iytiimvadfvocbec",
			"fromMailer"=> "416994628@qq.com",
			"fromName"  => "wecall.me",
			"replyTo"   => "416994628@qq.com"
		),
		// 企业邮箱
		"exmail" => array(
			"host" 		=> "smtp.exmail.qq.com",
			"hostname"  => "wecall.me",
			"port" 		=> "465",
			"username" 	=> "admin@wecall.me",
			"password"  => "ZD6&TF/2`jQX~2h`F',",
			"fromMailer"=> "admin@wecall.me",
			"fromName"  => "wecall.me",
			"replyTo"   => "admin@wecall.me",
		)
	),
	"163" => array(
		"email" => array(
			"host" 		=> "smtp.163.com",
			"hostname"  => "ronchen.me",
			"port" 		=> "25",
			"username" 	=> "wecall@ronchen.me",
			"password"  => "iytiimvadfvocbec",
			"fromMailer"=> "416994628@163.com",
			"fromName"  => "wecall.me",
			"replyTo"   => "416994628@163.com"
		)
	)
);


return $config;