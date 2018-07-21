在框架根目录下创建buffer缓存文件夹  并赋予777权限
Linux下需要确保 view下文件夹与控制器名称一致  
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
支持链式操作  
$model->where('id=1')->field('id,title')->order('id desc')->limit(9)->select();  
新增  
$model->insert(['field'=>'data']);  
删除  
$model->delete($id);
