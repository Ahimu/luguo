<?php
namespace app\admin\controller;
use think\Db;
use think\Request;
use think\Session;
use think\Controller;
use think\Config;

class Index extends Common
{
    public function index()

    {

    $admin=session::get('admin');
    $this->assign('admin',$admin);
      return $this->fetch();
    }
    public function main()
    {
      return $this->fetch();
    }

    public function clear(){
        $R = RUNTIME_PATH;
        if($this->_deleteDir($R)) {
           $result['info'] = '清除缓存成功!';
           $result['status'] = 1;
        }else {
           $result['info'] = '清除缓存失败!';
           $result['status'] = 0;
        }
        $result['url'] = url('admin/index/index');
        return json($result);
    }
    private function _deleteDir($R)
    {
       $handle = opendir($R);
       while (($item = readdir($handle)) !== false) {
           if ($item != '.' and $item != '..') {
               if (is_dir($R . '/' . $item)) {
                   $this->_deleteDir($R . '/' . $item);
               } else {
                   if (!unlink($R . '/' . $item))
                       die('error!');
               }
           }
       }
       closedir($handle);
       return rmdir($R);
    }

}
