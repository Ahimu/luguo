<?php
namespace app\admin\controller;
use think\Db;
use think\Request;
use think\Session;
use think\Controller;
use think\Config;
use think\Loader;
use org\Snoopy;
class Test extends Controller
{

        public function index(){




            $url = "http://likaikai.hzwzjs.net/index/index/index/time/20180121.html";
            $snoopy = new Snoopy;
            $snoopy->fetch($url); //获取所有内容
            //header("Content-type:text/html;charset=gb2312");
            // echo $snoopy->results; //显示结果
            echo $snoopy->fetchtext;
            //可选以下
            // $snoopy->fetchtext //获取文本内容（去掉html代码）
            // $snoopy->fetchlinks //获取链接
            // $snoopy->fetchform  //获取表单

         return $this->fetch();
        }





}
