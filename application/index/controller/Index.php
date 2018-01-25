<?php
namespace app\index\controller;
use think\Request;
use think\Db;
use think\Session;

class Index extends Common
{
    public function index()
    {

        $date=input('time');
        //var_dump($date);
        if($date){
            $url = "https://interface.meiriyiwen.com/article/day?dev=1&date=".$date;
        }else{
            $url = "https://interface.meiriyiwen.com/article/today?dev=1";
        }
        $list = file_get_contents($url);
        $list=json_decode($list,true);
        $list=$list['data'];
        $tor=date("Ymd",time());
        //echo $tor;
        $time=$list['date'];
        $this->assign('time',$time);
        $this->assign('list',$list);
        $this->assign('tor',$tor);

        $request = Request::instance();
        $ip = $request->ip();
        $url='http://ip.taobao.com/service/getIpInfo.php?ip='.$ip;
        $result = file_get_contents($url);
        $result = json_decode($result,true);
        //var_dump($result);
        //pre($result);

        return $this->fetch();
    }

    public function main()
    {

        return $this->fetch();


    }
}
