<?php
namespace app\admin\controller;
use think\Db;
use think\Request;
use think\Session;
use think\Controller;
use think\Config;
class Shipin extends Controller
{
    public function index()
    {


    return $this->fetch();
    }


}
