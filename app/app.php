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
        $this->loadCollections();
        // $this->loadDb();
        // 测试环境监听 sql 语句
        $this->loadProfiler();
        $this->loadDbEvent();
        $this->loadModelManager();

        $this->loadRedis();
        $this->loadCrypt();
        $this->loadSession();
        $this->loadNameSpaces();
        
        $app = new \Phalcon\Mvc\Micro($this->di);
        /**
         * Mount all of the collections, which makes the routes active.
         */
        foreach ($this->di->get('collections') as $collection) {
            $app->mount($collection);
        }

        $app->notFound(function () use ($app) {
            throw new HTTPException(404, 'Not Found.', 404);
        });
        $app->before(function () use ($app) {
            
            return true;
        });

        $app->after(function () use ($app) {
            // Respond by default as JSON
            if (!$app->request->get('type') || $app->request->get('type') == 'json') {
                // Results returned from the route's controller.  All Controllers should return an array
                $records = $app->getReturnedValue();
                $response = new JSONResponse();
                $response->convertSnakeCase(true)->send($records);
                
                return true;
            } else {
                if ($app->request->get('type') == 'csv') {
                    $records = $app->getReturnedValue();
                    $response = new CSVResponse();
                    $response->useHeaderRow(true)->send($records);

                    return true;
                } else {
                    throw new HTTPException(
                        403,
                        'Could not return results in specified format',
                        403
                    );
                }
            }
        });

        $app->get('/', function () use ($app) {
            $routes = $app->getRouter()->getRoutes();
            $routeDefinitions = [
                'GET' => [],
                'POST' => [],
                'PUT' => [],
                'PATCH' => [],
                'DELETE' => [],
                'HEAD' => [],
                'OPTIONS' => []
            ];
            foreach ($routes as $route) {
                $method = $route->getHttpMethods();
                $routeDefinitions[$method][] = $route->getPattern();
            }

            return $routeDefinitions;
        });

        set_exception_handler(function (\Exception $exception) use ($app) {
            //HTTPException's send method provides the correct response headers and body
            if ($exception instanceof HTTPException) {
                /** @var $exception HTTPException */
                $exception->send();
            } else {
                $di = \Phalcon\DI::getDefault();
                $response = $di->get('response');
                $response->setContentType('application/json');
                $response->setStatusCode(503, "Service Unavailable")->sendHeaders();
                $returnArr = ['status' => 'ERROR', 'errorCode' => '503', 'errorMessage' => "App Exception"];
                if (config('common.isDebug')) {
                    $returnArr['errorMessage'] = $exception->getMessage();
                }
                $response->setJsonContent($returnArr);
                $response->send();
                
                \services\LogsService::error($exception->getMessage(),array(),"Exception");
            }
        });

        return $app;
    }
}


$init = new APP();
$app  = $init->run();
$app->handle();


