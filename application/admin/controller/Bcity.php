<?php
namespace app\admin\controller;
use think\Db;
use think\Request;
use think\Session;
use think\Controller;
use think\Config;
class Bcity extends Controller
{
   public function index(){
       $keyword=input('keyword');
       $link=Db::name('bcityname')->alias('a')
       ->where('a.title', 'like', "%" . $keyword . "%")
       ->select();
       $this->assign('link',$link);
       $this->assign('keyword',$keyword);
       return $this->fetch();
   }

   public function add()
   {
       if (request()->isPost()){
           $data = input('post.');
           $data['posttime'] = time();
           $res=Db::name('bcityname')->insert($data);
           if($res){
              return $this->success('添加成功','index');
           }else{
              return $this->error('添加失败');
           }
       }else{
           return $this->fetch();
       }
   }

   public function edit()
   {
       if (request()->isPost()){
           $data = input('post.');
           $id=input('id');
           $data['posttime'] = time();
           $res=Db::name('bcityname')->where('id',$id)->update($data);
           if($res){
              return $this->success('添加成功','index');
           }else{
              return $this->error('添加失败');
           }
       }else{
           $id=input('id');
           $list=Db::name('bcityname')->where('id',$id)->find();
           $this->assign('list',$list);
           return $this->fetch();
       }
   }


    public function del()
   {
      $id =input('id');
      Db::name('bcityname')->where('id',$id)->delete();
      return ['status'=>1,'msg'=>'删除成功！'];
   }

   public function tui()
   {
       $id=input('id');
       $tui=Db::name('bcityname')->where('id',$id)->value('tui');
       if($tui==1){
           Db::name('bcityname')->where('id',$id)->update(['tui'=>0]);
           $result['info'] = '关闭!';
           $result['status'] = 200;
           return $result;
       }else{
           Db::name('bcityname')->where('id',$id)->update(['tui'=>1]);
           $result['info'] = '开启!';
           $result['status'] = 200;
           return $result;
       }
   }
    public function open()
   {
       $id=input('id');
       $open=Db::name('bcityname')->where('id',$id)->value('open');
       if($open==1){
           Db::name('bcityname')->where('id',$id)->update(['open'=>0]);
           $result['info'] = '关闭成功!';
           $result['status'] = 200;
           return $result;
       }else{
           Db::name('bcityname')->where('id',$id)->update(['open'=>1]);
           $result['info'] = '开启成功!';
           $result['status'] = 200;
           return $result;
       }
   }
 // -----------------------------章节管理---------------------------------------
 public function section(){
     $keyword=input('keyword');
     $link=Db::name('books')->alias('a')
     ->where('a.title', 'like', "%" . $keyword . "%")
     ->select();
     $this->assign('link',$link);
     $this->assign('keyword',$keyword);
     return $this->fetch();
 }
}
