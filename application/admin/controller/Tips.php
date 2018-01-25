<?php
namespace app\admin\controller;
use think\Db;
use think\Request;
use think\Session;
use think\Controller;
use think\Config;
class Tips extends Controller
{
    public function index()
    {
        $keyword=input('keyword');
        $tips=Db::name('tips')
        ->where('title', 'like', "%" . $keyword . "%")
        ->select();
        $this->assign('tips',$tips);
        $this->assign('keyword',$keyword);

     return $this->fetch();
    }

    public function add(){
        if (request()->isPost()){
            //构建数组
            $data = input('post.');
            $data['posttime'] = time();
            $res=Db::name('tips')->insert($data);
                if($res){
                   return $this->success('添加成功','index');
                }else{
                   return $this->error('添加失败');
                }
        }else{

            return $this->fetch();
        }
        return $this->fetch();
    }

    public function open(){
        $id=input('id');
        $open=Db::name('tie')->where('id',$id)->value('open');
        if($open==1){
            Db::name('tie')->where('id',$id)->update(['open'=>0]);
            $result['info'] = '关闭成功!';
            $result['status'] = 200;
            return $result;
        }else{
            Db::name('tie')->where('id',$id)->update(['open'=>1]);
            $result['info'] = '开启成功!';
            $result['status'] = 200;
            return $result;
        }
    }
}
