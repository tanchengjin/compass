<?php
/**
 * Copyright: Copyright (c) 2018 https://www.xingchenw.cn All rights reserved.
 * Author Tan 349508017@qq.com
 */

namespace compass;


use compass\cores\App;
use compass\cores\Route;

class Main
{
    public static function run(){
        #批量注册服务
        $app=new App();
        $config=$app->make('config',COMPASS.'/language');
        #获取框架语言包
        $GLOBALS['lang']=$config['zh-cn'];
        #获取基础

        $route=$app->make('route');
        #模块
        $module=str_replace('\\','/',APP.'/'.$route->module);
        #获取控制器所在路径
        $controller_file=$module.'/'.'controller/'.$route->controller.'.php';
        #模块是否存在
        if(!is_dir($module)){
           throw new \Exception($GLOBALS['lang']['module_not_exists'].$module);
        }
        #控制器是否存在
        if(is_file($controller_file)){
            $controller_file= 'app\\'.$route->module.'\\controller\\'.$route->controller;
            $controller=new $controller_file();
        }else{
            throw new \Exception($GLOBALS['lang']['ctrl_not_exists'].$controller_file);
        }
        #获取方法名
        $action=$route->action;
        #检测方法是否存在
        if(method_exists($controller,$route->action)){
            #调用控制器对应方法
            $controller->$action();
        }else{
            throw new \Exception($GLOBALS['lang']['action_not_exists'].$controller_file.'->'.$action.'()');
        }
    }
}