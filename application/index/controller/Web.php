<?php
namespace app\index\controller;
use think\Request;
use think\Db;
use think\Session;

class Web extends Common
{
    public function index()
    {

        $url = "https://interface.meiriyiwen.com/article/today?dev=1";
        $list = file_get_contents($url);
        $list =json_decode($list,true);
        var_dump($list);

    }


}
