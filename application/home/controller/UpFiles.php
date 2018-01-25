<?php
namespace app\home\controller;
use think\Db;
use think\Request;
use think\Controller;
use think\Session;
class UpFiles extends Common {

    public function upload() {
        // 获取上传文件表单字段名
        $fileKey = array_keys(request()->file());
        // 获取表单上传文件
        $file = request()->file($fileKey['0']);
        // 移动到框架应用根目录/uploads/ 目录下
        $info = $file->move(ROOT_PATH  . 'uploads');
        if ($info) {
            $result['code'] = 1;
            $result['info'] = '图片上传成功!';
            $path = str_replace('\\', '/', $info->getSaveName());
            $result['url'] = '/uploads/' . $path;
            $title = explode('.',$info->getInfo()['name']) ;//获取原图名称
            array_pop($title);
            $result['title'] = $title;
            return $result;
        } else {
            // 上传失败获取错误信息
            $result['code'] = 0;
            $result['info'] = '图片上传失败!';
            $result['url'] = '';
            return $result;
        }
    }

    public function file() {
        $fileKey = array_keys(request()->file());
        // 获取表单上传文件 例如上传了001.jpg
        $file = request()->file($fileKey['0']);
        // 移动到框架应用根目录/uploads/ 目录下
        $info = $file->move(ROOT_PATH  . 'uploads');

        if ($info) {
            $result['code'] = 1;
            $result['info'] = '文件上传成功!';
            $path = str_replace('\\', '/', $info->getSaveName());

            $result['url'] = '/uploads/' . $path;
            $result['ext'] = $info->getExtension();
            $result['size'] = byte_format($info->getSize(), 2);
            return $result;
        } else {
            // 上传失败获取错误信息
            $result['code'] = 0;
            $result['info'] = '文件上传失败!';
            $result['url'] = '';
            return $result;
        }
    }

    public function pic() {
        // 获取上传文件表单字段名
        $fileKey = array_keys(request()->file());
        // 获取表单上传文件
        $file = request()->file($fileKey['0']);
        // 移动到框架应用根目录/uploads/ 目录下
        $info = $file->move(ROOT_PATH .  'uploads');
        if ($info) {
            $result['code'] = 1;
            $result['info'] = '图片上传成功!';
            $path = str_replace('\\', '/', $info->getSaveName());
            $result['url'] = '/uploads/' . $path;
            return $result;
        } else {
            // 上传失败获取错误信息
            $result['code'] = 0;
            $result['info'] = '图片上传失败!';
            $result['url'] = '';
            return $result;
        }
    }

    //编辑器图片上传
    public function editUpload() {
        // 获取表单上传文件 例如上传了001.jpg
        $file = request()->file('file');
        // 移动到框架应用根目录/uploads/ 目录下
        $info = $file->move(ROOT_PATH  . 'uploads');
        if ($info) {
            $result['code'] = 0;
            $result['msg'] = '图片上传成功!';
            $path = str_replace('\\', '/', $info->getSaveName());
            $result['data']['src'] =  '/uploads/' . $path;
            $result['data']['title'] = $path;
            return $result;
        } else {
            // 上传失败获取错误信息
            $result['code'] = 1;
            $result['msg'] = '图片上传失败!';
            $result['data'] = '';
            return $result;
        }
    }

    //多图上传
    public function upImages() {
        // 获取表单上传文件 例如上传了001.jpg
        $file = request()->file('file');
        // 移动到框架应用根目录/uploads/ 目录下
        $info = $file->move(ROOT_PATH  . 'uploads');
        if ($info) {
            $result['status'] = 200;
            $result['msg'] = '图片上传成功!';
            $path = str_replace('\\', '/', $info->getSaveName());
            $result['url'] = '/uploads/' . $path;
            return $result;
        } else {
            // 上传失败获取错误信息
            $result['status'] = 0;
            $result['msg'] = '图片上传失败!';
            $result['dir'] = '';
            return $result;
        }
    }


    public function headpic() {
        // 获取上传文件表单字段名
        $fileKey = array_keys(request()->file());
        // 获取表单上传文件
        $file = request()->file($fileKey['0']);
        // 移动到框架应用根目录/uploads/ 目录下
        $info = $file->move(ROOT_PATH .  'uploads');
        if ($info) {
            $result['code'] = 1;
            $result['info'] = '图片上传成功!';
            $path = str_replace('\\', '/', $info->getSaveName());
            $result['url'] = '/uploads/' . $path;
            $user=session::get('user');
            Db::name('user')->where('id',$user['id'])->update(['headpic'=>$result['url']]);
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
