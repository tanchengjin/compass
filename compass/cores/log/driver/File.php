<?php
/**
 * Copyright: Copyright (c) 2018 https://www.xingchenw.cn All rights reserved.
 * Author Tan 349508017@qq.com
 */

namespace compass\cores\log\driver;


use compass\cores\Config;
use compass\cores\log\LogBase;
use compass\cores\log\Loginterface;

class File extends LogBase implements Loginterface
{
    private static $instance=null;
    private function __construct($logDir)
    {
        //调用父类方法进行日志配置配置文件设置
        $this->setParam();
    }
    private function __clone()
    {
        // TODO: Implement __clone() method.
    }

    /**
     *
     * @param $logDir 日志文件存放路径
     * @return File|null
     */
    public static function instance($logDir=null){
        if(is_null(self::$instance)){
            if(is_null($logDir)){
            }
            self::$instance=new self($logDir);
        }
        return self::$instance;
    }

    /**
     * 写入文件
     * @param string $content 写入内容
     */
    public function write($content='',$pattern='log')
    {
        //生成日志文件
        $this->detection($this->logDir,$pattern);
        $content=$this->createTimeInfo($content);
        //追加写入文件
        file_put_contents($this->filePath,$content,FILE_APPEND);
    }
    //写入内存中
    public function cache($content=''){
        global $log;
        $content=$this->createTimeInfo($content);
        $log[]=$content;
    }
    public function save(){
        global $log;
        //生成日志文件
        $this->detection($this->logDir);
        file_put_contents($this->filePath,print_r($log,true),FILE_APPEND);
    }
}