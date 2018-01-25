<?php
namespace app\index\controller;
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
         $url="http://lf.snssdk.com/neihan/service/tabs/";
         $joke_memu = file_get_contents($url);
         $joke_memu=json_decode($joke_memu,true);
         $this->assign('joke_memu',$joke_memu);
        // pre($joke_memu);
    }

    public function _empty(){
        return $this->error('空操作，返回上次访问页面中...');
    }

}
