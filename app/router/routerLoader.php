<?php
return call_user_func(function(){
    $collections = array();
    
    $collectionFiles = scandir(dirname(__FILE__) . '/collections');
    foreach($collectionFiles as $collectionFile){
        $pathinfo = pathinfo($collectionFile);
        //Only include php files
        if($pathinfo['extension'] === 'php'){
            // The collection files return their collection objects, so mount
            // them directly into the router.
            $collections[] = include_once(dirname(__FILE__) .'/collections/' . $collectionFile);
        }
    }
    return $collections;
});
