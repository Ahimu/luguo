<?php
use think\Request;
use think\Db;
use think\Session;

function Getuserinfo($userid){
    $user = Db::name('user')->where('id',$userid)->find();
    if(empty($user)){
        $user['nickname']='系统消息';
    }
	return $user['nickname'];
}

function Getuserpic($userid){
    $user = Db::name('user')->where('id',$userid)->find();
    if(empty($user['headpic'])){
        $user['headpic']='_home_/images/avatar/default.png';
    }
	return $user['headpic'];
}

function Getcatetitle($id){
    $cate = Db::name('clist')->where('id',$id)->find();
    if(empty($cate)){
        $cate['title']='提问';
    }
	return $cate['title'];
}

function Gettietitle($userid){
    $content = Db::name('tie')->where('id',$userid)->find();
    if(empty($content)){
        $content['title']='系统错误';
    }
	return $content['title'];
}

function  pre($data)
{
    echo "<pre>";
    print_r($data);
    echo "</pre>";
}
function GetOrderID($tbname)
{
	$r = Db::query("SELECT MAX(orderid) AS `orderid` FROM `$tbname`");
	$orderid = (empty($r['orderid']) ? 1 : ($r['orderid'] + 1));
	return $orderid;
}
function pasttime($the_time){
    $now_time = date("Y-m-d H:i:s",time());
    $now_time = strtotime($now_time);
    $show_time = $the_time;
    $dur = $now_time - $show_time;
    if($dur < 0){
        return $the_time;
    }else{
        if($dur < 60){
            return $dur.'秒前';
        }else{
            if($dur < 3600){
                return floor($dur/60).'分钟前';
            }else{
               if($dur < 86400){
               return floor($dur/3600).'小时前';
               }else{
                   if($dur < 259200){//3天内
                       return floor($dur/86400).'天前';
                   }else{
                       return $the_time;
                   }
               }
            }
        }
    }
}

function todaysignin($userid){
   $res=Db::name('signin')->where('userid',$userid)->find();
   $datetime=date('Ymd',time());
   if($res['posttime']==$datetime){
       return $res;
   }else{
       return 'false';
   }

}
function signinday($daynum){
    if($daynum<5){
        return 5;
    }else if($daynum>=5 and $daynum<15){
        return 10;
    }else if($daynum>=15 and $daynum<30){
        return 15;
    }else if($daynum>=30 and $daynum<100){
        return 20;
    }else if($daynum>=100 and $daynum<365){
        return 30;
    }else if($daynum>=365){
        return 50;
    }
}

 ?>
