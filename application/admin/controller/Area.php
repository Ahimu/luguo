<?php
namespace app\admin\controller;
use think\Db;
use think\Request;
use think\Session;
use think\Controller;
use think\Config;
use app\admin\model\Articlebase;
class Area extends Controller
{
    public function index()
    {
    $list=Db::name('area')->where('pid',1)->select();
    $this->assign('list',$list);

     return $this->fetch();
    }

    public function getarea(){
        $pid=input('id');
        $list=Db::name('area')->where('pid',$pid)->select();
        $str = '<option value="-1">-请选择-</option>';
        foreach ($list as $v){
        $str .= '<option value="'.$v['id'].'">'.$v['name'].'</option>';
        }
        return $str;
    }
}
