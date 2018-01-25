<?php
namespace app\admin\controller;
use think\Db;
use think\Request;
use think\Session;
use think\Controller;
use think\Config;
use app\admin\model\Articlebase;
class Article extends Common
{
    public function index()
    {

        $keyword=input('keyword');
        $link=Db::name('article')->alias('a')
        ->where('a.title', 'like', "%" . $keyword . "%")
        ->select();
        $this->assign('link',$link);
        $this->assign('keyword',$keyword);
     return $this->fetch();
    }


    public function add()
    {
        if (request()->isPost()){
            //构建数组
            $data = input('post.');
            $data['flag']=implode(',',$data['flag']);
            //var_dump($data['flag']);
            $data['posttime'] = time();
            $data['picarr']=implode(',',$data['picarr']);
            $article=new Articlebase();
            $res=$article->allowField(true)->save($data);
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
            //构建数组
            $data = input('post.');
            $id=input('id');
            $data['flag']=implode(',',$data['flag']);
            $data['posttime'] = time();
            $article=new Articlebase();
            $res=$article->allowField(true)->save($data,['id' => $id]);
                if($res){
                   return $this->success('修改成功','index');
                }else{
                   return $this->error('修改失败');
                }
        }else{
            $id=input('id');
            $list=Db::name('article')->where('id',$id)->find();
            $this->assign('list',$list);
            $flag=explode(',',$list['flag']);
            $this->assign('flag',$flag);
            if($list['picarr']){
                $picarr=explode(",",$list['picarr']);
            }else{
                $picarr=$list['picarr'];
            }

            $this->assign('picarr',$picarr);
            var_dump($picarr);
            return $this->fetch();
        }
    }

    public function del()
    {
       $id =input('id');
       Db::name('article')->where('id',$id)->delete();
       return ['status'=>1,'msg'=>'删除成功！'];
    }

    public function artopen()
    {
        $id=input('id');
        $open=Db::name('article')->where('id',$id)->value('open');
        if($open==1){
            Db::name('article')->where('id',$id)->update(['open'=>0]);
            $result['info'] = '关闭成功!';
            $result['status'] = 200;
            return $result;
        }else{
            Db::name('article')->where('id',$id)->update(['open'=>1]);
            $result['info'] = '开启成功!';
            $result['status'] = 200;
            return $result;
        }
    }



}
