|---compass  核心文件夹

|---application  应用文件夹

|---public   web访问入口

|------ static  资源目录

|------index.php 入口文件

|---model   模型文件目录

|---buffer   缓存目录


===========================

模型使用方法

$model=new model();  
$model->field='xxx';  
保存修改的数据  
$model->save();    


查询  
$model->select();  
新增  
$model->insert(['field'=>'data']);  
删除  
$model->delete($id);