<?php
namespace app\admin\controller;
use think\Db;
use think\Request;
use think\Session;
use think\Controller;
use think\Config;
use org\Pinyin;
class Tulin extends Controller
{

 public function index(){



 return $this->fetch();
 }

 public function liaotian(){
     $keyword=input('keyword');
     $url = "http://www.tuling123.com/openapi/api";
	 $appkey="3c74b371cceb4cdb8bfd91cf5fb9c422";
	 $params = array(
		   "key" => $appkey,//您申请到的本接口专用的APPKEY
		   "info" => $keyword,//要发送给机器人的内容，不要超过30个字符
		   "dtype" => "",//返回的数据的格式，json或xml，默认为json
		   "loc" => "",//地点，如北京中关村
		   "lon" => "",//经度，东经116.234632（小数点后保留6位），需要写为116234632
		   "lat" => "",//纬度，北纬40.234632（小数点后保留6位），需要写为40234632
		   "userid" => "1",//1~32位，此userid针对您自己的每一个用户，用于上下文的关联
	 );
	 $paramstring = http_build_query($params);
	 $content = juhecurl($url,$paramstring);
	 $result = json_decode($content,true);
     return $result;



 return $this->fetch();
 }



}
