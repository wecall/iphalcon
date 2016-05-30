<?php

require_once APP_PATH . '/init.php';

class APP extends init
{

    /**
     * @var \Phalcon\Di\FactoryDefault
     */
    public $di;

    public function __construct()
    {
        $this->di = new \Phalcon\Di\FactoryDefault();
    }

    public function run()
    {
        $this->registerDirs();
        $this->loadDb();
        $this->loadRedis();
        $this->loadCrypt();
        $this->loadSession();
        $this->loadViewCache();
        $this->loadView();
        $this->loadNameSpaces();
        $this->loadRouter();
        
        try {
            $application = new \Phalcon\Mvc\Application($this->di);
            echo $application->handle()->getContent();
        } catch (Exception $e) {
            echo "Exception: ", $e->getMessage();
        }
    }
}


$init = new APP();
$init->run();
