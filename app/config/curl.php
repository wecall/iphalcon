<?php
$config = array(
	// 基础分支
	"base"  => array(
		"header" => array(
			"Accept: text/xml,application/xml,application/xhtml+xml,text/html;q=0.9,text/plain;q=0.8,image/png,*/*;q=0.5",
			"Cache-Control: max-age=0",
			"Connection: keep-alive",
			"Keep-Alive: 300",
			"Accept-Charset: ISO-8859-1,utf-8;q=0.7,*;q=0.7",
			"Accept-Language: en-us,en;q=0.5",
			"Accept-Encoding: gzip",
			"Pragma: ",
		)
	),
	"https" => array(
		"CURLOPT_SSL_VERIFYPEER" => false,
		"CURLOPT_SSL_VERIFYHOST" => 2,
	),
	"weibo" => array(
		CURLOPT_USERAGENT => "spider",
	),
	"zhihu" => array(
		"header" => array(
			"Host: www.zhihu.com",
			"Connection: keep-alive",
			"Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8",
			"Upgrade-Insecure-Requests: 1",
			"DNT:1",
			"Accept-Language: zh-CN,zh;q=0.8,en-GB;q=0.6,en;q=0.4,en-US;q=0.2"
		),
		"options" => array(
			CURLOPT_REFERER => "https://www.baidu.com/s?word=%E7%9F%A5%E4%B9%8E&tn=sitehao123&ie=utf-8&ssl_sample=normal&f=3&rsp=0",
			CURLOPT_SSL_VERIFYPEER => false,
			CURLOPT_SSL_VERIFYHOST => 2,
			CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_RETURNTRANSFER => 1
		),
	),
);
return $config;