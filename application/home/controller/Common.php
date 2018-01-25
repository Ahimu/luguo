<?php
namespace app\home\controller;
use think\Db;
use think\Request;
use think\Session;
use think\Cookie;
use think\Controller;

use think\Config;
use think\response\Redirect;
class Common extends Controller
{

    public function _initialize()
    {
        $user=session::get('user');
        $this->assign('user',$user);

        $hot=Db::name('tie')->where('open',1)->order('annum desc,hits desc')->limit(10)->select();
        $this->assign('hot',$hot);

        $member=Db::name('user')->select();
        foreach ($member as $all) {
               $allid[]=$all['id'];
        }
        foreach ($allid as $v) {
        $us=Db::name('user')->where('id',$v)->find();
        $res['count']=Db::name('message')->where('userid',$v)->whereTime('posttime', 'w')->count();
        $res['nickname']=$us['nickname'];
        $res['headpic']=$us['headpic'];
        $arr[]=$res;
        }
        //var_dump($arr);
        $this->assign('arr',$arr);

    }

    public function _empty(){
        return $this->error('空操作，返回上次访问页面中...');
    }

}
