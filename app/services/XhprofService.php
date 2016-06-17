<?php
/**
 * xhprof 操作类
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the MIT-LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @author ron_chen<ron_chen@hotmail.com>
 * @copyright ron_chen<ron_chen@hotmail.com>
 * @link http://www.ronchen.me/
 */

class XhprofService{
	
	private $dir;
	private $data;
	private $runId;

	public function __construct($dir=""){
		if (empty($dir)) {
			$this->dir = "/alidata/log/xhprof";
		}else{
			$this->dir = $dir;
		}
		require_once(APP_PATH."/services/Xhprof/xhprof_lib.php");
		require_once(APP_PATH."/services/Xhprof/xhprof_runs.php");
	}

	/**
	 * 开始点
	 */
	public function beginDebug(){
		xhprof_enable(XHPROF_FLAGS_CPU+XHPROF_FLAGS_MEMORY);
	}

	/**
	 * 结束点
	 */
	public function endDebug($isout=false){
		$this->data = xhprof_disable();
		if ($isout) {
			var_dump($this->data);
		}
	}

	/**
	 * 运行实例ID
	 */
	public function getRunId(){
		$objXhprofRun = new XHProfRuns_Default($this->dir);
		return $objXhprofRun->save_run($this->data, 'xhprof');
	}


}	