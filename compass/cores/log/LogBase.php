<?php
/**
 * Copyright: Copyright (c) 2018 https://www.xingchenw.cn All rights reserved.
 * Author Tan 349508017@qq.com
 */

namespace compass\cores\log;
use compass\cores\Config;
#所有日志扩展类都必须继承此抽象类
abstract class LogBase
{
    //文件具体存储路径
    public $filePath;
    //日志文件存储文件夹路径
    public $logDir;
    //在子类的构造方法中实现此方法
    public function setParam()
    {
        //读取日志配置文件
        $configs=new Config();
        //读取日志配置文件中存储路径
        $this->logDir=$configs['log']['storage'];
    }

    /**
     * 检测文件夹与文件是否存在如不存在则创建
     * @param $fileDir 日志存放文件夹
     * @param string $pattern 日志级别
     */
    public function detection($fileDir,$pattern='log'){
        #获取年合月
        $date=date('Ym');
        #获取今日日期
        $day=date('d');
        $this->filePath=$fileDir.'/'.$date.'/'.$day.'_'.$pattern.'.log';
        //当前文件如果存在,则跳出本方法
        if(is_file($this->filePath)){
            return;
        }
        //设置日志存放路径
        $dir=$fileDir;
        //判断日志存放路径是否存在
        if(!is_dir($dir)){
            //创建日志文件夹
            mkdir($dir,0755);
        }

        $dir=$dir.'/'.$date;
        if(!is_dir($dir)){
            //创建年-月组成的文件夹
            mkdir($dir,0755);
        }
        $file=$dir.'/'.$day.'_'.$pattern.'.log';
        if(!is_file($file)){
            $f=fopen($file,'w');
            fclose($f);
        }
    }
    //在每条日志前面添加时间,并返回生成后的字符串
    public function createTimeInfo($content=''){
        $date=date('Y-m-d H:i:s');
        //加入时间戳
        $content=$date.':'.time().':'.$content."\n";
        return $content;
    }
}