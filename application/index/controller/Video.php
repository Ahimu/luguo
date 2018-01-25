<?php
namespace app\index\controller;
use think\Request;
use think\Db;
use think\Session;

class Video extends Common
{
    public function index()
    {

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

    public function main()
    {
        $id=input('id');
        if($id){
            $url="http://api.douban.com/v2/movie/subject/".$id."?apikey=0b2bdeda43b5688921839c8ecb20399b&city=%E5%8C%97%E4%BA%AC&client=&udid=";
        }else{
            $url="http://api.douban.com/v2/movie/subject/26865690?apikey=0b2bdeda43b5688921839c8ecb20399b&city=%E5%8C%97%E4%BA%AC&client=&udid=";
        }

        $list = file_get_contents($url);
        $list =json_decode($list,true);
        // //var_dump($list);
        // echo "<pre>";
        // print_r($list);
        // echo "</pre>";

        $this->assign('list',$list);
        return $this->fetch();
    }


}
