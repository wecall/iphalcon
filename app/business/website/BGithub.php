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
		$containerObj  = pq("div#js-pjax-container > div.container > div.columns");

		// 左边数据处理
		$asideObj      = $containerObj->find("div.codesearch-aside");

			// 左边 ---- 1
		$htmlDataMenu  = array(); 
		foreach ($asideObj->find('nav.menu > a') as $item_a) {
			$href_url     = pq($item_a)->attr("href");
			$href_url_arr = parse_url($href_url);
			array_push($htmlDataMenu, array(
				"href"    => $href_url,
				"text"    => $href_url_arr["query"],
				"counter" => str_replace(",", "", pq($item_a)->find("span.counter")->text())
			));
		}

			// 左边 ---- 2
		$htmlDataLanguage = array();
		foreach ($asideObj->find('ul.filter-list > li') as $item_li) {
			$width_percent = pq($item_li)->find("span.bar")->attr("style");
			$a_href_obj    = pq($item_li)->find("a");
			array_push($htmlDataLanguage, array(
				"href"    => $a_href_obj->attr("href"),
				"text"    => $a_href_obj->text(),
				"percent" => \Tools::strStyleToHash($width_percent),
				"counter" => str_replace(",", "", pq($item_li)->find("a > span.count")->text()),
			));
		}

		// 右边数据处理
		$resultsObj     = $containerObj->find("div.codesearch-results");
		$htmlDataResult = array();
		foreach ($resultsObj->find("ul.repo-list > li") as $li_repo) {
			$repoLiObj   = pq($li_repo);
			$repoNameObj = $repoLiObj->find('h3.repo-list-name > a');
			$repoStatObj = $repoLiObj->find('div.repo-list-stats');
			array_push($htmlDataResult, array(
				"name"   => $repoNameObj->attr("href"),
				"url"    => $repoNameObj->text(),
				"stargazers" => $repoStatObj->find("a:first")->text(),
				"forks" => $repoStatObj->find("a:last")->text(),
				"desc"  => $repoLiObj->find('p.repo-list-description')->text(),
				"meta"  => $repoLiObj->find('p.repo-list-meta')->text(),
			));
		}

		return array(
			"menuType" => $htmlDataMenu,
			"language" => $htmlDataLanguage,
			"repoList" => $htmlDataResult
		);
	}
	
}