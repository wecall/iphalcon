<?php

class UserTask extends \Phalcon\CLI\Task{
	/**
     * @var Redis
     */
    protected $redis;

    public function initialize()
    {
        $this->redis = getDI()->get('redis');
    }

    /**
     * 测试脚本任务的执行
     * php cli.php user checkLogin 12 
     */
    public function checkLoginAction( array $params){
        echo "succees ".$params[0];
        echo "succees ".$params[1];
    }

}