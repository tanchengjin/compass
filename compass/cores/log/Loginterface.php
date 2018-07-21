<?php
/**
 * Copyright: Copyright (c) 2018 https://www.xingchenw.cn All rights reserved.
 * Author Tan 349508017@qq.com
 */

namespace compass\cores\log;


interface Loginterface
{
    #写入日志 写入内容,日志级别
    public function write($content,$pattern='log');
    #将日志写入缓存
    public function cache($content);
    #将缓存中的日志保存
    public function save();
    #必须实现单例模式
    public static function instance();
}