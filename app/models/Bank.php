<?php


class Bank extends AbstractModel{

    /**
     *
     * @var integer
     */
    public $id;

    /**
     *
     * @var string
     */
    public $name;

    /**
     *
     * @var string
     */
    public $ename;

    /**
     * @var integer
     */
    public $isValid;
    
    /**
     *
     * @var string
     */
    public $updatedTime;

    /**
     *
     * @var string
     */
    public $createdTime;

    public function getSource(){
        return  parent::getSource(__CLASS__);
    }

    public function beforeSave()
    {
        $this->updatedTime = date('Y-m-d H:i:s');
    }

    public function beforeCreate()
    {
        $this->isValid     = 1;
        $this->createdTime = date('Y-m-d H:i:s');
    }

    /**
     * 根据 主键查询记录
     */
    public static function findRowById($id){
        return self::query()
                ->where("id = :id:")
                ->bind(array("id" => $id))
                ->order("id")
                ->execute();
    }

    /**
     * 根据条件查询语句
     */
    public static function findFirstByName($name){
        return self::find("isValid = '1' and ename LIKE '%".$name."' ");
    }
}
