<?php
/**
 * Copyright: Copyright (c) 2018 https://www.xingchenw.cn All rights reserved.
 * Author Tan 349508017@qq.com
 */

namespace compass\cores;

header('charset=utf8');
class Model
{
    //数据库实例
    public static $db;
    //数据库表名
    protected $table;
    //数据表参数
    protected static $params=array();
    //数据表修改参数
    protected static $Data=array();
    //条件
    private $where;
    //查询字段
    private $field;
    //获取条数
    private $limit;
    //排序
    private $order;
    private $orders;
    private $error;
    public function __construct(){
        if(!self::$db){
            self::$db=new Db();
        }
        if(!$this->table){
            throw new \Exception('table not find');
        }
    }

    /**
     * 插入数据库
     * @param array $datas 插入数据
     */
    public function insert($datas=array()){
        $key=null;
        $val=null;
        if(is_array($datas)){
            foreach($datas as $k=>$v){
                if(is_null($key)){
                    $key=$k;
                }else{
                    $key.=",{$k}";
                }
                if(is_null($val)){
                    $val="'$v'";
                }else{
                    $val.=",'{$v}'";
                }
            }
            $sql="INSERT INTO {$this->table} ($key) VALUES ($val)";
            $res=self::$db->query($sql);
            if($res !== false){
                //返回新增后的ID
                return $res;
            }else{
                //记录错误信息
                $this->error=self::$db->getError();
                return false;
            }
        }
    }

    /**
     * 删除数据表字段
     * @param array 删除多个|string 删除单个 $val 删除条件的值
     * @param string $index 默认id 字段
     */
    public function delete($val=null,$index='id'){
        //用于存储要删除的值
        $delete_id=null;
        if(is_array($val)){
            foreach($val as $k=>$v){
                if(is_null($delete_id)){
                    $delete_id=$v;
                }else{
                    $delete_id.=",{$v}";
                }
            }
            $sql="DELETE FROM {$this->table} WHERE {$index} IN($delete_id)";
        }elseif(is_string($val) || is_integer($val)){
            $sql="DELETE FROM {$this->table} WHERE {$index}={$val}";
        }elseif(is_null($val)){
            $sql="DELETE FROM {$this->table} {$this->where}";
        }
        $res=self::$db->query($sql);
        if($res !== false){
            //返回影响条数
            return $res;
        }else{
            //保存错误信息
            $this->error=self::$db->getError();
            return false;
        }
    }

    /**
     * 保存$model->字段=值  修改后的数据
     */
    public function save(){
        $value='';
        foreach(self::$Data as $k=>$v){
            var_dump($k.'=>'.$v);
            if($k == 'id'){
                $id=$v;
                unset($k);
                continue;
            }
            $value.=" {$k}='{$v}',";
        }
        $value=trim($value,',');
        $sql="update {$this->table} set $value WHERE id={$id} ";
        $res=self::$db->query($sql);
        if($res){
            $this->error=self::$db->getError();
        }
        //返回影响条数
        return $res;
    }
    //查询多条数据
    public function select(){
        //拼装sql语句
        $sql=$this->sql('select');
        return self::$params=self::$db->query($sql);
    }
    /**
     * 查询单条数据
     * @param null $val 查询条件
     * @param string $$key 默认查询id字段
     * @return mixed
     */
    public function get($val=null,$key='id'){
        if(!is_null($val)){
            $this->where=" WHERE {$key}={$val}";
            //拼装sql语句
            $sql=$this->sql('select');
            self::$params=self::$db->query($sql)[0];
            if(is_object(self::$params)){
                self::$params=get_object_vars(self::$params);
            }
            self::$Data['id']=self::$params['id'];
            return self::$params;
        }
    }

    /**
     * where条件
     * @param $sql 如id=1
     * @return $this
     */
    public function where($sql){
        if(is_null($this->where)){
            $this->where=' WHERE '.$sql;
        }else{
            $this->where.=' AND '.$sql;
        }
        return $this;
    }

    /**
     * 排序语句
     * @param $sql 如  order by id desc
     * @param string $sqls 通过两个条件进行排序id,desc
     * @return $this
     */
    public function order($sql,$sqls=''){
        if(!empty($sql) && !empty($sqls)){
            $this->order=$sql;
            $this->orders=$sqls;
        }elseif(!empty($sql)){
            $this->order=$sql;
        }
        return $this;
    }

    /**
     * 限制语句
     * @param $number 如 limit 1
     * @return $this
     */
    public function limit($number){
        if(!empty($number)){
            $this->limit=$number;
        }
        return $this;
    }

    /**
     * 查询字段
     * @param $field 如id,title,xxx
     * @return $this
     */
    public function field($field){
        $this->field=$field;
        return $this;
    }
    //拼装sql语句,适用于查询
    private function sql($sql){
        if($this->field){
            $sql.=' '.$this->field.' from '.$this->table;
        }else{
            $sql.=' * from '.$this->table;
        }
        if($this->where){
            $sql.=''.$this->where;
        }
        //拼装排序语句
        if(!empty($this->order) && !empty($this->orders)){
            $sql.=" ORDER BY {$this->order} {$this->orders}";
        }elseif($this->order){
            $sql.=" ORDER BY {$this->order}";
        }
        //拼装数据条数语句
        if($this->limit){
            $sql.=" LIMIT {$this->limit}";
        }
        return $sql;
    }
    public function __get($name)
    {
        if(is_object(self::$params)){
            if(isset(self::$params->$name)){
                return self::$params->$name;
            }else{
                return null;
            }
        }elseif(is_array(self::$params)){
            if(isset(self::$params[$name])){
                return self::$params[$name];
            }else{
                return null;
            }
        }
    }
    public function __set($name, $value)
    {
        if(is_object(self::$params)){
            if(isset(self::$params->$name)){
                self::$Data[$name]=$value;
            }else{
                return null;
            }
        }elseif(is_array(self::$params)){
            if(isset(self::$params[$name])){
                self::$Data[$name]=$value;
            }else{
                return null;
            }
        }
    }
    public function getError(){
        return $this->error;
    }
}