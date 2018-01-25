<?php
namespace app\admin\controller;
use think\Db;
use think\Request;
use think\Session;
use think\Controller;
use think\Config;
use org\Pinyin;
class Echart extends Controller
{

        public function index(){

            $yuefen = array('1','2','3','4','5','6','7','8','9','10','11','12');

            foreach ($yuefen as $v)
            {
             $data[]=Db::name('user')->where('regtime','>',datemonthtime($v)['starttime'])->where('regtime', '<', datemonthtime($v)['endtime'])->count();
            }
            $data=implode(',',$data);
            $this->assign('data',$data);
            return $this->fetch();
        }





}
