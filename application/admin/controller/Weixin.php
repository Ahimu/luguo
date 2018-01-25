<?php
namespace app\admin\controller;
use think\Db;
use think\Request;
use think\Session;
use think\Controller;
use think\Config;
use think\File;
use app\admin\model\Textmodel;
class Weixin extends Controller
{
    public function index()
    {
        if (request()->isPost()){
            $id=input('id');
            $data=input('post.');
            $data['posttime']=time();
            $res=Db::name('wxconfig')->where('id',$id)->update($data);
            if($res){
               return $this->success('保存成功','index');
            }else{
               return $this->error('保存失败');
            }
        }else{
            $config=Db::name('wxconfig')->where('id',1)->find();
            $this->assign('config',$config);
            // $list=weather('杭州');
            // var_dump(translation('你好')) ;
            //var_dump(json_decode(translation('你好'),true));

            return $this->fetch();
        }
    }

    public function memu(){
        return $this->fetch();
    }

    public function message(){

        $keyword=input('keyword');
        $list=Db::name('wxsearch')
        ->where('title', 'like', "%" . $keyword . "%")
        ->select();
        $this->assign('keyword',$keyword);
        $this->assign('list',$list);
        return $this->fetch();
    }

    public function messageadd()
    {
        if (request()->isPost()){
            //构建数组
            $data = input('post.');
            $data['wid']='';
            $data['table']='';
            $data['posttime'] = time();
            $res=Db::name('wxsearch')->insert($data);
                if($res){
                   return $this->success('添加成功','message');
                }else{
                   return $this->error('添加失败');
                }
        }else{

            return $this->fetch();
        }
    }
    public function messageedit()
    {
        if (request()->isPost()){
            //构建数组
            $id=input('id');
            $data = input('post.');
            $data['wid']='';
            $data['table']='';
            $data['posttime'] = time();
            $res=Db::name('wxsearch')->where('id',$id)->update($data);
                if($res){
                   return $this->success('修改成功','message');
                }else{
                   return $this->error('修改失败');
                }
        }else{
            $id=input('id');
            $list=Db::name('wxsearch')->where('id',$id)->find();
            $this->assign('list',$list);
            return $this->fetch();
        }
    }

    public function delmessage()
    {
       $id =input('id');
       Db::name('wxsearch')->where('id',$id)->delete();
       return ['status'=>1,'msg'=>'删除成功！'];
    }

    public function text()
    {

        $keyword=input('keyword');
        $list=Db::name('text')
        ->where('title', 'like', "%" . $keyword . "%")
        ->select();
        $this->assign('keyword',$keyword);
        $this->assign('list',$list);

       return $this->fetch();
    }

    public function textadd()
    {
        if (request()->isPost()){
            //构建数组
            $data = input('post.');
            $data['posttime'] = time();
            $text=new Textmodel();
            $res=$text->allowField(true)->save($data);
			$wx['wid'] = Db::name('text')->getLastInsID();
            $wx['title']=$data['title'];
            $wx['type']=$data['type'];
            $wx['table']='text';
            $wx['content']='';
            $wx['posttime'] = time();
            Db::name('wxsearch')->insert($wx);
                if($res){
                   return $this->success('添加成功','text');
                }else{
                   return $this->error('添加失败');
                }
        }else{

            return $this->fetch();
        }
    }

    public function textedit()
    {
        if (request()->isPost()){
            //构建数组
            $data = input('post.');
            $data['posttime'] = time();
            $id=input('id');
            $text=new Textmodel();
            $res=$text->allowField(true)->save($data,['id' => $id]);
            if($res){
               return $this->success('修改成功','text');
            }else{
               return $this->error('修改失败');
            }
        }else{
            $id=input('id');
            $list=Db::name('text')->where('id',$id)->find();
            $this->assign('list',$list);
            return $this->fetch();
        }
    }
}
