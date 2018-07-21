<?php
/**
 * Copyright: Copyright (c) 2018 https://www.xingchenw.cn All rights reserved.
 * Author Tan 349508017@qq.com
 */

$start=current_time();
require(ROOT_PATH.'/compass/constants.php');
require(ROOT_PATH.'/compass/cores/Loader.php');
require(COMPASS.'/function.php');
require(COMPASS.'/cores/Log.php');

spl_autoload_register('\compass\cores\Loader::autoload');
require 'cores/template/drive/smarty/Smarty.class.php';
//判断是否开启调试模式
$config=new \compass\cores\Config();
$debug=$config['config']['debug'];
ini_set('display_errors',$debug);
global $log;
\compass\Main::run();


$end=current_time();
$runtime='runtime[框架运行时间]'.(number_format($end-$start,3)*1000).'ms[毫秒]';
\compass\cores\Log::cache($runtime);
\compass\cores\Log::save();
function current_time(){
    list($usec,$use)=explode(' ',microtime());
    return (float)$usec+(float)$use;
}