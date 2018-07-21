<?php
/**
 * Copyright: Copyright (c) 2018 https://www.xingchenw.cn All rights reserved.
 * Author Tan 349508017@qq.com
 */

namespace compass\cores;

class Db
{
    private static $db;
    private static $error;
    private $where;
    public function __construct($params=array()){
        if(!empty($params)){
            $className="\compass\cores\db\drive\\".$params['type'];
            self::$db=$className::instance($params);
        }else{
            $config=new Config();
            $database=$config['database'];
            $className="\compass\cores\db\drive\\".$database['type'];
            self::$db=$className::instance($database);
        }
    }
    public function query($query=''){
        Log::cache('sql: -->'.$query);
        $result=self::$db->query($query);
        if($result === false){
            //执行失败保存失败信息
            self::$error=self::$db->getError();
        }
        return $result;
    }
    public function where($sql){
        $this->where.=' '.$sql;
    }
    //调取失败信息
    public function getError(){
        return self::$error;
    }
}