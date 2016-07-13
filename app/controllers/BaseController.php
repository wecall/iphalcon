<?php

use Phalcon\Mvc\Controller;

class BaseController extends Controller{

	/**
	 * 身份验证
	 */
    protected $access_token;

    public function onConstruct(){

      $this->access_token = \Tools::buildToken();
      
      $accessToken = $this->request->getHeader(GlobalConsts::AUTH_TOKEN);
      
    }
    
    /**
     * 参数处理
     */
    public function formatRequestParam(){
    	$param  = array();
    	$method = $this->request->getMethod();
    	
    	switch ($method) {
    		case 'POST':
    			$param = $this->formatPostParams();
    			break;
    		case 'GET':
    			$param = $this->formatGetParams();
    			break;
    		case 'PUT':
    			$param = $this->formatPutParams();
    			break;
    		default:
    			$param["method"] = $method;
    			break;
    	}
        // 判断是否有文件
        if ($this->request->hasFiles()) {
            $param["files"] = $this->request->getUploadedFiles();
        }

        // 处理分页
        $page = isset($param["page"]) ? $param["page"] : GlobalConsts::PAGE_ONE;
        $size = isset($param["size"]) ? $param["size"] : GlobalConsts::PAGE_SIZE;
        $pageConvertArr = \Tools::converPage($page,$size);
        $param['offset'] = $pageConvertArr['offset'];
        $param['limit']  = $pageConvertArr['limit'];

    	return $param;
    }
    

    /**
     * 格式化 GET 请求参数
     */
    protected function formatGetParams(){

    	$getParams = $this->request->getQuery();

    	return $getParams;
    }

    /**
     * 格式化 POST 请求参数
     */
    protected function formatPostParams(){

    	$postParams = $this->request->getPost();

    	return $postParams;
    }

    /**
     * 格式化 PUT 请求参数
     */
    protected function formatPutParams(){
        // $putParams = $_PUT;
        $putParams = $this->request->getPut();
        
        return $putParams;
    }

}