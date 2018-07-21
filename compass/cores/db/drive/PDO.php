<?php
/**
 * Copyright: Copyright (c) 2018 https://www.xingchenw.cn All rights reserved.
 * Author Tan 349508017@qq.com
 */

namespace compass\cores\db\drive;


use compass\cores\db\DatabaseBase;
use compass\cores\db\DbInterface;

class PDO extends DatabaseBase implements DbInterface
{
    private static $instance;
    private static $db;
    //错误信息
    private $error;
    private function __construct($params)
    {
        parent::__construct();
        //连接数据库
        $this->connect();
    }
    public static function instance($params=array()){
        if(is_null(self::$instance)){
            self::$instance=new self($params);
        }
        return self::$instance;
    }
    //数据库连接
    private function connect(){
        try{
            $dsn=self::$params['drive'].':host='.self::$params['host'].';dbname='.self::$params['dbName'];
            self::$db=new \PDO($dsn,self::$params['username'],self::$params['password']);
            //设置编码
            $charset=self::$params['charset'];
            self::$db->query("set names $charset");
        }catch(\Exception $e){
            echo $e->getMessage();
        }
    }
    #插入成功后返回插入成功的ID号
    public function insert($sql=null)
    {
        if(!is_null($sql)){
            $result=self::$db->query($sql);
            if($result === false){
                $this->error=self::$db->errorInfo()[0].': '.self::$db->errorInfo()[2];
                return false;
            }
            return self::$db->lastInsertId();
        }
    }
    public function delete($sql=null)
    {
        if(!is_null($sql)){
            $result=self::$db->query($sql);
            if($result === false){
                $this->error=self::$db->errorInfo()[0].': '.self::$db->errorInfo()[2];
                return false;
            }
            return $result->rowCount();
        }
    }
    #更新成功后返回受SQL语句影响的行数
    public function update($sql=null)
    {
        if(!is_null($sql)){
            $result=self::$db->query($sql);
            if($result === false){
                $this->error=self::$db->errorInfo()[0].': '.self::$db->errorInfo()[2];
                return false;
            }
            return $result->rowCount();
        }
    }

    public function select($sql=null)
    {
        if(!is_null($sql)){
            $result=self::$db->query($sql);
            if($result === false){
                $this->error=self::$db->errorInfo()[0].': '.self::$db->errorInfo()[2];
                return false;
            }
            return $result->fetchAll(\PDO::FETCH_CLASS);
        }
    }
    public function close()
    {
        self::$db=null;
    }
    public function getError(){
        return $this->error;
    }
}