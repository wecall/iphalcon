<?php

class AbstractModel extends \Phalcon\Mvc\Model
{
    public function initialize()
    {
        try {
            $dbWrite = 'chat_db_master';
            $dbRead = 'chat_db_slave';
            if ($this->getDI()->has($dbWrite)) {
                $this->setWriteConnectionService($dbWrite);
            }
            if ($this->getDI()->has($dbRead)) {
                $this->setReadConnectionService($dbRead);
            }
        } catch (\Exception $e) {
            echo $e->getMessage();
        }

        $this->setup(array(
            'event' => true,
            'notNullValidations' => false,
            'phqlLiterals' => true,
        ));
    }
}