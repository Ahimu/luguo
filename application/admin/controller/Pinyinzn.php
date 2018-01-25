<?php
namespace app\admin\controller;
use think\Db;
use think\Request;
use think\Session;
use think\Controller;
use think\Config;
use org\Pinyin;
class PinYinzn extends Controller
{

 public function index(){
 //     $keyword="爱你一万年";
 //     $getinfo = file_get_contents ( "http://mobilecdn.kugou.com/api/v3/search/song?format=json&keyword=" . urlencode ( $keyword ) . "&page=1&pagesize=1&showtype=1" );
 // var_dump(json_decode($getinfo,true));


 return $this->fetch();
 }

 public function fanyi(){
     $keyword=input('keyword');
     $py = new PinYin();
     $res=$py->getAllPY($keyword);
     $result['info'] = $res;
     $result['status'] = 200;
     return $result;



 return $this->fetch();
 }



}
