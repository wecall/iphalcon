<?php

namespace business\website;

use extend\Iwebsite;

/**
* 大众点评的数据接口访问
*/
class BDianping implements Iwebsite{
	
	public $webUrlStr     = "www.dianping.com";
	public $webUrlArr  = array();

	function __construct(){
		$this->webUrlArr = array(
			"base"   => "http://www.dianping.com/",
			"search" => "http://www.dianping.com/search/keyword/1/0_%s",
		);
	}

	/**
	 * 获取搜索信息
	 */
	public function getSearchInfo($keyword){
		$curl = new \services\RollingCurlService();
        $curl->set_gzip(true);
        $url = sprintf($this->webUrlArr["search"],$keyword);
        $curl->get($url);
        $data = $curl->execute();
        var_dump($this->getData($data));
	}

	/**
	 * 获取数据
	 */
	private function getData($data){
		echo $data;
		exit;
	}

	function check(){
		
	}
}