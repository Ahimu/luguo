<?php
namespace app\admin\controller;
use think\Db;
use think\Request;
use think\Session;
use think\Controller;
use think\Config;
class Message extends Common
{
    public function index()
    {
        $keyword=input('keyword');
        $message=Db::name('message')->alias('a')
        ->join('tie t','a.tie_id = t.id')
        ->join('user u','a.userid = u.id')
        ->field('a.*,t.title as tiename,u.nickname')
        ->where('a.content', 'like', "%" . $keyword . "%")
        ->select();
        $this->assign('message',$message);
        $this->assign('keyword',$keyword);
     return $this->fetch();
    }


    public function open(){
        $id=input('id');
        $open=Db::name('message')->where('id',$id)->value('open');
        if($open==1){
            Db::name('message')->where('id',$id)->update(['open'=>0]);
            $result['info'] = '关闭成功!';
            $result['status'] = 200;
            return $result;
        }else{
            Db::name('message')->where('id',$id)->update(['open'=>1]);
            $result['info'] = '开启成功!';
            $result['status'] = 200;
            return $result;
        }
    }
}
