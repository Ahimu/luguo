<?php
namespace app\admin\controller;
use think\Db;
use think\Request;
use think\Session;
use think\Controller;
use think\Config;
use think\Cookie;
class Auth extends Common
{
    public function index()
    {
    $keyword=input('keyword');
    $list=Db::name('auth_rule')->alias('a')
    ->where('a.title', 'like', "%" . $keyword . "%")
    ->select();
    $list=self::toLevel($list);
    $this->assign('list',$list);
    $this->assign('keyword',$keyword);
    return $this->fetch();
    }


    public function ruleadd()
    {
        if(request()->isPost()){
            $data=input('post.');
            $data['type']=1;
            $data['status']=1;
            $data['sort']=1;
            $res= Db::name('auth_rule')->insert($data);
            if($res){
              return $this->success('添加成功','index');
            }else{
               return $this->error('添加失败');
            }
        }
        $list=Db::name('auth_rule')->select();
        $list=self::toLevel($list);
        $this->assign('list',$list);
      return $this->fetch();
    }

    public function ruleedit()
    {
        if(request()->isPost()){
            $data=input('post.');
            $id=input('id');
            $res= Db::name('auth_rule')->where('id',$id)->update($data);
            if($res){
              return $this->success('修改成功','index');
            }else{
               return $this->error('修改失败');
            }
        }
        $list=Db::name('auth_rule')->select();
        $list=self::toLevel($list);
        $this->assign('list',$list);
        $id=input('id');
        $rule= Db::name('auth_rule')->where('id',$id)->find();
        $this->assign('rule',$rule);
      return $this->fetch();
    }

    public function ruledel()
   {
       $id =input('id');
       $res=Db::name('auth_rule')->where('id',$id)->delete();
       if($res){
         return $this->success('成功','index');
       }else{
          return $this->error('失败');
       }
   }


    /**
        * 递归取无限极分类
        * @param  [type]  $cate
        * @param  string  $html
        * @param  integer $parent_id
        * @param  integer $level
        */
        static function toLevel($cate,$html="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;└─",$pid=0,$level=0){
           $arr = array();
           foreach($cate as $v){
               if($v['pid'] == $pid){
                   $v['level'] =  $level;
                   $v['html'] = str_repeat($html,$v['level']);
                   $arr[] = $v;

                   $arr = array_merge($arr,self::toLevel($cate,$html,$v['id'],$level+1));
               }

           }
           return $arr;
       }

//----------------------------------角色管理-----------------------------------//
    public function role()
    {
        $list=Db::name('auth_group')->select();
        $this->assign('list',$list);

      return $this->fetch();
    }

    public function roleadd()
    {
        if(request()->isPost()){
            $data=input('post.');
            //var_dump($data);
            $data['rules']='';
            $res= Db::name('auth_group')->insert($data);
            if($res){
              return $this->success('添加成功','role');
            }else{
               return $this->error('添加失败');
            }
        }
      return $this->fetch();
    }

    public function roleedit()
    {
        if(request()->isPost()){
            $data=input('post.');
            $id=input('id');
            $res= Db::name('auth_group')->where('id',$id)->update($data);
            if($res){
              return $this->success('修改成功','role');
            }else{
               return $this->error('修改失败');
            }
        }
      $id=input('id');
      $role= Db::name('auth_group')->where('id',$id)->find();
      $this->assign('role',$role);
      return $this->fetch();
    }

    public function roledel()
   {
       $id =input('id');
       $res=Db::name('auth_group')->where('id',$id)->delete();
       if($res){
           return json(array('status'=>1,'info'=>'删除成功'));
       }else{
            return json(array('status'=>0,'info'=>'删除失败'));
       }
   }

   public function setauth()
  {
      $id=input('id');
      $a=Db::table('sh_auth_group')->find($id);

      $s = explode(",", $a['rules']);
      //var_dump($e);
      $this->assign('a',$a);
      $this->assign('s',$s);

      $data=Db::table('sh_auth_rule')->select();
      $this->assign('data',$data);

      $arr=array();
      $arr1=array();

      $c=Db::table('sh_auth_rule')->where('pid',0)->select();
      foreach ($c as $k => $v) {
          $d=Db::table('sh_auth_rule')->where('pid',$v['id'])->select();
          $arr[]=$d;

      }
      $this->assign('c',$c);
      $this->assign('d',$arr);
      foreach ($arr as $m) {
          foreach ($m as $n) {
              $e=Db::table('sh_auth_rule')->where('pid',$n['id'])->select();

              $arr1[]=$e;
          }
      }
      $this->assign('e',$arr1);
   //var_dump($arr1);
      return $this->fetch();
  }
  public function savesetauth()
   {

       $id=input('id');
       $data=input('post.');

       $rules=$data['rules'];

       $data['rules']=implode(',',$rules);
       //var_dump($data);die;
       $res=Db::table('sh_auth_group')->where('id',$id)->update($data);
       if($res){
               $this->success('授权成功','index');
       }else{
              $this->error('授权失败');
       }
   }



//--------------------------------管理员管理-----------------------------------//
    public function admin()
    {
     $keyword=input('keyword');
     $list=Db::name('admin')->alias('a')
     ->join('auth_group g','a.group_id = g.id')
     ->field('a.*,g.title as groupname')
     ->where('a.username', 'like', "%" . $keyword . "%")
     ->select();
     $this->assign('list',$list);
     $this->assign('keyword',$keyword);
      return $this->fetch();
    }

    public function adminadd()
    {
    if(request()->isPost()){
        $data=input('post.');
        $data['username']=input('username');
        if(empty(input('password'))){
              return $this->error('密码不能为空');
        }
        $data['password']=md5(md5(input('password')));
        $data['logintime']=time();
        $data['loginip']=$_SERVER['REMOTE_ADDR'];
        $admin=Db::name('admin')->where('username',$data['username'])->select();
           if($admin){
                return $this->error('用户名已存在');
           }else{

            $res= Db::name('admin')->insert($data);
               if($res){
                 return $this->success('添加成功','admin');
               }else{
                  return $this->error('添加失败');
               }
           }

    }
     $group=Db::name('auth_group')->select();
     $this->assign('group',$group);
     return $this->fetch();
    }

    public function adminedit()
    {
        if(request()->isPost()){
        $id=input('id');
        $data=input('post.');
        $data['password']=md5(md5($data['password']));
        $data['logintime']=time();
        $data['loginip']=$_SERVER['REMOTE_ADDR'];
        if($data['password']==''){
              $admin=Db::table('sh_admin')->find($id);
              $data['password']=$admin['password'];
        }
          $res= Db::name('admin')->where('id',$id)->update($data);
          if($res){
            return $this->success('修改成功','admin');
          }else{
             return $this->error('修改失败');
          }

        }
        $id=input('id');
        $admin=Db::name('admin')->where('id',$id)->find();
        $this->assign('admin',$admin);
        $group=Db::name('auth_group')->select();
        $this->assign('group',$group);
      return $this->fetch();
    }

    public function adminopen()
   {
       $id=input('id');
       $open=Db::name('admin')->where('id',$id)->value('open');
       if($open=='true'){
           Db::name('admin')->where('id',$id)->update(['open'=>'false']);
           $result['info'] = '禁用成功!';
           $result['status'] = 200;
           return $result;
       }else{
           Db::name('admin')->where('id',$id)->update(['open'=>'true']);
           $result['info'] = '开启成功!';
           $result['status'] = 200;
           return $result;
       }
   }
    public function admindel()
   {
       $id =input('id');
       $res=Db::name('admin')->where('id',$id)->delete();
       if($res){
           return json(array('status'=>1,'info'=>'删除成功'));
       }else{
            return json(array('status'=>0,'info'=>'删除失败'));
       }
   }

}
