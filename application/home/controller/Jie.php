<?php
namespace app\home\controller;
use think\Request;
use think\Db;
use think\Session;

class Jie extends Common
{
    public function index()
    {
           $classid=input('classid');
           $classid =isset($classid) ? $classid : '0';
           $this->assign('classid',$classid);
           $cate=Db::name('cate')->where('open',1)->select();
           $this->assign('cate',$cate);
           $user=session::get('user');
           $this->assign('user',$user);

        $list=Db::name('tie')->where('open',1)->select();
        $this->assign('list',$list);
        return $this->fetch();
    }
    public function add()
    {
        $user=session::get('user');
        if (request()->isPost()){
            $data = input('post.');
            $data['userid']=$user['id'];
            $data['posttime']=time();
            $data['hits']=mt_rand(50, 200);
            $data['annum']='0';
            $data['open']='1';
            $res= Db::name('tie')->insert($data);
            if($res){
                $result['info'] = '添加成功!';
                $result['status'] = 200;
                $result['url'] = url('index');
                return $result;
            }else{
                $result['info'] = '添加失败!';
                $result['status'] = 400;
                return $result;
            }
        }else{
                $cate=Db::name('cate')->where('open',1)->select();
                $this->assign('cate',$cate);
                return $this->fetch();
            }
    }

    public function edit()
    {
        if (request()->isPost()){
            $id=input('id');
            $data = input('post.');
            //$data['hits']=mt_rand(50, 200);
            $res= Db::name('tie')->where('id',$id)->update($data);
            if($res){
                $result['info'] = '修改成功!';
                $result['status'] = 200;
                $result['url'] = url('index');
                return $result;
            }else{
                $result['info'] = '修改失败!';
                $result['status'] = 400;
                return $result;
            }
        }else{
            $id=input('id');
            $list=Db::name('tie')->where('id',$id)->find();
            $this->assign('list',$list);
            $cate=Db::name('cate')->where('open',1)->select();
            $this->assign('cate',$cate);
            return $this->fetch();
        }

    }

    public function detail()
    {

        return $this->fetch();
    }
}
