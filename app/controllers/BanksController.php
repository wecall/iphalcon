<?php

use Phalcon\Mvc\Controller;

class BanksController extends ExtendController{

    
    /**
     * 控制器配置
     * @return array
     */
    public function settings()
    {
        return array('pk' => 'id', 'model' => new Bank());
    }

    /**
     * 数据更新
     */
    public function hook_before_update($datahash){
        $params = $this->formatRequestParam();

        if (isset($params["name"])) {
            $datahash["name"] = $params["name"];
        }
        if (isset($params["ename"])) {
            $datahash["ename"] = $params["ename"];
        }
        return $datahash;
    }


   public function hook_before_edit($datahash){
        $params = $this->formatRequestParam();

        if (isset($params["name"])) {
            $datahash["name"] = $params["name"];
        }
        if (isset($params["ename"])) {
            $datahash["ename"] = $params["ename"];
        }
        return $datahash;
   }


    // /**
    //  * 获取所有的银行信息 
    //  */
    // public function index(){
    //     $params = $this->formatRequestParam();   

    //     $bankObj = Bank::find(array(
    //         'isValid=1',
    //         'order' => "id desc",
    //     ));
    //     if (!$bankObj) {
    //         throw new HTTPException('10001');
    //     }
    //     return $bankObj->toArray();
    // }

    // /**
    //  * 根据关键字搜索 
    //  */
    // public function search($keyword){
    //     $condition = array();
    //     $condition[] = 'isValid=1';

    //     if ($keyword) {
    //         $condition[] = sprintf("( name like '%%%s%%' or ename LIKE '%%%s%%')",$keyword,$keyword);
    //     }

    //     $bankObj = Bank::find(array(
    //         join(' AND ',$condition),
    //         'order' => "id",
    //     ));

    //     if (!$bankObj) {
    //         throw new HTTPException('10001');
    //     }

    //     return $bankObj->toArray();
    // }

    // /**
    //  * 根据主键查询 
    //  */
    // public function findById($id){

    //     $bankObj = Bank::findRowById($id);
        
    //     if (!$bankObj) {
    //         throw new HTTPException('10001');
    //     }

    //     return $bankObj->toArray();
    // }

    // /**
    //  * 添加银行信息
    //  */
    // public function add(){
    //     $params = $this->formatRequestParam();

    //     $bank = new Bank();
    //     $bank->name ='花旗银行';
    //     $bank->ename ='bank';
    //     $bank->isValid = 1;
    //     return $bank->create();
    //     // return $bank->getLastInsertId();
    // }

    // public function update(){

    // }


    // public function delete(){

    // }
}
