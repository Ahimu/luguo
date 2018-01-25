<?php
namespace app\admin\controller;
use think\Db;
use think\Request;
use think\Session;
use think\Cookie;
use think\Controller;
use org\Auth;
use think\Config;
use think\response\Redirect;
class Common extends Controller
{

    public function _initialize()
    {
        $admin=Session::get('admin');
               //判断管理员是否登录
        if (!$admin) {
           $this->redirect('login/index');
        }
        //权限验证
        $auth=new Auth();
        $request=  \think\Request::instance();
               //控制器
                   //    dump($request->controller());
                   //    dump($request->module());
                   //    dump($request->action());die;
        $controller=$request->controller();
        $module=$request->module();
        $action=$request->action();
       	$rule_name=$module.'/'.$controller.'/'.$action;
       	$result=$auth->check($rule_name,$admin['id']);
        //var_dump($result);
        if(empty($result)){
             $this->error('您没有权限访问','admin/index/main');
       	}
    }

    public function _empty(){
        return $this->error('空操作，返回上次访问页面中...');
    }

}
