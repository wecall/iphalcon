<?php

class BeanUtil
{
    const STYLE_UNDERLINE = 1;
    const STYLE_LITTLE_CAMEL_CASE = 2;

    public static function magicCopy($obj1, $obj2 = null, $targetStyle = self::STYLE_LITTLE_CAMEL_CASE)
    {
        return BeanUtil::copy($obj1, $obj2, $targetStyle, true);
    }

    /**
     * 将$obj1的属性或者key=>$vale复制到$obj2
     */
    public static function copy($obj1, $obj2 = null, $targetStyle = self::STYLE_LITTLE_CAMEL_CASE, $forceCopy = false)
    {
        if(! is_object($obj1) && ! is_array($obj1)) {
            return false;
        }

        if($obj2 === null) {
            $obj2 = array();
        }

        if(! is_object($obj2) && ! is_array($obj2)) {
            return false;
        }

        $src1 = $obj1;
        //首先统一转换成数组
        if(is_object($obj1)) {
            $src1 = self::object2Array($obj1);
        }

        $rt = $obj2;
        if(is_array($obj2)) {
            $rt = self::copyToArray($src1, $obj2, $targetStyle);
        } else {
            $rt = self::copyToObject($src1, $obj2, $targetStyle, $forceCopy);
        }
        return $rt;
    }

    private static function copyToArray($array1, $array2, $targetStyle = self::STYLE_UNDERLINE)
    {
        $rt = $array2;
        foreach($array1 as $key => $value) {

            if($targetStyle == self::STYLE_LITTLE_CAMEL_CASE) {
                $targetKey = self::convertLittleCamelCaseStyle($key);
            } else {
                $targetKey = self::convertUnderlineStyle($key);
            }

            $rt[$targetKey] = $array1[$key];
        }
        return $rt;
    }

    private static function copyToObject($array1, $object2, $targetStyle = self::STYLE_UNDERLINE, $forceCopy = false)
    {
        $reflectObj = new ReflectionObject($object2);
        $props = array_keys(self::object2Array($object2, ReflectionProperty::IS_PUBLIC));

        foreach($array1 as $key => $val) {
            $targetKey = $targetStyle === self::STYLE_UNDERLINE ? self::convertUnderlineStyle($key) : self::convertLittleCamelCaseStyle($key);
            if($forceCopy || in_array($targetKey, $props)) {
                //public属性直接赋值
                $object2->$targetKey = $val;
            } else {
                $methodName = 'set' . ucfirst($targetKey);
                $methodNamePhpStyle = 'set' . ucfirst($key);
                $method = null;
                if($reflectObj->hasMethod($methodName)) {
                    $method = $reflectObj->getMethod($methodName);
                } elseif($reflectObj->hasMethod($methodNamePhpStyle)) {
                    $method = $reflectObj->getMethod($methodNamePhpStyle);
                }
                if($method != null && $method->isPublic()) {
                    $method->invoke($object2, $val);
                }
            }
        }
        return $object2;
    }

    public static function convetStyle($key, $targetStyle = self::STYLE_UNDERLINE)
    {
        if($targetStyle === self::STYLE_UNDERLINE) {
            return self::convertUnderlineStyle($key);
        } else {
            return self::convertLittleCamelCaseStyle($key);
        }
    }

    public static function convertUnderlineStyle($key)
    {
        //全部大写的，改成全部小写
        if(preg_match('#^[A-Z0-9_]+$#', $key)) {
            return strtolower($key);
        } else {

            $result = strtolower(preg_replace('/(?<!\b)(?=[A-Z])/', "_", ucfirst($key)));
            return preg_replace('/[_]+/', '_', $result);
        }
    }

    public static function convertLittleCamelCaseStyle($key)
    {
        $rs = preg_replace_callback("/(?:^|_)([a-z])/", function($r) { return strtoupper($r[1]); }, $key);
        return preg_replace_callback("/^(.)/", function($r) { return strtolower($r[1]); }, $rs);
    }

    /**
     * 对象转数组
     */
    public static function object2Array($obj, $filter = null, $removeNull = false)
    {
        $rt = array();
        if(! is_array($obj)) {
            $reflectObj = new ReflectionObject($obj);
            if($filter === null) {
                $filter = ReflectionProperty::IS_PRIVATE | ReflectionProperty::IS_PROTECTED | ReflectionProperty::IS_PUBLIC;
            }
            $props = $reflectObj->getProperties($filter);
            foreach($props as $prop) {
                $prop->setAccessible(true);
                $rt[$prop->getName()] = $prop->getValue($obj);
            }
        } else {
            $rt = $obj;
        }
        if($removeNull) {
            foreach($rt as $key => $val) {
                if($val === null) {
                    unset($rt[$key]);
                }
            }
        }
        return $rt;
    }
}