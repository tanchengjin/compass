<?php
/**
 * Copyright: Copyright (c) 2018 https://www.xingchenw.cn All rights reserved.
 * Author Tan 349508017@qq.com
 */

namespace compass\cores\db;

//所有数据库类必须实现此接口
interface DbInterface
{
    #增
    public function insert();
    #删
    public function delete();
    #改
    public function update();
    #查
    public function select();
    #关闭数据库连接句柄
    public function close();
    #获取数据库错误信息
    public function getError();
}