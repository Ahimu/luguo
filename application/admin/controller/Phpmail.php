<?php
namespace app\admin\controller;
use think\Db;
use think\Request;
use think\Session;
use think\Controller;
use think\Config;
use think\Loader;
use org\RndChinaName;
class Phpmail extends Controller
{

        public function index(){

            $name_obj = new rndChinaName();
            $name = $name_obj->getName(4);
            var_dump($name);
         return $this->fetch();
        }


        public function send()
        {
 	        $data=input('post.');
            $toemail=$data['email'];
            $name='dalianmao';
            $subject=$data['subject'];
            $content=$data['content'];
            if(send_mail($toemail,$name,$subject,$content)){
                return $this->success('发送成功','index');
            }else{
                 return $this->error('发送失败');
            }



 	    }


}
