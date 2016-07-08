<?php
/**
 * @Author: Ron Chen
 * @Date:   2016-02-29 12:50:33
 * @Last Modified by:   Ron Chen
 * @Last Modified time: 2016-07-08 17:40:57
 */

class WechatController extends BaseController{
	public $param;
	public $wechat;
	public $mongo; 

	public function onConstruct(){
		$common = config("common");

		
		$configWechat = $common["wechat"];
		if (WEB_MODE == 'develop') {
			$configWechat = $configWechat["develop"];
		}else{
			$configWechat = $configWechat["product"];
		}

		// 初始化身份验证
		$this->wechat = new WeiXin(
			$configWechat["token"],
			$configWechat["appid"],
			$configWechat["appsecret"]);
		
		$this->mongo  = new MongoClient($common["mongodb"]);

		$this->param = array();
		$this->param += $this->params;

		$this->param["title"]  = "API微信管理平台";
		$this->param["description"] = "API微信管理平台-公众号管理";

	}


	public function index(){
		
		// 创建菜单
		$this->createMenu();
		// 处理接收的消息
		$this->receiveMessage();
		// 处理回复消息
	}

	public function adminAction(){

		$this->_view->assign($this->param);
	}

	// 新增临时素材
	public function mediaUploadAction(){
		$result = array();
		// 添加素材管理
		if ($_FILES["media"]["error"] > 0){
			$result["code"] = $_FILES["media"]["error"];
			$result["msg"]  = "数据错误";
			exit(json_encode($result));
		}

		$filename = $_FILES["media"]["name"];
		$fileext  = strtolower(Tools::getFileExtension($filename));
	    $filesize = ($_FILES["media"]["size"] / 1024);

	    $uploadpath = APP_PATH."/public/upload/wechat/";

	    if (file_exists($uploadpath.$filename)){
	    	$result["code"] = "301";
			$result["msg"]  = "文件已存在";
			exit(json_encode($result));
	    }
	    // 服务器存储
	    move_uploaded_file($_FILES["media"]["tmp_name"], $uploadpath.$filename);
	    $postVal = false;
	    $postValNew = false;

	    switch ($fileext){
			case "jpg":
				if ($filesize > 64 && $filesize <= 128 ){
					$postVal = $this->wechat->mediaUpload('image',$uploadpath.$filename);
					if (!$postVal) {
						$result["code"] = "302";
						$result["msg"]  = "图片上传失败";
						exit(json_encode($result));
					}
				}elseif( $filesize > 128 ){
					$result["code"] = "303";
					$result["msg"]  = "图片大小不符合规范";
					exit(json_encode($result));
				}else{
					$postVal = $this->wechat->mediaUpload('image',$uploadpath.$filename);
					if (!$postVal) {
						$result["code"] = "302";
						$result["msg"]  = "图片上传失败";
						exit(json_encode($result));
					}
					$postValNew = $this->wechat->mediaUpload('thumb',$uploadpath.$filename);
					if (!$postValNew) {
						$result["code"] = "304";
						$result["msg"]  = "缩略图上传失败";
						exit(json_encode($result));
					}else{
						$db_result_new = Tools::object_to_array($postValNew);
						$db_result_new["is_temporary"] = 0;
						$this->mongo->selectDB('imovie')->selectCollection('wx_media_upload')->insert($db_result_new);
					}
				}
				break;
			case "amr":
			case "mp3":
				if ($filesize > 256){
					$result["code"] = "305";
					$result["msg"]  = "语音大小不符合规范";
					exit(json_encode($result));
				}else {
					$postVal = $this->wechat->mediaUpload('voice',$uploadpath.$filename);
					if (!$postVal) {
						$result["code"] = "306";
						$result["msg"]  = "语音上传失败";
						exit(json_encode($result));
					}
				}
				break;
			case "mp4":
				if ($filesize > 1024 ){
					$result["code"] = "307";
					$result["msg"]  = "视频大小不符合规范";
					exit(json_encode($result));
				}else{
					$postVal = $this->wechat->mediaUpload('video',$uploadpath.$filename);
					if (!$postVal) {
						$result["code"] = "308";
						$result["msg"]  = "视频上传失败";
						exit(json_encode($result));
					}
				}
				break;
			default:
				$result["code"] = "309";
				$result["msg"]  = "文件格式不符合规范";
				exit(json_encode($result));
				break;
		}
		$result["code"] = "200";
		$result["msg"]  = "上传微信服务器成功";
		
		// 处理入库操作 is-temporary
		$db_result = Tools::object_to_array($postVal);
		$db_result["is_temporary"] = 0;

		// 数据插入
		$this->mongo->selectDB('imovie')->selectCollection('wx_media_upload')->insert($db_result);
		// 插入数据库
		exit(json_encode($result));
	}

	// 新增图文素材信息
	public function mediaImageUploadAction(){
		Yaf_Dispatcher::getInstance ()->disableView();
		$result = array();
		// 添加素材管理
		if ($_FILES["media"]["error"] > 0){
			$result["code"] = $_FILES["media"]["error"];
			$result["msg"]  = "数据错误";
			exit(json_encode($result));
		}

		$filename = $_FILES["media"]["name"];
		$fileext  = strtolower(Tools::getFileExtension($filename));
	    $filesize = ($_FILES["media"]["size"] / 1024);

	    $uploadpath = APP_PATH."/public/upload/wechat/image_text/";

	    if (file_exists($uploadpath.$filename)){
	    	$result["code"] = "301";
			$result["msg"]  = "文件已存在";
	    }else{
	    	// 服务器存储
		    move_uploaded_file($_FILES["media"]["tmp_name"], $uploadpath.$filename);

		    $file_ext_arr = ["jpg","png","jpeg"];

		    if (in_array($fileext, $file_ext_arr)) {
		    	
		    	if ($filesize > 1024){
					$result["code"] = "303";
					$result["msg"]  = "图片大小不符合规范";
				}else {
					$postVal = $this->wechat->imageUpload($uploadpath.$filename);
					if (!$postVal) {
						$result["code"] = "304";
						$result["msg"]  = "图片上传失败";
					}else{
						$result["code"] = "200";
						$result["msg"]  = "图片上传成功";
						$db_result = Tools::object_to_array($postVal);
						$db_result["type"] = "imagetext";
						$db_result["is_temporary"] = 1;
						$db_result["created_at"]   = time();
						// 数据插入
						$this->mongo->selectDB('imovie')->selectCollection('wx_media_upload')->insert($db_result);
					}
				}
		    }else{
		    	$result["code"] = "302";
				$result["msg"]  = "文件格式不正确";
		    }
	    }
	   
	    exit(json_encode($result));
	}


	// 永久素材上传
	public function materialUploadAction(){
		Yaf_Dispatcher::getInstance ()->disableView();
		$request = $this->getRequest();

		$title = $request->getPost('title','');
		$introduction = $request->getPost('introduction','');

		// 添加素材管理
		if ($_FILES["media"]["error"] > 0){
			$result["code"] = $_FILES["media"]["error"];
			$result["msg"]  = "数据错误";
			exit(json_encode($result));
		}

		$filename = $_FILES["media"]["name"];
		$fileext  = strtolower(Tools::getFileExtension($filename));
	    $filesize = ($_FILES["media"]["size"] / 1024);

	    $uploadpath = APP_PATH."/public/upload/wechat/material/";

	    if (file_exists($uploadpath.$filename)){
	    	$result["code"] = "301";
			$result["msg"]  = "文件已存在";
			exit(json_encode($result));
	    }else{
	    	// 服务器存储
		    move_uploaded_file($_FILES["media"]["tmp_name"], $uploadpath.$filename);
		    $postVal = false;
		    $str_type = "";
		    $postMaterialArr = Array();
			$postMaterialArr["title"] = $title;
			$postMaterialArr["introduction"] = $introduction;

		    switch ($fileext){
				case "jpg":
					$str_type = "image";
					if ($filesize > 64 && $filesize <= 128 ){
						$postVal = $this->wechat->materialUpload('image',$uploadpath.$filename,$postMaterialArr);
						if (!$postVal) {
							$result["code"] = "302";
							$result["msg"]  = "图片上传失败";
							exit(json_encode($result));
						}
					}elseif( $filesize > 128 ){
						$result["code"] = "303";
						$result["msg"]  = "图片大小不符合规范";
						exit(json_encode($result));
					}else{
						$postVal = $this->wechat->materialUpload('image',$uploadpath.$filename,$postMaterialArr);
						if (!$postVal) {
							$result["code"] = "302";
							$result["msg"]  = "图片上传失败";
							exit(json_encode($result));
						}
						$postValNew = $this->wechat->materialUpload('thumb',$uploadpath.$filename,$postMaterialArr);
						if (!$postValNew) {
							$result["code"] = "304";
							$result["msg"]  = "缩略图上传失败";
							exit(json_encode($result));
						}else{
							$db_result_new = Tools::object_to_array($postValNew);
							$db_result_new["is_temporary"] = 2;
							$db_result_new["type"]         = "thumb";
							$db_result_new["created_at"]   = time();
							$db_result_new["description"]  = $postMaterialArr;
							$this->mongo->selectDB('imovie')->selectCollection('wx_media_upload')->insert($db_result_new);
						}
					}
					break;
				case "amr":
				case "mp3":
					$str_type = "voice";
					if ($filesize > 256){
						$result["code"] = "305";
						$result["msg"]  = "语音大小不符合规范";
						exit(json_encode($result));
					}else {
						$postVal = $this->wechat->materialUpload('voice',$uploadpath.$filename,$postMaterialArr);
						if (!$postVal) {
							$result["code"] = "306";
							$result["msg"]  = "语音上传失败";
							exit(json_encode($result));
						}
					}
					break;
				case "mp4":
					$str_type = "video";
					if ($filesize > 1024 ){
						$result["code"] = "307";
						$result["msg"]  = "视频大小不符合规范";
						exit(json_encode($result));
					}elseif(empty($title) || empty($introduction)){
						$result["code"] = "310";
						$result["msg"]  = "视频需要填写标题和文字介绍";
						exit(json_encode($result));
					}else{
						$postVal = $this->wechat->materialUpload('video',$uploadpath.$filename,$postMaterialArr);
						if (!$postVal) {
							$result["code"] = "308";
							$result["msg"]  = "视频上传失败";
							exit(json_encode($result));
						}
					}
					break;
				default:
					$result["code"] = "309";
					$result["msg"]  = "文件格式不符合规范";
					exit(json_encode($result));
					break;
			}
	    }

		$result["code"] = "200";
		$result["msg"]  = "上传微信服务器成功";
		$db_result = Tools::object_to_array($postVal);
		$db_result["is_temporary"] = 2;
		$db_result["type"]         = $str_type;
		$db_result["created_at"]   = time();
		$db_result["description"]  = $postMaterialArr;
		// 数据插入
		$this->mongo->selectDB('imovie')->selectCollection('wx_media_upload')->insert($db_result);
		// 插入数据库
		exit(json_encode($result));
	}

	// 获取临时素材列表
	public function mediaUploadListAction(){

		Yaf_Dispatcher::getInstance ()->disableView();

		$media_list_cursor = $this->mongo->selectDB('imovie')->selectCollection('wx_media_upload')->find();

		$result = Array();
		$result_row = Array();
		$i = 0;
		while( $media_list_cursor->hasNext() ) {
			$i++;
			$media_item =  $media_list_cursor->getNext();
			$result_row["id"] = $i;
			$result_row["create_time"] = date('Y-m-d H:i:s',$media_item["created_at"]);
			switch ($media_item["type"]) {
				case 'thumb':
					$result_row["str_type"] = "缩略图";
					$result_row["str_down"] = $this->wechat->mediaDownloadUrl($media_item["thumb_media_id"]);
					break;
				case 'image':
					$result_row["str_type"] = "图片";
					$result_row["str_down"] = $this->wechat->mediaDownloadUrl($media_item["media_id"]);
					break;
				case 'video':
					$result_row["str_type"] = "视频";
					$result_row["str_down"] = $this->wechat->mediaDownloadUrl($media_item["media_id"]);
					break;
				case 'voice':
					$result_row["str_type"] = "语音";
					$result_row["str_down"] = $this->wechat->mediaDownloadUrl($media_item["media_id"]);
					break;
				default:
					$result_row["str_type"] = "未知";
					$result_row["str_down"] = "#";
					break;
			}

			array_push($result, $result_row);
		}

		exit(json_encode($result));
	}

	// 消息接收处理
	private function receiveMessage(){
		$this->wechat->handleRequest();
		$message = array();

		if ($this->wechat->getPostObj()) {
			$message["ToUserName"] = $this->wechat->getToUserName();
			$message["FromUserName"] = $this->wechat->getFromUserName();
			$message["CreateTime"] = $this->wechat->getCreateTime();
			$receive_type = $this->wechat->getMsgType();
			$message["MsgType"] = $receive_type;

			switch ($receive_type) {
				case 'text':
					$message["Content"] = $this->wechat->requestText();
					break;
				case 'image':
					$message["Content"] = $this->wechat->requestImage();
					break;
				case 'location':
					$message["Content"] = $this->wechat->requestLocation();
					break;
				case 'voice':
					$message["Content"] = $this->wechat->requestVoice();
					break;
				case 'shortvideo':
					$message["Content"] = $this->wechat->requestVideo();
					break;
				case 'video':
					$message["Content"] = $this->wechat->requestVideo();
					break;
				case 'link':
					$message["Content"] = $this->wechat->requestLink();
					break;
				case 'event':
					$event_type = $this->wechat->getEvent();

					if ($this->wechat->isEventSubscribe()) {
						// 订阅事件	
						$message["Content"] = array(
							"message" => "欢迎订阅",
							"eventinfo" => array()
						);
					}elseif ($this->wechat->isEventUnSubscribe()) {
						// 取消订阅
						$message["Content"] = array(
							"message" => "欢迎下次再来",
							"eventinfo" => array()
						);
					}elseif ($this->wechat->isEventScanSubscript()) {
						// 是否为扫描二维码关注事件
						$message["Content"] = array(
							"message" => "欢迎订阅",
							"eventinfo" => $this->wechat->requestEventScan()
						);
					}elseif ($this->wechat->isEventScan()) {
						// 是否为已关注扫描二维码事件
						$message["Content"] = array(
							"message" => "已经订阅",
							"eventinfo" => $this->wechat->requestEventScan()
						);
					}elseif ($this->wechat->isEventLocation()) {
						// 是否为上报地理位置事件
						$message["Content"] = array(
							"message" => "上报地理位置",
							"eventinfo" => $this->wechat->requestEventLocation()
						);
					}elseif ($this->wechat->isEventClick()) {
						// 是否为菜单点击事件
						$message["Content"] = array(
							"message" => "点击菜单",
							"eventinfo" => $this->wechat->requestEventClick()
						);
					}else{
						// 未知的事件类型
						$message["Content"] = array(
							"message" => "未知事件",
							"eventinfo" => array()
						);
					}
					break;		
				default:
					$message["Content"] = array();
					break;
			}
			// 插入Mongodb
			$result = $this->mongo->selectDB('imovie')->selectCollection('wx_request_message')->insert($message);
		}else{
			file_put_contents(LOG_DIR."/message_none.txt","消息错误");
		}

	}

	// 创建 菜单
	private function createMenu(){
		$menu = array(
			array(
				"name" => "最新故事",
				"sub_button" => array(
					array(
						"type"=>"click",
		                "name"=>"《我们的故事》", 
		                "key"=>"V1001_NEW_BLOG", 
		                "sub_button"=>array()
					),
					array(
						"type"=>"click",
		                "name"=>"《我们的故事》", 
		                "key"=>"V1002_NEW_BLOG", 
		                "sub_button"=>array()
					),
					array(
						"type"=>"click",
		                "name"=>"《我们的故事》", 
		                "key"=>"V1003_NEW_BLOG", 
		                "sub_button"=>array()
					),
					array(
						"type"=>"click",
		                "name"=>"《我们的故事》", 
		                "key"=>"V1004_NEW_BLOG", 
		                "sub_button"=>array()
					),
				)
			),
			array(
				"name" => "领取祝福",
				"sub_button" => array(
					array(
						"type" =>"view", 
	                    "name" =>"节日礼物",
	                    "url" => "http://www.wecall.me/",
	                    "key" =>"V2001_GIFT", 
	                    "sub_button" => array()
					),
					array(
	                    "type"=> "view", 
	                    "name"=> "IP 设计",
	                    "url" => "http://www.wecall.me/",
	                    "key"=> "V2002_IP_DESIGN", 
	                    "sub_button"=> array()
					),
					array(
	                    "type"=> "view", 
	                    "name"=> "项目兼职", 
	                    "url" => "http://www.wecall.me/",
	                    "key"=> "V2003_PART_TIME", 
	                    "sub_button"=> array()
					),
					array(
	                    "type"=> "click", 
	                    "name"=> "软件组装师", 
	                    "key"=> "V2004_ASSEMBLY_DIVISION", 
	                    "sub_button"=> array()
					),
				)
			),
			array(
				"name" => "我的独家",
				"sub_button" => array(
					array(
						"type" =>"view", 
	                    "name" =>"便利店",
	                    "url" => "http://www.wecall.me/",
	                    "key" =>"V3001_DIME_STORE", 
	                    "sub_button" => array()
					),
					array(
	                    "type"=> "view", 
	                    "name"=> "所有文摘",
	                    "url" => "http://www.ronchen.me/",
	                    "key" =>"V3002_MY_BLOG", 
	                    "sub_button"=> array()
					),
					array(
	                    "type"=> "click", 
	                    "name"=> "我的名片", 
	                    "key"=> "V3003_MY_CARD", 
	                    "sub_button"=> array()
					),
				)
			)
		);
		
		$this->wechat->menuCreate($menu);
	}
}