<?php

namespace business\website;

use extend\Iwebsite;
/**
* Github 的数据接口访问
*/
class BGithub implements Iwebsite{
	
	public $webUrl     = "github.com";
	public $searchType = array("Repositories","Code","Issues","Users");
	public $webUrlArr  = array();

	public function __construct(){

		$this->webUrlArr = array(
			"base"   => "https://github.com",
			// &ref=searchresults&type=%s
			"search" => "https://github.com/search?q=%s",
		);

	}

	/**
	 * 获取搜索页面的Language bar count
	 * @param $keyword 搜索的关键字
	 */
	public function getLanguageBarJson($keyword){
		$curl = new \services\RollingCurlService();
        $curl->set_gzip(true);
        $url = sprintf($this->webUrlArr["search"],$keyword);
        $curl->get($url);
        $data = $curl->execute();
        
        var_dump($this->getData($data));

	}

	/**
	 * 检查当前url 结构是否改变
	 */
	public function check(){
		
	}


	/**
	 * 页面结构处理逻辑 ----------------------------
	 * @param data text/html 数据结构内容
	 */
	private function getData($data=''){
		\phpQuery::newDocument($data);
		// 分块处理 页面的HTML
		
		$domObj = pq("div#js-pjax-container > div.container > div.columns > div.one-fourth")->text();
        echo $domObj ;
        exit();
	}


}