<?php
/**
 * Copyright: Copyright (c) 2018 https://www.xingchenw.cn All rights reserved.
 * Author Tan 349508017@qq.com
 */

namespace compass\cores;

#App容器
class App
{
    private $_instance=array();
    private $_binding=array();
    private static $configs;

    /**
     * App constructor.
     * @param bool $autoRegister 是否将App配置文件中的类批量注册到容器中
     * @param array $param 手动指定要注册类
     * @param bool $filePath 配置文件路径
     */
    public function __construct($autoRegister=true,$param=array(),$filePath=false)
    {
        #如果为真则注册App配置文件中所有服务
        if($autoRegister){
            if(!$filePath){
                $filePath=COMPASS.'/configs/App.php';
            }
            //文件是否存在
            if(!is_file($filePath)){
                throw new \Exception('config file not exists');
            }
            if(!isset(self::$configs)){
                self::$configs=include COMPASS.'/configs/App.php';
            }
            if(empty($param)){
                #加载配置
                foreach (self::$configs as $key=>$v){
                    $this->_register($key,$v,true);
                }
            }else{
                //手动指定要绑定的数据
                foreach (self::$configs as $key=>$v){
                    foreach($param as $p){
                        if($key == $p){
                            $this->_register($key,$v,true);
                        }
                    }
                }
            }
        }

    }

    #向容器绑定实例
    public function bind($name,$class){
        $this->_register($name,$class);
    }
    //获取所需服务
    public function make($name,$params=array()){
        //如果服务已实例化则直接返回
        if(isset($this->_instance[$name])){
            return $this->_instance[$name];
        }
        //如果服务没有绑定直接返回
        if(!isset($this->_binding[$name])){
            return null;
        }
        //将服务进行实例化
        $service=$this->_binding[$name]['class'];
        $obj=null;
        //是否为匿名绑定
        if($service instanceof \Closure){
            $obj=call_user_func_array($service,$params);
        }elseif(is_string($service)){
            //如果参数不为空则启用反射
            if(empty($params)){
                $obj=new $service();
            }else{
                $ref=new \ReflectionClass($service);
                $obj=$ref->newInstance($params);
            }
        }
        $GLOBALS['App'][$name]=$obj;
        return $obj;
    }
    #查找$name是否在容器中
    #如果存在则返回$name在容器中的类型
    #如果不存在则返回null
    public function find($name){
        if(isset($this->_instance[$name])){
            return 'instance';
        }
        if(isset($this->_binding[$name])){
            return 'binding';
        }
        return null;
    }
    public function remove($name){
        if(self::find($name)){
            unset($this->_instance[$name],$this->_binding[$name]);
        }
    }

    /**
     * 将服务注入到容器中
     * @param $name 名称
     * @param $class 类
     * @param bool $instance true只需实例化一次
     */
    private function _register($name,$class,$instance=false){
        //注册的对象如果存在则移除原有对象
        $this->remove($name);
        //所注册的类,如果是一个对象,并且不是匿名函数则直接将其实例化放入到$_instance中
        if(!($class instanceof \Closure) && is_object($class)){
            $this->_instance[$name]=$class;
            $GLOBALS['App'][$name]=$class;
        }else{
            //instance如果为真则只需实例化一次,反之则每次都需要实例化
            $this->_binding[$name]=array('class'=>$class,'instance'=>$instance);
        }
    }
}