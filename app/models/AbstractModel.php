<?php

class AbstractModel extends \Phalcon\Mvc\Model
{
    protected static $_cache = array();
    protected static $_dbpre = "admin_";
    protected static $_cachepre = "model:cache:";
    /**
     * 设置数据库的读写分离
     */
    public function initialize()
    {
        try {
            $dbWrite = 'db_master';
            $dbRead = 'db_slave';
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
    
    /**
     * 定位数据库名字
     */
    public function getSource($classname){

        return self::$_dbpre.strtolower($classname);
    }

    /**
    * 根据查询条件拼接缓存的key
    */
    protected static function _createKey($parameters){  
        $uniqueKey = array();
        if (is_array($parameters) && count($parameters) > 0) {
            foreach ($parameters as $key => $value) {
                if (is_scalar($value)) {
                    $uniqueKey[] = $key . ':' . $value;
                } else {
                    if (is_array($value)) {
                        $uniqueKey[] = $key . ':[' . self::_createKey($value) .']';
                    }
                }
            }
        }
                
        return self::$_cachepre.join(',', $uniqueKey);
    }

    /**
     * 重写find ++++ cache 
     */
    public static function find($parameters=null){
        $key = self::_createKey($parameters);

        if (!isset(self::$_cache[$key])) {
          self::$_cache[$key] = parent::find($parameters);
        }

        return self::$_cache[$key];
    }

    /**
     * 重写 findFirst ++++ cache 
     */
    public static function findFirst($parameters=null){
         $key = self::_createKey($parameters);

        if (!isset(self::$_cache[$key])) {
          self::$_cache[$key] = parent::findFirst($parameters);
        }

        //Return the result in the cache
        return self::$_cache[$key];
    }

    /**
     * 分页获取数据
     * 数据库查询语句 isValid=1 id desc
     * @param $page 当前页码
     * @param $size 每页显示数据
     * @return 返回数据对象
     */
    public static function findByPage($page=1,$size=20){
        $pageArr = \Tools::converPage($page,$size);

        return parent::find(
            array(
                "conditions" => "isValid = '1' ",
                "order" => "id",
                "offset"=> $pageArr["offset"],
                "limit" => $pageArr["limit"]
            )
        );
    }

    /**
     * 查询PHQL
     * @param $phql "SELECT * FROM Bank where ename=:ename:"
     * @param $parameters array("ename"=>'bank')
     * @return  对象 
     */
    public static function exec($phql,$parameters = array()){
        if (count($parameters) > 0) {
            return getDI('modelsManager')->executeQuery($phql,$parameters);
        }else{
            return getDI('modelsManager')->executeQuery($phql);
        }
    }

    /**
     * 输出执行的最后一条SQL语句
     */
    public static function writeSql(){
        return getDI('profiler')->getLastProfile()->getSQLStatement();
    }
}