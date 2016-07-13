<?php
$config = array(
	"session_name" => "access_session",
	"cryptKey"     => "f8cd1431833d3abcae09cc49f11c7963",
	// 极验验证 -- 免费版本 / 企业版本 / 企业旗舰版
	"geetest"      => array(
		"captcha_id"  => "4b05a5bc8e547bfba4152080e81b326e",
		"private_key" => "bc1a648ba9d8c175821331650ea84632"
	),
	"wechat"      => array(
		"develop" => array(
			"token"     => "3bfda7f7c8da890a7cbae8c5e4358578",
			"appid"     => "wx1b722f279522e164",
			"appsecret" => "42f9d1b20f4d5144a30b823077fa74fe",
		),
		"product" => array(
			"token"     => "3bfda7f7c8da890a7cbae8c5e435857c",
			"appid"     => "wx0451699d8bc68817",
			"appsecret" => "ea932bf1eb3ac5a16732b74b0f4291a1",
		)
	),
	"isDebug"	   => true,
);
return $config;