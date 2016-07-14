<?php

class JSONResponse extends Response
{

    protected $snake = true;

    public function __construct()
    {
        parent::__construct();
    }

    public function send($records, $error = false)
    {

        // Error's come from HTTPException.  This helps set the proper envelope data
        $response = $this->di->get('response');
        // Most devs prefer camelCase to snake_Case in JSON, but this can be overriden here
        if ($this->snake) {
            $records = $this->arrayKeysToSnake($records);
        }

        if (!$error) {
            $message = ['status' => 'OK', 'results' => $records];
        } else {
            $message = ['status' => 'ERROR', 'errorCode' => $records['errorCode'], 'errorMessage' => $records['errorMessage']];
        }
        $response->setContentType('application/json');
        // HEAD requests are detected in the parent constructor. HEAD does everything exactly the
        // same as GET, but contains no body.
        $response->setJsonContent($message);
        $response->send();
        return $this;
    }

    public function convertSnakeCase($snake)
    {
        $this->snake = (bool)$snake;
        return $this;
    }
}
