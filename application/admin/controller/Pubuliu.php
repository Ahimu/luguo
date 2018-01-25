<?php
namespace app\admin\controller;
use think\Db;
use think\Request;
use think\Session;
use think\Controller;
use think\Config;
class Pubuliu extends Controller
{
    public function index()
    {

    // $url="http://image.baidu.com/channel/listjson?pn=0&rn=30&tag1=%E7%BE%8E%E5%A5%B3&tag2=%E5%85%A8%E9%83%A8&ftags=%E5%B0%8F%E6%B8%85%E6%96%B0&ie=utf8";
    $url="https://api.douban.com/v2/movie/in_theaters?apikey=0b2bdeda43b5688921839c8ecb20399b&city=%E5%8C%97%E4%BA%AC&start=0&count=100&client=&udid=";
    $list = file_get_contents($url);
    $list =json_decode($list,true);
    //var_dump($list);
    // echo "<pre>";
    // print_r($list['subjects']);
    // echo "</pre>";
    $list=$list['subjects'];
    $this->assign('list',$list);
    return $this->fetch();
    }


}
