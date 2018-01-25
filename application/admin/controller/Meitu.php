<?php
namespace app\admin\controller;
use think\Db;
use think\Request;
use think\Session;
use think\Controller;
use think\Config;
class Meitu extends Controller
{
    public function index()
    {

     return $this->fetch();
    }


    public function upload() {
        // 获取上传文件表单字段名
        $fileKey = array_keys(request()->file());
        // 获取表单上传文件
        $file = request()->file($fileKey['0']);
        // 移动到框架应用根目录/uploads/ 目录下
        $info = $file->move(ROOT_PATH  . 'uploads');
        if ($info) {
            $result['status'] = 1;
            $result['info'] = '图片上传成功!';
            $path = str_replace('\\', '/', $info->getSaveName());
            $result['url'] = '/uploads/' . $path;
            $title = explode('.',$info->getInfo()['name']) ;//获取原图名称
            array_pop($title);
            $url=$result['url'];
            $result['img'] = $title;
            Db::name('test')->insert(['pic'=>$url]);
            return $result;
        } else {
            // 上传失败获取错误信息
            $result['code'] = 0;
            $result['info'] = '图片上传失败!';
            $result['url'] = '';
            return $result;
        }
    }


}
