<?php
/**
 * Copyright: Copyright (c) 2018 https://www.xingchenw.cn All rights reserved.
 * Author Tan 349508017@qq.com
 */

namespace compass\cores\log\driver;


use compass\cores\log\LogBase;
use compass\cores\log\Loginterface;

class Seaslog extends LogBase implements Loginterface
{
    private static $instance;
    private static $seaslog;
    private function __construct()
    {
        if(!extension_loaded('seaslog')){
            dd('seaslog 扩展不存在');
        }
        if(!self::$seaslog){
            self::$seaslog=new \Seaslog();
        }
        $this->setParam();
        \Seaslog::setBasePath($this->logDir);
        \Seaslog::setLogger(date('Ym'));
    }
    public static function instance(){
        if(is_null(self::$instance)){
            self::$instance=new self();
        }
        return self::$instance;
    }

    public function write($content, $pattern='log')
    {
        \Seaslog::$pattern(SEASLOG_INFO,$content);

    }
    public function cache($content)
    {
        global $log;
        $log[]=$content;
    }

    public function save()
    {
        global $log;
        \Seaslog::Log(SEASLOG_INFO,print_r($log,true));
    }
}