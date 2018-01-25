<?php
namespace app\admin\controller;
use think\Db;
use think\Request;
use think\Session;
use think\Controller;
use think\Config;
use org\Pinyin;
class Tupian extends Controller
{

        public function index(){

          $url='https://api.tuchong.com/feed-app?os_api=22&device_type=MI&device_platform=android&ssmix=a&manifest_version_code=232&dpi=400&abflag=0&uuid=651384659521356&version_code=232&app_name=tuchong&version_name=2.3.2&openudid=65143269dafd1f3a5&resolution=1280*1000&os_version=5.8.1&ac=wifi&aid=0&page=1&type=refresh';
          $list = file_get_contents($url);
          $list=json_decode($list,true);
         // var_dump($list['feedList']);
          $this->assign('data',$list['feedList']);
         return $this->fetch();
        }




}
