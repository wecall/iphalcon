<?php

use extend\IExtendBase as IBase;

abstract class ExtendController extends BaseController implements IBase {
	public $settings;

	/**
     * 默认初始化函数,继承自基类
     */
    public function onConstruct() {
        $this->settings = $this->settings();
    }

	public function settings() {
        return [];
    }

    /**
     * 获取该数据对象的属性值
     */
    protected function getSchemal($model) {
        $metaData = $model->getModelsMetaData();
        return $metaData->getAttributes($model);
    }

    /**
     * 抽出属于数据库的字段信息
     */
    protected function validate($model) {
        //校验传输的数据
        $params = $this->formatRequestParam();
        $schema = $this->getSchemal($model);

        $dataHash = array();
        foreach ($params as $key => $val) {
            if (!in_array($key, $schema)) {
                continue;
            }

            $val = trim($val);
            if ('' == $val) {
                continue;
            }

            $dataHash[$key] = $val;
        }

        return $dataHash;
    }

    /**
     * 数据添加
     */
    public function add() {
        $model    = $this->settings['model'];
        $dataHash = $this->validate($model);

        if (!count($dataHash)) {
            throw new HTTPException('10002');
        }

        if (method_exists($this, 'hook_before_add')) {
            $dataHash = call_user_func(array($this, 'hook_before_add'), $dataHash);
        }

        foreach ($dataHash as $key => $val) {
            $model->{$key} = $val;
        }
        
        if (! $model->save()) {
            return new HTTPException('10003');
        }
        
        $id = $this->getlastInsertId();
        
        if (method_exists($this, 'hook_after_add')) {
            $dataHash['id'] = $id;
            call_user_func(array($this, 'hook_after_add'), $dataHash);
        }
        
        return ['id' => $id, "data" => $model->toArray()];
    }

    /**
     * 数据编辑
     */
    public function edit($id) {

    	$dataHash[$this->settings['pk']] = $id ;

        $objHash = $this->_getObjData($dataHash);
        if (method_exists($this, 'hook_before_edit')) {
            $dataHash = call_user_func(array($this, 'hook_before_edit'), $dataHash);
        }
        
        foreach ($dataHash as $key => $val) {
            $objHash->{$key} = $val;
        }
        
        if (!$objHash->save()) {
            throw new HTTPException('10006');
        }
        
        if (method_exists($this, 'hook_after_edit')) {
            $dataHash['id'] = $dataHash['trigger_id'];
            $dataHash = call_user_func(array($this, 'hook_after_edit'), $dataHash);
        }

        return ["data" => $objHash->toArray()];
    }

    /**
     * 数据更新
     */
    public function update($id) {

    	$dataHash[$this->settings['pk']] = $id ;

        $objHash = $this->_getObjData($dataHash);
        if (method_exists($this, 'hook_before_update')) {
            $dataHash = call_user_func(array($this, 'hook_before_update'), $dataHash);
        }

        foreach ($dataHash as $key => $val) {
            $objHash->{$key} = $val;
        }

        if (!$objHash->save()) {
            throw new HTTPException('10007');
        }

        $affectedRows = $this->settings['model']->getWriteConnection()->affectedRows();
        return ['affectedRows' => $affectedRows];
    }

    /**
     * 删除数据
     */
    public function delete($id) {

        $model  = $this->settings['model'];
        $delKey = $this->settings['pk'];

        if (!isset($delKey) || !isset($id)) {
            throw new HTTPException('10004');
        }

        //校验数据字段
        $schema = $this->getSchemal($model);
        if (!in_array($delKey, $schema)) {
            throw new HTTPException('10008');
        }

        $dataHash[$delKey] = $id;
        $model->find("{$delKey}='{$id}'")->delete();
        $affectedRows = $model->getWriteConnection()->affectedRows();
        if (!$affectedRows) {
            $affectedRows = 0;
        }
        if (method_exists($this, 'hook_after_delete')) {
            $affectedRows += call_user_func_array(array($this, 'hook_after_delete'), array('pkAffected' => $affectedRows, 'data' => $dataHash));
        }

        return ['affectedRows' => $affectedRows];
    }

    /**
     * 根据主键查找
     */
    public function findById($id) {
        
        $dataObj = $this->settings['model']->findFirst(array($this->settings['pk'] . "= '{$id}'"));
        if (!$dataObj || $dataObj->count() <= 0) {
            throw new HTTPException('10002');
        }

        $data = $dataObj->toArray();
        if (method_exists($this, 'hook_after_findbyid')) {
            $data = call_user_func(array($this, 'hook_after_findbyid'), $data);
        }
        
        return $data;
    }

    /**
     * 搜索相关记录
     */
    public function search(){
        $params = $this->formatRequestParam();
        $order  = isset($params["iorder"]) ? $params["iorder"] : $this->settings['pk'];

        // 查询条件
        $condition = "";
        if (method_exists($this, 'hook_before_search')) {
            $condition = call_user_func(array($this, 'hook_before_search'),$params);
        }
        $dataObj = $this->settings['model']->find(array(
            $condition,
            'order' => $order,
            'offset'=> isset($params["offset"]) ? $params["offset"] : GlobalConsts::PAGE_ZERO,
            'limit' => isset($params["limit"])  ? $params["limit"]  : GlobalConsts::PAGE_SIZE,
        ));

        if (!$dataObj || $dataObj->count() <= 0) {
            throw new HTTPException('10002');
        }

        $resultData = $dataObj->toArray();
        if (method_exists($this, 'hook_after_search')) {
            $resultData = call_user_func(array($this, 'hook_after_search'), $resultData);
        }

        return $resultData;
    }

    /**
     * 查询所有记录
     */
    public function findAll() {
        $params = $this->formatRequestParam();
        
        $order = isset($params["iorder"]) ? $params["iorder"] : $this->settings['pk'];
        
        // 查询条件
        $condition = "";
        if (method_exists($this, 'hook_before_findall')) {
            $condition = call_user_func(array($this, 'hook_before_findall'),$params);
        }
        $dataObj = $this->settings['model']->find(array(
    		$condition,
    		'order' => $order,
    		'offset'=> isset($params["offset"]) ? $params["offset"] : GlobalConsts::PAGE_ZERO,
    		'limit' => isset($params["limit"]) ? $params["limit"] : GlobalConsts::PAGE_SIZE,
        ));

        if (!$dataObj || $dataObj->count() <= 0) {
            throw new HTTPException('10002');
        }

        $resultData = $dataObj->toArray();
        if (method_exists($this, 'hook_after_findall')) {
            $resultData = call_user_func(array($this, 'hook_after_findall'), $resultData);
        }

        return $resultData;
    }

    /**
     * 获取当前对象插入的Id
     */
    public function getlastInsertId(){
        $model = $this->settings['model'];
    	return $model->getWriteConnection()->lastInsertId($model->getSource());
    }

    /**
     * 根据主键获取数据对象
     */
    private function _getObjData($dataHash) {
        if (!count($dataHash)) {
            throw new HTTPException('10002');
        }
        
        if (!array_key_exists($this->settings['pk'], $dataHash)) {
            throw new HTTPException('10004');
        }
        
        $condition = sprintf("%s='%s'",$this->settings['pk'],$dataHash[$this->settings['pk']]);
        $objHash = $this->settings['model']->findFirst(array($condition));

        if (!$objHash || $objHash->count() <= 0) {
            throw new HTTPException('10005');
        }

        return $objHash;
    }

}