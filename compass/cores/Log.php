<?php
/**
 * Copyright: Copyright (c) 2018 https://www.xingchenw.cn All rights reserved.
 * Author Tan 349508017@qq.com
 */

namespace compass\cores;


use compass\cores\log\driver\File;

class Log
{
    private static $instance;
    private static $className;
    private function __construct()
    {
        $configs=new Config();
        self::$className='\\compass\\cores\\log\\driver\\'.$configs['log']['drive'];
    }
    private function __clone()
    {
        // TODO: Implement __clone() method.
    }

    public static function instance(){
        if(is_null(self::$instance)){
            self::$instance=new self();
        }
        return self::$instance;
    }
    //写入日志
    public static function write($content){
        self::instance();
        $class=self::$className;
        $class::instance()->write($content);
    }
    //将日志存入到缓存中
    public static function cache($content){
        self::instance();
        $class=self::$className;
        $class::instance()->cache($content);
    }
    //将cache中保存的数据存入到文件中
    public static function save(){
        self::instance();
        $class=self::$className;
        $class::instance()->save();
    }
}