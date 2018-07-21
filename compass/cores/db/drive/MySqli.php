<?php
/**
 * Copyright: Copyright (c) 2018 https://www.xingchenw.cn All rights reserved.
 * Author Tan 349508017@qq.com
 */

namespace compass\cores\db\drive;


use compass\cores\db\DatabaseBase;
use compass\cores\db\DbInterface;

class MySqli extends DatabaseBase implements DbInterface
{
    //保存数据库实例
    private static $db=null;
    private static $instance=null;
    private $host;
    private $username;
    private $passwd;
    private $port;
    private $dbName;
    private $charset;
    private $error;
    private function __construct($params=array())
    {
        //读取参数
        if(is_array($params)){
            $this->host=isset($params['host'])?$params['host']:'localhost';
            $this->username=isset($params['username'])?$params['username']:'root';
            $this->passwd=isset($params['password'])?$params['password']:'';
            $this->port=isset($params['port'])?$params['port']:3306;
            $this->dbName=isset($params['dbName'])?$params['dbName']:'test';
            $this->charset=isset($params['charset'])?$params['charset']:'utf8';
        }else{
            return false;
        }
        $this->connect();
        $this->setCharset();
    }
    //连接数据库
    private function connect(){
        if(is_null(self::$db)){
            self::$db=mysqli_connect($this->host,$this->username,$this->passwd,$this->dbName,$this->port);
        }
    }
    //设置客户端字符集
    private function setCharset(){
        mysqli_set_charset(self::$db,$this->charset);
    }
    private function __clone()
    {
        // TODO: Implement __clone() method.
    }
    public static function instance($params=array()){
        if(is_null(self::$instance)){
            self::$instance=new self($params);
        }
        return self::$instance;
    }
    public function insert($sql=null){
        if(!is_null($sql)){
            $result=mysqli_query(self::$db,$sql);
            if($result){
                return mysqli_insert_id($result);
            }else{
                $this->error=mysqli_errno(self::$db).': '.mysqli_error(self::$db);
            }
        }
    }
    public function delete($sql=null)
    {
        if(!is_null($sql)){
            $result=mysqli_query(self::$db,$sql);
            if($result){
                return mysqli_affected_rows($result);
            }else{
                $this->error=mysqli_errno(self::$db).': '.mysqli_error(self::$db);
            }
        }
    }
    public function update($sql=null)
    {
        if(!is_null($sql)){
            $result=mysqli_query(self::$db,$sql);
            if($result){
                return mysqli_affected_rows($result);
            }else{
                $this->error=mysqli_errno(self::$db).': '.mysqli_error(self::$db);
            }
        }
    }

    public function select($sql=null)
    {
        if(!is_null($sql)){
            $result=mysqli_query(self::$db,$sql);
            if($result){
                return mysqli_fetch_all($result);
            }else{
                $this->error=mysqli_errno(self::$db).': '.mysqli_error(self::$db);
            }
        }
    }
    public function getError(){
        return $this->error;
    }
    public function close(){
        mysqli_close(self::$db);
    }
}