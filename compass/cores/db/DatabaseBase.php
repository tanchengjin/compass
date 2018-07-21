<?php
/**
 * Copyright: Copyright (c) 2018 https://www.xingchenw.cn All rights reserved.
 * Author Tan 349508017@qq.com
 */

namespace compass\cores\db;

//所有数据库类必须继承实现
use compass\cores\Config;

abstract class DatabaseBase
{
    public static $params=array(
        #数据库类型
        'type'=>'PDO',
        #PDO驱动
        'drive'=>'mysql',
        #数据库主机
        'host'=>'127.0.0.1',
        #数据库用户名
        'username'=>'root',
        #数据库密码
        'password'=>'',
        #数据库名称
        'dbName'=>'test',
        #端口
        'port'=>'3306',
        #字符集编码
        'charset'=>'utf8',
    );
    public function __construct()
    {
        //参数设置
        $this->setParams();
    }
    public function setParams(){
        //读取数据库配置参数并设置
        $configs=new Config();
        $database=$configs['database'];
        foreach ($database as $k=>$v){
            self::$params[$k]=$v;
        }
    }
    public function query($sql=null){
        if(is_null($sql)){
            return false;
        }
        $type=substr($sql,0,6);
        //判断查询模式
        if(strtolower($type) == 'select'){
            return $this->select($sql);
        }
        if(strtolower($type) == 'insert'){
            return $this->insert($sql);
        }
        //返回影响条数
        if(strtolower($type) == 'update'){
            return $this->update($sql);
        }
        //返回结果影响条数
        if(strtolower($type) == 'delete'){
            return $this->delete($sql);
        }
    }
}