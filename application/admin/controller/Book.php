<?php
namespace app\admin\controller;
use think\Db;
use think\Request;
use think\Session;
use think\Controller;
use think\Config;
use app\admin\model\Booksmodel;
class Book extends Controller
{
    public function index()
    {
        $keyword=input('keyword');
        $link=Db::name('books')->alias('a')
        ->join('member m','a.author = m.id')
        ->join('cate c','a.cate_id = c.id')
        ->field('a.*,c.cate_name as catename,m.username')
        ->where('a.title', 'like', "%" . $keyword . "%")
        ->where('a.delstate', '')
        ->select();
        $this->assign('link',$link);
        $this->assign('keyword',$keyword);
     return $this->fetch();
    }

    public function add(){
        if (request()->isPost()){
            //构建数组
            $data = input('post.');
            $data['posttime'] = time();
            $book=new Booksmodel();
            $res=$book->allowField(true)->save($data);
                if($res){
                   return $this->success('添加成功','index');
                }else{
                   return $this->error('添加失败');
                }
        }else{

            $cate=Db::name('cate')->select();
            $this->assign('cate',$cate);
            $author=Db::name('member')->where('is_writer',2)->select();
            $this->assign('author',$author);
            return $this->fetch();
        }
    }

    public function edit(){

        if (request()->isPost()){
            //构建数组
            $data = input('post.');
            $id=input('id');

            $data['posttime'] = time();
            $book=new Booksmodel();
            $res=$book->allowField(true)->save($data,['id' => $id]);
                if($res){
                   return $this->success('修改成功','index');
                }else{
                   return $this->error('修改失败');
                }
        }else{
            $id=input('id');
            $list=Db::name('books')->where('id',$id)->find();
            $this->assign('list',$list);

            $cate=Db::name('cate')->select();
            $this->assign('cate',$cate);
            $author=Db::name('member')->where('is_writer',2)->select();
            $this->assign('author',$author);
            return $this->fetch();
        }
    }

    public function bookopen()
    {
        $id=input('id');
        $open=Db::name('books')->where('id',$id)->value('open');
        if($open==1){
            Db::name('books')->where('id',$id)->update(['open'=>0]);
            $result['info'] = '关闭成功!';
            $result['status'] = 200;
            return $result;
        }else{
            Db::name('books')->where('id',$id)->update(['open'=>1]);
            $result['info'] = '开启成功!';
            $result['status'] = 200;
            return $result;
        }
    }

    public function del()
    {
       $id =input('id');
       $res=Db::name('books')->where('id',$id)->update(['delstate'=>'true']);
       if($res){
           $result['info'] = '删除成功!';
           $result['status'] = 200;
           return $result;
       }else{
           $result['info'] = '删除失败!';
           $result['status'] = 200;
           return $result;
       }
    }


//----------------------------章节管理-------------------------------------------
    public function chapter()
    {
     $keyword=input('keyword');
     $bookid=input('id');
     $list=Db::name('chapter')
        ->where('classid',$bookid)
        ->where('delstate','')
        ->where('title', 'like', "%" . $keyword . "%")
        ->order('id', 'asc')
        ->select();
     $this->assign('list',$list);
     $this->assign('bookid',$bookid);
     $this->assign('keyword',$keyword);
     return $this->fetch();
    }
//   添加章节
    public function chapteradd(){
        if (request()->isPost()){
            //构建数组
            $data = input('post.');
            $classid = input('classid');
            $data['posttime'] = time();
            //如果字数为空
            if(empty($data['wordnum'])){
    		$encode = 'UTF-8';
    		$str_num = mb_strlen($data['content'], $encode);
    		$j = 0;
    		for($i=0; $i < $str_num; $i++)
    		{
    			if(ord(mb_substr($data['content'], $i, 1, $encode))> 0xa0)
    			{
    				$j++;
    			}
    		}
                $data['wordnum']=$j;
        	}else{
        		$data['wordnum']=$data['wordnum'];
        	}

            $book=Db::name('books')->where('id',$classid)->find();
        	$word_price=$book['word_price'];
        	//判断是否收费
        	if($data['isprice']=='true'){
        	    $data['price']= intval($data['wordnum']/1000) *$word_price;
        	}else{
        		$data['price'] ='';
        	}
            $data['zannum'] ='';
            $res=Db::name('chapter')->insert($data);
                if($res){
                   return $this->redirect('chapter', ['id' => $classid], 302,' 添加成功');
                }else{
                   return $this->error('添加失败');
                }
        }else{
            $classid=input('classid');
            $this->assign('classid',$classid);
             return $this->fetch();
        }


    }
//   修改章节
    public function chapteredit(){
        if (request()->isPost()){
            $data=input('post.');
            $id=input('id');
            $data['classid']=Db::name('chapter')->where('id',$id)->value('classid');
            if(empty($data['wordnum'])){
      		$encode = 'UTF-8';
      		$str_num = mb_strlen($data['content'], $encode);
      		$j = 0;
      		for($i=0; $i < $str_num; $i++)
      		{
      			if(ord(mb_substr($data['content'], $i, 1, $encode))> 0xa0)
      			{
      				$j++;
      			}
      		}
                  $data['wordnum']=$j;
          	}else{
          		$data['wordnum']=$data['wordnum'];
          	}

            $book=Db::name('books')->where('id',$data['classid'])->find();
          	$word_price=$book['word_price'];
          	//判断是否收费
          	if($data['isprice']=='true'){
          	    $data['price']= intval($data['wordnum']/1000) *$word_price;
          	}else{
          		$data['price'] ='';
          	}
              $res= Db::name('chapter')->where('id',$id)->update($data);
              if($res){
                 return $this->success('修改成功','index');
              }else{
                 return $this->error('修改失败');
              }
        }else{
            $id=input('id');
            $list=Db::name('chapter')->where('id',$id)->find();
            $this->assign('list',$list);
             return $this->fetch();
        }
    }
//  章节收费状态
    public function isprice(){
        $id=input('id');
        $isprice=Db::name('chapter')->where('id',$id)->value('isprice');
        if($isprice=='true'){
            Db::name('chapter')->where('id',$id)->update(['isprice'=>'false']);
            $result['info'] = '开启免费!';
            $result['status'] = 200;
            return $result;
        }else{
            Db::name('chapter')->where('id',$id)->update(['isprice'=>'true']);
            $result['info'] = '开启收费!';
            $result['status'] = 200;
            return $result;
        }
    }
//  章节状态显示
    public function chapteropen()
    {
        $id=input('id');
        $open=Db::name('chapter')->where('id',$id)->value('open');
        if($open==1){
            Db::name('chapter')->where('id',$id)->update(['open'=>0]);
            $result['info'] = '关闭成功!';
            $result['status'] = 200;
            return $result;
        }else{
            Db::name('chapter')->where('id',$id)->update(['open'=>1]);
            $result['info'] = '开启成功!';
            $result['status'] = 200;
            return $result;
        }
    }

//  章节删除
    public function delchapter()
    {
       $id =input('id');
       $res=Db::name('chapter')->where('id',$id)->update(['delstate'=>'true']);
       if($res){
           $result['info'] = '删除成功!';
           $result['status'] = 200;
           return $result;
       }else{
           $result['info'] = '删除失败!';
           $result['status'] = 200;
           return $result;
       }
    }
}
