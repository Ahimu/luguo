<?php
namespace app\index\controller;
use think\Request;
use think\Db;
use think\Session;

class Joke extends Common
{
    public function index()
    {
         // $title=input('title');

         // if($title=='推荐'){
         //     $url="http://lf.snssdk.com/neihan/stream/mix/v1/?content_type=-101";
         // }else if($title=='视频'){
         //     $url="http://lf.snssdk.com/neihan/stream/mix/v1/?content_type=-104";
         // }else if($title=='图片'){
         //     $url="http://lf.snssdk.com/neihan/stream/mix/v1/?content_type=-103";
         // }else if($title=='段子'){
         //     $url="http://lf.snssdk.com/neihan/stream/mix/v1/?content_type=-102";
         // }else if($title=='订阅'){
         //     $url="http://lf.snssdk.com/neihan/in_app/mybar_list/";
         // }else if($title=='同城'){
         //     $url="http://lf.snssdk.com/neihan/stream/mix/v1/?content_type=-201";
         // }else if($title=='段友圈'){
         //     $url="http://lf.snssdk.com/neihan/dongtai/dongtai_list/v1/";
         // }else{
         //     $url="http://lf.snssdk.com/neihan/stream/mix/v1/?content_type=-101";
         // }

         $url="http://is.snssdk.com/neihan/stream/mix/v1/?mpic=1&webp=1&essence=1&content_type=-102&message_cursor=-1&am_longitude=110&am_latitude=120&am_city=%E5%8C%97%E4%BA%AC%E5%B8%82&am_loc_time=1489226058493&count=30&min_time=1489205901&screen_width=1450&do00le_col_mode=0&iid=3216590132&device_id=32613520945&ac=wifi&channel=360&aid=7&app_name=joke_essay&version_code=612&version_name=6.1.2&device_platform=android&ssmix=a&device_type=sansung&device_brand=xiaomi&os_api=28&os_version=6.10.1&uuid=326135942187625&openudid=3dg6s95rhg2a3dg5&manifest_version_code=612&resolution=1450*2800&dpi=620&update_version_code=6120";
         $list = file_get_contents($url);
         $list=json_decode($list,true);
         $list=$list['data']['data'];
         // $list=json_encode($list)
         //pre($list);

        $this->assign('list',$list);
        return $this->fetch();
    }

    public function main()
    {



        return $this->fetch();
    }


}
