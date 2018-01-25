<?php
namespace app\admin\controller;
use think\Db;
use think\Request;
use think\Session;
use think\Controller;
use think\Config;
use org\phpqrcode;

    class Qrcode extends Controller
{
    public function index()
    {
        if (request()->isPost()){
        $data=input('title');
        //$logo=input('logo');
        $filename="helloworld".date("Ymdhis",time()).".png";
        $img=qrcode($data,$filename,$picPath=false,$logo=false,$size='10',$level='L',$padding=2,$saveandprint=false);

        }else{
           $img='/luguo/uploads/QRcode/helloworld20180103125842.png';
        }
        $this->assign('img',$img);
     return $this->fetch();
    }
}
