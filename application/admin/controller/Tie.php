<?php
namespace app\admin\controller;
use think\Db;
use think\Request;
use think\Session;
use think\Controller;
use think\Config;
class Tie extends Common
{
    public function index()
    {
        $keyword=input('keyword');
        $tie=Db::name('tie')->alias('a')
        ->join('user u','a.userid = u.id')
        ->join('clist c','a.classid = c.id')
        ->field('a.*,c.title as catename,u.nickname')
        ->where('a.title|c.title', 'like', "%" . $keyword . "%")
        ->select();

        $this->assign('tie',$tie);
        $this->assign('keyword',$keyword);

     return $this->fetch();
    }

    public function top(){

        $id=input('id');
        $top=Db::name('tie')->where('id',$id)->value('top');
        if($top==1){
            Db::name('tie')->where('id',$id)->update(['top'=>0]);
            $result['info'] = '取消置顶!';
            $result['status'] = 200;
            return $result;
        }else{
            Db::name('tie')->where('id',$id)->update(['top'=>1]);
            $result['info'] = '置顶成功!';
            $result['status'] = 200;
            return $result;
        }
    }

    public function flag(){

        $id=input('id');
        $flag=Db::name('tie')->where('id',$id)->value('flag');
        if($flag==1){
            Db::name('tie')->where('id',$id)->update(['flag'=>0]);
            $result['info'] = '取消加精!';
            $result['status'] = 200;
            return $result;
        }else{
            Db::name('tie')->where('id',$id)->update(['flag'=>1]);
            $result['info'] = '加精成功!';
            $result['status'] = 200;
            return $result;
        }
    }

    public function open(){
        $id=input('id');
        $open=Db::name('tie')->where('id',$id)->value('open');
        if($open==1){
            Db::name('tie')->where('id',$id)->update(['open'=>0]);
            $result['info'] = '关闭成功!';
            $result['status'] = 200;
            return $result;
        }else{
            Db::name('tie')->where('id',$id)->update(['open'=>1]);
            $result['info'] = '开启成功!';
            $result['status'] = 200;
            return $result;
        }
    }
}
