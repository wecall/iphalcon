<?php

/**
 * 获取网页内容的类库 操作类
 * 
 * @author ron_chen<ron_chen@hotmail.com>
 * @copyright ron_chen<ron_chen@hotmail.com>
 * @link http://www.ronchen.me/
 */
class Fetcher{

	private $http;
	private $content;

	public function __construct(){
		$this->http = new RollingCurlService();
		$this->http->set_gzip(true);
		$this->http->set_headers(config('curl.base.header'));
	}

	/**
	 * get 请求
	 */
	public function get($url,$isDebug=false){
		$this->http->get($url,array());

		if ($isDebug) {
			$this->getfilename($url);
		}else{
			$this->content = $this->http->execute();
		}
		return $this->content;
	}

	/**
	 * post 请求
	 */
	public function post($url,$field=array(),$isDebug=false){
		$this->http->post($url,$field);

		if ($isDebug) {
			if (count($field) > 0) {
				$url = sprintf("%s?data=%s",$url,json_encode($field));
			}
			$this->getfilename($url);
		}else{
			$this->content = $this->http->execute();
		}
		return $this->content;
	}

	/**
	 * 获取远程图片
	 * IMG_PATH 
	 */
	public function getOriginalImage($url,$filename=""){
		if($url==""):return false;endif;
		$url = preg_replace('/\'/','',$url);
		$url = preg_replace('/ /','',$url);
		//如果未指定图片名字（包括图片存储路径）
		if($filename == "" ){
			$ext=strrchr($url,".");
			if($ext!=".gif" && $ext!=".jpg" && $ext!=".png" && $ext!=".bmp"):return false;endif;
			$filename= IMG_PATH . SecurityUtil::saveImageName().$ext;
		}
		if (file_exists(dirname($filename)) && is_readable(dirname($filename)) && is_writable(dirname($filename))) {
			try {
				$ch = curl_init($url);
				$fp = @fopen($filename, 'w');
				curl_setopt($ch, CURLOPT_FILE, $fp);
				curl_setopt($ch, CURLOPT_HEADER, 0);
				curl_exec($ch);
				$code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
				curl_close($ch);
				fclose($fp);
				if ($code != 200) {
					@unlink($filename);
					$this->GrabImage($url,$filename);
					throw new Exception('无法获得远程文件:'.$url." 到:".$filename);
				}
			} catch(Exception $e) {
				$filename = $this->GrabImage($url,$filename);
				die($e->getMessage());
			}
			return $filename;
		}
		return false;
	}

	/*
	**功能:读取图片信息的方法
	**参数:
	**$url:网站路径
	**$filename:文件名
	**2013年12月25日
	**检查完毕
	*/
	private static function GrabImage($url,$filename="") {
		//showMsg("保存图片地址:$url  名称：$filename");
		LogsService::info("保存图片地址:{url},名称：{filename}",array("url" => $url,"filename" => $filename),"GrabImage");
		//php set_time_limit函数的功能是设置当前页面执行多长时间不过期哦。
		set_time_limit(24 * 60 * 60 * 60);
		if($url==""):return false;endif;
		if($filename=="") {
			$ext=strrchr($url,".");
			if($ext!=".gif" && $ext!=".jpg"):return false;endif;
			$filename=date("dMYHis").$ext;
		}
		ob_start();
		readfile($url);
		$img = ob_get_contents();
		ob_end_clean();
		$size = strlen($img);
		$fp2=@fopen($filename, "a");
		fwrite($fp2,$img);
		fclose($fp2);
		return $filename;
	}

	/**
	 * 启动Debug模式,将页面内容缓存到本地
	 */
	private function getfilename($url){
		$filename = CACHE_PATH . "/debug/". SecurityUtil::hash($url) .".html";
		
		if(file_exists($filename)){
			$this->content = file_get_contents($filename);
		}else{
			$this->content = $this->http->execute();
			file_put_contents($filename,$this->content);
		}
	}

}