<?php
namespace app\home\controller;
use think\Request;
use think\Db;
use think\Session;
use think\Paginator;

class User extends Common
{


    public function index()
    {
        $user=session::get('user');
        if(empty($user)){
             $this->redirect('user/login');
        }
        $list=Db::name('tie')->where('userid',$user['id'])->select();
        $count=count($list);

        $collection=Db::name('collection')
                    ->alias('a')
                    ->join('sh_tie w','a.tie_id = w.id')
                    ->where('a.userid',$user['id'])
                    ->select();

        $collectionnum =count($collection);
        //$pagecollection = $collection->render();

        $this->assign('collectionnum',$collectionnum);
        $this->assign('collection',$collection);
        $this->assign('count',$count);
        $this->assign('list',$list);
        // $this->assign('page',$page);
        // $this->assign('pagecollection',$pagecollection);
        return $this->fetch();
    }

    public function home(){
        $user=session::get('user');
        if(empty($user)){
             $this->redirect('user/login');
        }
        return $this->fetch();
    }
    public function set(){
        $u=session::get('user');
        if(empty($u)){
             $this->redirect('user/login');
        }
        $user=Db::name('user')->where('id',$u['id'])->find();
        $this->assign('user',$user);
        return $this->fetch();
    }
    public function message(){
        $user=session::get('user');
        if(empty($user)){
             $this->redirect('user/login');
        }
        $list=Db::name('message')->where('open',1)->where('repid',$user['id'])->select();
        $this->assign('list',$list);
        return $this->fetch();
    }
    public function delmessage(){
        $id=input('id');
        $open=Db::name('message')->where('id',$id)->value('open');
        if($open==1){
            Db::name('message')->where('id',$id)->update(['open'=>0]);
            $result['info'] = '删除成功!';
            $result['status'] = 200;
            return $result;
        }else{
            $result['info'] = '参数错误!';
            $result['status'] = 400;
            return $result;
        }
    }
    public function delallmessage(){
       $id=input('id');
       $list=Db::name('message')->where('open',1)->where('repid',$id)->select();
       foreach ($list as $ids) {
        $idall[]=$ids['id'];
       }
       foreach ($idall as $v) {
          Db::name('message')->where('id',$v)->update(['open'=>0]);
       }
       $result['info'] = '删除成功!';
       $result['status'] = 200;
       return $result;

    }

    public function activate(){
        return $this->fetch();
    }

    public function login()
    {
        if (request()->isPost()){
            $data = input('post.');
            $code=$data['vercode'];
            if(!captcha_check($code)){
                $result['info'] = '验证码错误!';
                $result['status'] = 400;
                return $result;
            }
        //用户名查找
            $username=input('username');
            $res=Db::name('user')->where("username",$username)->find();
            if(empty($res)){
                $result['info'] = '你的账户不存在!';
                $result['status'] = 400;
                return $result;
            }else{
               //密码匹对
                $data['password']=md5(md5(input('password')));
                if($res['password'] != $data['password'] ){
                    $result['info'] = '密码有误!';
                    $result['status'] = 400;
                    return $result;
                }else{
                       if($res['open']==0){
                           $result['info'] = '该账号已被禁用!';
                           $result['status'] = 400;
                           return $result;
                       }else{
                           //登录成功
                           $user=$res;
                           $user['logtime']=time();
                           $user['logip']=$_SERVER['REMOTE_ADDR'];
                           Db::name('user')->where('id',$res['id'])->update($user);
                           Session::set('user',$user);
                           $result['info'] = '登录成功!';
                           $result['status'] = 200;
                           $result['url'] = url('index');
                           return $result;
                       }

                }
            }
        }else{
            return $this->fetch();
        }
    }

    public function reg()
    {
        if (request()->isPost()){
            //构建数组
           $data = input('post.');
           $code=$data['vercode'];
           if(!captcha_check($code)){
               $result['info'] = '验证码错误!';
               $result['status'] = 400;
               return $result;
           }
           $username=$data['name'];
           $res=Db::name('user')->where("username",$username)->find();
           if($res){
               $result['info'] = '你的账户已存在!';
               $result['status'] = 400;
               return $result;
           }else{
            $reg['logtime']=time();
            $reg['regtime']=time();
            $reg['logip']=$_SERVER['REMOTE_ADDR'];
            $reg['regip']=$_SERVER['REMOTE_ADDR'];
            $reg['username']=$username;
            $reg['password']=md5(md5($data['password']));
            $reg['integral']=0;//积分
            $reg['log_num']=0;//插件登录
            $reg['balance']=0;//余额
            $reg['cadev']=0;//认证
            $reg['vip']=0;//vip
            $reg['headpic']='_home_/images/avatar/default.png';
            $reg['nickname']=$data['nickname'];
            $user= Db::name('user')->insert($reg);
                if($user){
                    $reg['id'] = Db::name('user')->getLastInsID();
                    Session::set('user',$reg);
                    $message['tie_id']=0;
                    $message['content']='恭喜你注册成功!';
                    $message['userid']=0;
                    $message['repid']=$reg['id'];
                    $message['posttime']=time();
                    $message['open']=1;
                    Db::name('message')->insert($message);
                    $result['info'] = '注册成功';
                    $result['status'] = 200;
                    $result['url'] = url('home/user/index');
                    return $result;
                }else{
                    $result['info'] = '参数有误';
                    $result['status'] = 400;
                    return $result;
                }
           }
        }else{
            return $this->fetch();
        }
    }
    public function saveuser(){
        $data=input('post.');
        $id=input('id');
        $res=Db::name('user')->where('id',$id)->update($data);
        if($res){
            $result['info'] = '修改成功';
            $result['status'] = 200;
            return $result;
        }else{
            $result['info'] = '参数有误';
            $result['status'] = 400;
            return $result;
        }
    }

    public function repass(){
        $data=input('post.');
        $id=input('id');
        $user = Db::name('user')->where('id',$id)->find();
        if($user['password']!=md5(md5($data['nowpass']))){
            $result['info'] = '当前密码错误!';
            $result['status'] = 302;
            return $result;
        }
        if($data['pass']!=$data['repass']){
            $result['info'] = '新密码与确认密码不一致!';
            $result['status'] = 302;
            return $result;
        }
        $password=md5(md5($data['pass']));
        $res=Db::name('user')->where('id',$id)->update(['password'=>$password]);
        if($res){
            $result['info'] = '修改成功';
            $result['status'] = 200;
            return $result;
        }else{
            $result['info'] = '参数有误';
            $result['status'] = 400;
            return $result;
        }

    }
    public function logout()
    {
        session('user',null);
        $this->redirect('login');
    }


}
