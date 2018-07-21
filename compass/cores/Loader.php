<?php
/**
 * Copyright: Copyright (c) 2018 https://www.xingchenw.cn All rights reserved.
 * Author Tan 349508017@qq.com
 */

namespace compass\cores;


class Loader
{
    public static $fileMapper=[];
    //自动加载类
    public static function autoload($fileName){
        if(isset($fileMapper[$fileName])){
            return;
        }
        if(strstr($fileName,'app')){
        $fileName=str_replace('app','application',$fileName);
        }
        $class=str_replace('\\','/',ROOT_PATH.'/'.$fileName.'.php');
        if(is_file($class)){
            include $class;
            //==================================
            //日志写入
            global $log;
            $date=date('Y-m-d H:i:s');
            //加入时间戳
            $log[]=$date.':'.time().':'.$class."\n";
            //=====================================
            self::$fileMapper[$fileName]=$fileName;
        }else{
            throw new \Exception($GLOBALS['lang']['file_not_exists'].$class);
        }
    }
}