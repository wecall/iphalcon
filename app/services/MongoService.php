<?php

class MongoService{

    protected static $_db = 'imovie';

    protected static $_collection = 'user';

    protected static $_validate = array();

    protected static $_mongoDB;

    /**
     * Config For MongDB
     *
     * @var array
     */
    public function __construct($db = '', $collection = '')
    {
        self::init($db, $collection);
    }

    /**
     * 获取Db实例
     *
     * @param bool $master
     *
     * @return mixed
     */
    public static function getInstance($db,$collection) {

        if (!isset(self::$_mongoDB))
        {
            self::$_mongoDB = self::init($db,$collection);
        }

        return self::$_mongoDB;
    }

    /**
     * Init The Class
     *
     * @param string $db  
     * @param string $collection            
     */
    public static function init($db = '', $collection = '')
    {
        if (! self::$_mongoDB) {
        	// 获取配置
            $config_mongodb = config('mongodb');
            $config = $config_mongodb[0];
            $conStr = "mongodb://";
            if ($config['username'] && $config['password']) {
                $conStr .= "{$config['username']}:{$config['password']}@";
            }
            $conStr .= "{$config['host']}:{$config['port']}";
            $_mongoDB = new MongoClient($conStr, array(
                "connect" => false
            ));
            if ($db && $collection) {
                static::$_db    = $db;
                self::$_mongoDB = $_mongoDB->selectCollection($db, $collection);
            } else {
                self::$_mongoDB = $_mongoDB->selectCollection(static::$_db, static::$_collection);
            }
        }
    }

    /**
     * Set Db & Collection
     *
     * @param string $db            
     * @param string $collection            
     */
    public static function setDb($db = NULL, $collection = NULL)
    {
        if ($db && $collection) {
            static::$_db = $db;
            static::$_collection = $collection;
            self::$_mongoDB = NULL;
        }
    }

    /**
     * Set Collection
     *
     * @param string $collection            
     */
    public static function setCollection($collection = NULL)
    {
        if ($collection) {
            static::$_collection = $collection;
            self::$_mongoDB = NULL;
        }
    }

    /**
     * Fetch From Mongodb
     *
     * @param array $argv            
     * @param number $skip            
     * @param number $limit            
     * @param array $sort            
     * @return Ambigous <multitype:, multitype:>|boolean
     */
    public static function find($argv = array(), $skip = 0, $limit = 30, $sort = array())
    {
        self::init();
        $argv = self::validate($argv);
        if ($argv) {
            $result = self::$_mongoDB->find($argv)
                ->skip($skip)
                ->limit($limit)
                ->sort($sort);
            
            return self::toArray($result);
        }
        return array();
    }

    /**
     * Fetch From Mongodb
     *
     * @param array $argv     array       
     * @param number $skip            
     * @param number $limit            
     * @param array $sort            
     * @return Ambigous <multitype:, multitype:>|boolean
     */
     public static function findByPage($argv = array(), $skip = 0, $limit = 30, $sort = array())
    {
        self::init();
        $result = self::$_mongoDB->find($argv)
            ->skip($skip)
            ->limit($limit)
            ->sort($sort);
        
        return self::toArray($result);
    }

    /**
     * Fetch By MongoId
     *
     * @param string $_id            
     * @return Ambigous <Ambigous, boolean, multitype:>
     */
    public static function findById($_id = '')
    {
        if (is_string($_id)) {
            return self::findOne(array(
                '_id' => new MongoId($_id)
            ));
        }else{
            return self::findOne(array(
                '_id' => $_id
            ));
        }
    }

    /**
     * Fetch One From MongoDB
     *
     * @param array $argv            
     * @param array $fields            
     * @return multitype: boolean
     */
    public static function findOne($argv = array(), $fields = array())
    {
        self::init();
        $argv = self::validate($argv);
        if ($argv) {
            return self::cleanId(self::$_mongoDB->findOne($argv, $fields));
        }
        return FALSE;
    }

    /**
     * Fetch All From MongoDB
     *
     * @param array $argv            
     * @param array $fields            
     * @return Ambigous <multitype:, multitype:>|boolean
     */
    public static function findAll($argv = array(), $fields = array())
    {
        self::init();
        $argv = self::validate($argv);
        if ($argv) {
            $result = self::$_mongoDB->find($argv, $fields);
            return self::toArray($result);
        }
        return FALSE;
    }

    /**
     * Find And Modify
     *
     * @param array $argv            
     * @param array $newData            
     * @param array|NULL $fields            
     * @param array $options            
     */
    public static function findAndModify($argv = array(), $newData = array(), $fields = array(), $options = NULL)
    {
        self::init();
        $argv = self::validate($argv);
        $newData = self::validate($newData);
        return self::$_mongoDB->findAndModify($argv, array(
            '$set' => $newData
        ), $fields, $options);
    }

    /**
     * Update MongoDB
     *
     * @param array $argv            
     * @param array $newData            
     * @param string $options            
     */
    public static function update($argv = array(), $newData = array(), $options = 'multiple')
    {
        self::init();
        $argv = self::validate($argv);
        $newData = self::validate($newData);
        return self::$_mongoDB->update($argv, array(
            '$set' => $newData
        ), array(
            "{$options}" => true
        ));
    }

    /**
     * Update MongoDB By Id
     *
     * @param string $_id            
     * @param array $newData            
     */
    public static function updateById($_id, $newData = array())
    {
        $result = array();
        if (is_string($_id)) {
            $result = self::update(array(
                '_id' => new MongoId($_id)
            ), $newData);
        }
        return $result;
    }

    /**
     * Insert Into Mongodb
     *
     * @param array $data            
     */
    public static function insert($data = array())
    {
        self::init();
        $data = self::validate($data);
        $s = '$id';
        self::$_mongoDB->insert($data);
        return $data['_id']->$s;
    }

    /**
     * Remove All From Mongodb
     *
     * @param array $argv            
     */
    public static function remove($argv = array())
    {
        self::init();
        $argv = self::validate($argv);
        return self::$_mongoDB->remove($argv);
    }

    /**
     * Remove By Id From Mongodb
     *
     * @param string $_id            
     * @return Ambigous <boolean, multitype:>
     */
    public static function removeById($_id)
    {
        return self::removeOne(array(
            '_id' => new MongoId($_id)
        ));
    }

    /**
     * Remove One From Mongodb
     *
     * @param array $argv            
     */
    public static function removeOne($argv = array())
    {
        self::init();
        $argv = self::validate($argv);
        return self::$_mongoDB->remove($argv, array(
            "justOne" => true
        ));
    }

    /**
     * Remove Field From MongoDB
     *
     * @param string $_id            
     * @param array $field            
     */
    public static function removeFieldById($_id, $field = array())
    {
        self::init();
        $unSetfield = array();
        foreach ($field as $key => $value) {
            if (is_int($key)) {
                $unSetfield[$value] = TRUE;
            } else {
                $unSetfield[$key] = $value;
            }
        }
        return self::$_mongoDB->update(array(
            '_id' => new MongoId($_id)
        ), array(
            '$unset' => $unSetfield
        ));
    }

    /**
     * Count
     *
     * @param unknown $argv            
     */
    public static function count($argv = array())
    {
        self::init();
        $argv = self::validate($argv);
        return self::$_mongoDB->count($argv);
    }

    /**
     * Mongodb Object To Array
     *
     * @param array $data            
     * @return multitype:
     */
    private static function toArray($data)
    {
        return self::cleanId(iterator_to_array($data));
    }

    /**
     * Clear Mongo _id
     *
     * @param array $data            
     * @return void unknown
     */
    private static function cleanId($data)
    {
        $s = '$id';
        if (isset($data['_id'])) {
            $data['_id'] = $data['_id']->$s;
            return $data;
        } elseif ($data) {
            foreach ($data as $key => $value) {
                $data[$key]['_id'] = $value['_id']->$s;
            }
        }
        return $data;
    }

    /**
     * Validate Data Callbak Function
     *
     * @param array $argv            
     */
    private static function validate($data)
    {
        if (static::$_validate) {
            foreach (static::$_validate as $arg => $validate) {
                if (is_array($data) && array_key_exists(strval($arg), $data)) {
                    foreach ($validate as $key => $value) {
                        switch (strtolower($key)) {
                            case 'type':
                                if ($value == 'int') {
                                    $data[$arg] = (int) $data[$arg];
                                } elseif ($value == 'string') {
                                    $data[$arg] = (string) $data[$arg];
                                } elseif ($value == 'bool') {
                                    $data[$arg] = (bool) $data[$arg];
                                } elseif ($value == 'float') {
                                    $data[$arg] = (float) $data[$arg];
                                } elseif ($value == 'array') {
                                    $data[$arg] = (array) $data[$arg];
                                }
                                break;
                            case 'min':
                                if (strlen($data[$arg]) < $value) {
                                    exit('Error: The length of ' . $arg . ' is not matched');
                                }
                                break;
                            case 'max':
                                if (strlen($data[$arg]) > $value) {
                                    exit('Error: The length of ' . $arg . ' is not matched');
                                }
                                break;
                            case 'func':
                                $call = preg_split('/[\:]+|\-\>/i', $value);
                                if (count($call) == 1) {
                                    $data[$arg] = call_user_func($call['0'], $data[$arg]);
                                } else {
                                    $data[$arg] = call_user_func_array(array(
                                        $call['0'],
                                        $call['1']
                                    ), array(
                                        $data[$arg]
                                    ));
                                }
                                break;
                        }
                    }
                }
            }
        }
        return $data;
    }
}