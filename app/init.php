<?php

use Phalcon\Mvc\View;
use Phalcon\Loader as Loader;
use Phalcon\Session\Adapter\Files as SessionAdapter;
use Phalcon\Mvc\Model\Manager as ModelsManager;

/**
* Phalcon 的初始化文件
*/
class init
{
	public function registerDirs(){
        $loader = new Loader();
        $loader->registerDirs(array(
            APP_PATH . '/controllers',
            APP_PATH . '/models',
            APP_PATH . '/consts',
            APP_PATH . '/exceptions',
            APP_PATH . '/responses',
            APP_PATH . '/services',
            APP_PATH . '/router',
            APP_PATH . '/utils',
            APP_PATH . '/tasks',
            APP_PATH . '/library',
            APP_PATH . '/interface',
            APP_PATH . '/business',
        ))->register();
    }

    public function loadDb(){
        $dbs = config("db");
        foreach ($dbs as $key => $value) {
            $this->di->set($key, function () use ($value) {
                $adapter = isset($value['adapter']) ? $value['adapter'] : "Mysql";
                unset($value['adapter']);
                $class = 'Phalcon\Db\Adapter\Pdo\\' . $adapter;
                return new $class($value);
            });
        }
    }

    /**
     * 分析SQL语句
     */
    public function loadProfiler(){
        $this->di->set('profiler', function(){
            return new \Phalcon\Db\Profiler();
        }, true);
    }

    public function loadModelManager(){
        $this->di->set('modelsManager', function() {
              return new ModelsManager();
        });
    }

    /**
     * 开发环境 的数据库加载  SQL 监听
     */
    public function loadDbEvent(){
        $dbs = config("db");
        foreach ($dbs as $key => $value) {
            $this->di->set($key, function () use ($value) {

                $eventsManager = new \Phalcon\Events\Manager();
                $profiler = getDI("profiler");

                $eventsManager->attach('db', function($event, $connection) use ($profiler) {
                    if ($event->getType() == 'beforeQuery') {
                        $profiler->startProfile($connection->getSQLStatement());
                    }
                    if ($event->getType() == 'afterQuery') {
                        $profiler->stopProfile();
                    }
                });

                $adapter = isset($value['adapter']) ? $value['adapter'] : "Mysql";
                unset($value['adapter']);
                $class = 'Phalcon\Db\Adapter\Pdo\\' . $adapter;

                $connection = new $class($value);
                $connection->setEventsManager($eventsManager);

                return $connection;
            });
        }
    }

    public function loadRedis(){
        $redisConfig = config('redis.default');
        if (!empty($redisConfig)) {
            $this->di->set('redis', function () use ($redisConfig) {
                $redis = new \Redis();
                if ($redisConfig['timeout'] > 0) {
                    $redis->connect($redisConfig['host'], $redisConfig['port'], $redisConfig['timeout']);
                } else {
                    $redis->connect($redisConfig['host'], $redisConfig['port']);
                }

                return $redis;
            });
        }
    }

    public function loadNameSpaces() {
        $namespaces = config("namespaces");
       
        $loader = new Loader();
        $loader->registerNamespaces($namespaces)->register();
    }
    
    public function loadSession() {
        $config = config("common");
        $this->di->setShared('session', function () use ($config) {
            session_name("access_token");
            $session = new SessionAdapter(
                array('uniqueId' => $config['session_name'])
            );
            $session->start();

            return $session;
        });

        $this->di->set('cookies', function () {
            $cookies = new Phalcon\Http\Response\Cookies();
            $cookies->useEncryption(false);

            return $cookies;
        }, true);
    }

    
    public function loadCrypt(){
        $this->di->set('crypt', function () {
            $crypt = new \Phalcon\Crypt();
            $crypt->setKey(config('common.cryptKey')); //Use your own key!
            return $crypt;
        });
    }

    public function loadCollections(){
        $this->di->set('collections', function () {
            return include(dirname(__FILE__) . '/router/routerLoader.php');
        });
    }

}



/**
 * @param string $key
 * @param mixed $default
 * @return mixed
 */
function config($key = null, $default = null)
{
    static $arrConfig;
    $internal_config_path = realpath(dirname(__FILE__) . '/../app/config');
    $external_config_path = realpath(CONFIG_PATH);
    
    $value = ArrUtil::get($arrConfig, $key, $default);
    if ($arrConfig && $value) {
        return $value;
    }

    if (!strpos($key, '.')) {
        $file_name = $key;
    } else {
        $arr = explode('.', $key);
        $file_name = $arr[0];
    }

    $internal_config_file = $internal_config_path . '/' . $file_name . '.php';
    if (!file_exists($internal_config_file)) {
        exit('config file ' . $internal_config_file . 'not exist!');
    }

    $data = require_once $internal_config_file;
    if ($external_config_path != $internal_config_path) {
        $external_config_file = $external_config_path . '/' . $file_name . '.php';
        if (file_exists($external_config_file)) {
            $data = require_once $external_config_file;
        }
    }

    $arrConfig = ArrUtil::add($arrConfig, $file_name, $data);

    return ArrUtil::get($arrConfig, $key, $default);
}

/**
 * @param null $beanName
 * @param null $parameters
 * @return mixed|\Phalcon\DiInterface
 */
function getDI($beanName = null, $parameters = null)
{
    $di = \Phalcon\DI::getDefault();
    if (!$beanName) {
        return $di;
    }

    return $di->get($beanName, $parameters);
}

function getClientIp()
{
    static $clientIp;
    if (!isset($clientIp)) {
        $ip = null;

        if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ipArray = explode(",", $_SERVER['HTTP_X_FORWARDED_FOR']);

            for ($i = count($ipArray) - 1; $i >= 0; $i--) {
                $_ip = trim($ipArray[$i]);
                if ((!preg_match('/^\d+\.\d+\.\d+\.\d+$/', $_ip))
                    || preg_match("/^(10|192\.168)\./", $_ip)
                ) {
                    continue;
                }

                $tmp = explode('.', $_ip);
                if ($tmp[0] == 172 && $tmp[1] >= 16 && $tmp[1] <= 31) {
                    continue;
                }

                $ip = $_ip;
                break;
            }
        }

        if (!$ip) {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        $clientIp = $ip;

        return $clientIp;
    }

    return $clientIp;
}