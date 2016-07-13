<?php
/**
 * 分析SQL语句 操作类
 *
 *
 * @author ron_chen<ron_chen@hotmail.com>
 * @copyright ron_chen<ron_chen@hotmail.com>
 * @link http://www.ronchen.me/
 */

namespace services;

class ProfilerService{

	protected static $profiler;
	protected static $instance = NULL;

	private function __construct(){
		self::$profiler = getDI('profiler');
    }

	public static function getInstance(){
		if (empty(self::$instance)) {
			self::$instance  = new ProfilerService();
		}
		return self::$instance;
	}

	/**
	 * 输出最后一条SQL
	 */
	public static function outputSQL(){
		return self::$profiler->getLastProfile()->getSQLStatement();
	}

	/**
	 * 执行的SQL记录到Log中
	 */
	public static function recordSQL(){
		$profiles = self::$profiler->getProfiles();
		
		//遍历输出
		foreach ($profiles as $profile) {
			LogsService::debug("当前的SQL语句:{sql},执行时间:{stime}~{etime},消耗时间:{seconds}",array(
				"sql"    => $profile->getSQLStatement(),
				"stime"  => $profile->getInitialTime(),
				"etime"  => $profile->getFinalTime(),
				"seconds"=> $profile->getTotalElapsedSeconds()
			));
		}
	}

}