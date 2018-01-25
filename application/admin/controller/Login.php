<?php
namespace app\admin\controller;
use think\Db;
use think\Request;
use think\Session;
use think\Controller;

class Login extends Controller
{
    public function index()
    {

      return $this->fetch();
    }



    public function check()
    {

        $code=input('code');
        if(!captcha_check($code)){
            $result['info'] = '验证码错误!';
            $result['status'] = 400;
            return $result;
        }
    //用户名查找
        $username=input('username');
        $res=Db::name('admin')->where("username",$username)->find();
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
                if($res['open']=='false'){
                    $result['info'] = '该账号已被禁用!';
                    $result['status'] = 400;
                    return $result;
                }else{
                   //登录成功
                   $admin=$res;
                   $admin['logintime']=time();
                   $admin['loginip']=$_SERVER['REMOTE_ADDR'];
                   Db::name('admin')->where('id',$res['id'])->update($admin);
                   Session::set('admin',$admin);
                   $result['info'] = '登录成功!';
                   $result['status'] = 200;
                   $result['url'] = url('admin/index/index');
                   return $result;
               }

            }
        }

    }
    public function logout()
    {
        session('admin',null);
        $this->redirect('admin/login/index');
    }



}
