<?php
/**
 * Copyright: Copyright (c) 2018 https://www.xingchenw.cn All rights reserved.
 * Author Tan 349508017@qq.com
 */

namespace compass\cores;


class Route
{
    //默认模块名
    public $module=null;
    //默认控制器名
    public $controller=null;
    //默认方法名
    public $action=null;
    private $configs=null;
    private $config=null;
    public function __construct(){
        #加载配置文件
        if(class_exists('\compass\cores\Config')){
            $this->configs=new Config();
            #获取基础配置文件
            $this->config=$this->configs['config'];
        }
        if(isset($_SERVER['REQUEST_URI']) && $_SERVER['REQUEST_URI'] !== '/'){
            //获取url地址栏模块,控制器,方法名
            $urls=explode('/',trim($_SERVER['REQUEST_URI'],'/'));
            #根据url地址栏查找相应模块,控制器,方法
            if(isset($urls[0])){
                $this->module=$urls[0];
                unset($urls[0]);
            }else{
                $this->module=$this->config['default_module'];
            }
            if(isset($urls[1])){
                $this->controller=ucfirst($urls[1]);
                unset($urls[1]);
            }else{
                $this->controller=$this->config['default_controller'];
            }
            if(isset($urls[2])){
                #解决pathinfo出现/index?id=1格式
                if(strstr($urls[2],'?')){
                    $res=explode('?',$urls[2]);
                    $this->action=$res[0];
                }else{
                    $this->action=$urls[2];
                }
                unset($urls[2]);
            }else{
                $this->action=$this->config['default_action'];
            }
            #将url多余部分转换为get
            $urlCount=count($urls)+3;
            $i=3;
            while($i<$urlCount){
                #判断url地址栏key是否有对应的value
                if(isset($urls[$i+1])){
                    $_GET[$urls[$i]]=$urls[$i+1];
                }
                $i+=2;
            }
        }else{
            #获取配置文件相应模块,控制器,方法
            $this->module=$this->config['default_module'];
            $this->controller=$this->config['default_controller'];
            $this->action=$this->config['default_action'];
        }
        Log::cache('module-->: '.$this->module.' controller-->: '.$this->controller.' action-->: '.$this->action);

    }
}