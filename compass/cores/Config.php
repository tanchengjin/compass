<?php
/**
 * Copyright: Copyright (c) 2018 https://www.xingchenw.cn All rights reserved.
 * Author Tan 349508017@qq.com
 */

namespace compass\cores;

#全局注册配置
class Config implements \ArrayAccess
{
    private $container=array();
    private $configPath;
    #设置配置文件路径
    public function __construct($path=null){
        if(is_null($path)){
            #判断CONFIGS配置目录常量是否存在
            if(defined('CONFIGS')){
                $this->configPath=CONFIGS;
            }else{
                $this->configPath='\\compass\configs';
            }
        }else{
            $this->configPath=$path;
        }
    }
    public function offsetExists($key)
    {
        return isset($this->container[$key]);
    }
    public function offsetSet($key, $value)
    {
        if(is_null($key)){
            $this->container[]=$value;
        }else{
            $this->container[$key]=$value;
        }
    }
    public function offsetGet($key)
    {
        if(!isset($this->container[$key])){
            #载入配置文件
            $filePath=$this->configPath.'/'.$key.'.php';
            $config=require $filePath;
            $this->container[$key]=$config;
        }
        return $this->container[$key];
    }
    public function offsetUnset($key)
    {
        unset($this->container[$key]);
    }
}