<?php
namespace app\home\controller;
use think\Request;
use think\Db;
use think\Session;

class Index extends Common
{
    public function index()
    {   $classid=input('classid');
        $classid =isset($classid) ? $classid : '0';


        $flag=input('flag');
        $flag =isset($flag) ? $flag : '';
        if($flag=='w'){
            if(empty($classid)){
            $list=Db::name('tie')->where('accept',0)->where('top',0)->where('open',1)->select();
            }else{
            $list=Db::name('tie')->where('accept',0)->where('classid',$classid)->where('open',1)->select();
            }
        }else if($flag=='y'){
            if(empty($classid)){
            $list=Db::name('tie')->where('accept',1)->where('top',0)->where('open',1)->select();
            }else{
            $list=Db::name('tie')->where('accept',1)->where('classid',$classid)->where('open',1)->select();
            }
        }else if($flag=='j'){
            if(empty($classid)){
            $list=Db::name('tie')->where('flag',1)->where('top',0)->where('open',1)->select();
            }else{
            $list=Db::name('tie')->where('flag',1)->where('classid',$classid)->where('open',1)->select();
            }
        }else{
            if(empty($classid)){
            $list=Db::name('tie')->where('top',0)->where('open',1)->select();
            }else{
            $list=Db::name('tie')->where('classid',$classid)->where('open',1)->select();
            }
        }

        $this->assign('list',$list);
        $top=Db::name('tie')->where('top',1)->where('open',1)->order('hits desc')->select();
        $this->assign('top',$top);
        $this->assign('classid',$classid);
        $this->assign('flag',$flag);
        $cate=Db::name('clist')->where('open',1)->select();
        $this->assign('cate',$cate);
        $user=session::get('user');
        $this->assign('user',$user);
        return $this->fetch();
    }

    public function detail()
    {   $user=session::get('user');
        $this->assign('user',$user);

        $classid=input('classid');
        $id=input('id');
        $classid =isset($classid) ? $classid : '0';
        $this->assign('classid',$classid);

        $cate=Db::name('clist')->where('open',1)->select();
        $this->assign('cate',$cate);

        $message=Db::name('message')->where('tie_id',$id)->where('open',1)->select();
        $this->assign('message',$message);

        $content=Db::name('tie')->where('id',$id)->find();
        Db::name('tie')->where('id',$id)->setInc('hits',1);//bug +1 会变成+2
        $this->assign('content',$content);
        return $this->fetch();
    }

    public function message(){
        $data=input('post.');
        $data['posttime']=time();
        $data['open']=0;
        $data['accept']=0;
        $res=Db::name('message')->insert($data);
        if($res){
            Db::name('tie')->where('id',$data['tie_id'])->setInc('annum');
            $result['info'] = '回复成功';
            $result['status'] = 200;
            return $result;
        }else{
            $result['info'] = '回复失败,请稍后重试!';
            $result['status'] = 400;
            return $result;
        }

    }
    public function signin(){
        $userid=input('userid');
        //$user=Db::name('user')->where('userid',$userid)->find();
        $res=Db::name('signin')->where('userid',$userid)->find();
        if($res){
            $data['posttime']= date('Ymd',time());
            $lasttime= date('Ymd',time()-86400);
            if($lasttime==$res['posttime']){
                $data['daynum']= $res['daynum']+1;
                $data['integral']=signinday($res['dayenum']);
                $sin=Db::name('signin')->where('userid',$userid)->update($data);
                if($sin){
                    Db::name('user')->where('id',$userid)->setInc('integral',$data['integral']);
                    $result['info'] = '签到成功!';
                    $result['status'] = 200;
                    return $result;
                }else{
                    $result['info'] = '签到失败,请稍后重试!';
                    $result['status'] = 400;
                    return $result;
                }
            }else{
                if($res['posttime']==$data['posttime']){
                    $result['info'] = '今日你已经签到啦!';
                    $result['status'] = 400;
                    return $result;
                }
                $data['daynum']= 1;
                $data['integral']=signinday(1);
                $sin=Db::name('signin')->where('userid',$userid)->update($data);
                if($sin){
                    Db::name('user')->where('id',$userid)->setInc('integral',$data['integral']);
                    $result['info'] = '签到成功!';
                    $result['status'] = 200;
                    return $result;
                }else{
                    $result['info'] = '签到失败,请稍后重试!';
                    $result['status'] = 400;
                    return $result;
                }
            }
        }else{
            $data['userid']=input('userid');
            $data['posttime']= date('Ymd',time());
            $data['daynum']= 1;
            $data['integral']=signinday(1);
            $sin=Db::name('signin')->insert($data);
            if($sin){
                Db::name('user')->where('id',$userid)->setInc('integral',$data['integral']);
                $result['info'] = '签到成功!';
                $result['status'] = 200;
                return $result;
            }else{
                $result['info'] = '签到失败,请稍后重试!';
                $result['status'] = 400;
                return $result;
            }
        }


    }


}
