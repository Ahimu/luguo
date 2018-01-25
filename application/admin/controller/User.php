<?php
namespace app\admin\controller;
use think\Db;
use think\Request;
use think\Session;
use think\Controller;
use think\Config;
use think\File;
class User extends Controller
{
    public function index()
    {
        $keyword=input('keyword');
        $user=Db::name('user')->alias('u')
        ->field('u.*')
        ->where('u.username|u.nickname', 'like', "%" . $keyword . "%")
        ->select();
        $this->assign('user',$user);
        $this->assign('keyword',$keyword);

     return $this->fetch();
    }

    public function cadev(){
        $id=input('id');
        $cadev=Db::name('user')->where('id',$id)->value('cadev');
        if($cadev==1){
            Db::name('user')->where('id',$id)->update(['cadev'=>0]);
            $result['info'] = '关闭成功!';
            $result['status'] = 200;
            return $result;
        }else{
            Db::name('user')->where('id',$id)->update(['cadev'=>1]);
            $result['info'] = '开启成功!';
            $result['status'] = 200;
            return $result;
        }
    }

    public function vip(){

        $id=input('id');
        $vip=Db::name('user')->where('id',$id)->value('vip');
        if($vip==1){
            Db::name('user')->where('id',$id)->update(['vip'=>0]);
            $result['info'] = '关闭成功!';
            $result['status'] = 200;
            return $result;
        }else{
            Db::name('user')->where('id',$id)->update(['vip'=>1]);
            $result['info'] = '开启成功!';
            $result['status'] = 200;
            return $result;
        }
    }

    public function open(){
        $id=input('id');
        $open=Db::name('user')->where('id',$id)->value('open');
        if($open==1){
            Db::name('user')->where('id',$id)->update(['open'=>0]);
            $result['info'] = '关闭成功!';
            $result['status'] = 200;
            return $result;
        }else{
            Db::name('user')->where('id',$id)->update(['open'=>1]);
            $result['info'] = '开启成功!';
            $result['status'] = 200;
            return $result;
        }
    }

    public function excel(){
                $header = array('ID','账号','昵称','积分','认证','会员','状态','登录时间','注册时间');
                $data=Db::name('user')
                ->alias('u')
                ->field('u.id,u.username,u.nickname,u.integral,u.cadev,u.vip,u.open,u.logtime,u.regtime')->select();
                $num=Db::name('user')->count();
        		$this->writer($header,$data);
    }

    public function addexcel()
    {
         return $this->fetch();
    }
    public function inserexcel()
    {
        $file = request()->file('file');
        $info = $file->validate(['ext' => 'xls'])->move(ROOT_PATH . 'runtime' . DS . 'excel');
        if($info){
           $exclePath = $info->getSaveName();  //获取文件名
           $file_name = ROOT_PATH . 'runtime' . DS . 'excel' . DS . $exclePath;   //上传文件的地址
           $res=$this->reader($file_name);
           $data = [];
           foreach($res as $k=>$v){
                  // $data[$k]['id'] = $v['A'];
                   $data[$k]['username'] = $v['B'];
                   $data[$k]['nickname'] = $v['C'];
                   $data[$k]['integral'] = $v['D'];
                   $data[$k]['cadev'] = $v['E'];
                   $data[$k]['vip'] = $v['F'];
                   $data[$k]['open'] = $v['G'];
                   $data[$k]['logtime'] = $v['H'];
                   $data[$k]['regtime'] = $v['I'];
           }
          // var_dump($data);
           $a= Db::name('excel')->insertAll($data); //批量插入数据
           $id=Db::name('excel')->order('id desc')->limit(1)->value('id');

           Db::name('excel')->where('id',$id)->delete();
           if($a){
               $this->success('添加成功','index');
           }else{
               $this->error('excel参数有错误');
           }
        }
    }
    static function writer($header, $data,$name=false,$type = 0) {
        //导出
        $result = import("PHPExcel",EXTEND_PATH.'PHPExcel');
        if(!$name){$name=date("Y-m-d-H-i-s",time());}
        $objPHPExcel = new \PHPExcel();
        $objProps = $objPHPExcel->getProperties();
        //设置表头
        $key = ord("A");
        foreach($header as $v){
            $colum = chr($key);
            $objPHPExcel->getActiveSheet()->getColumnDimension($colum)->setWidth(15);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue($colum.'1', $v);
            $key += 1;
        }
        $column = 2;
        $objActSheet = $objPHPExcel->getActiveSheet();
        $objActSheet->getRowDimension(1)->setRowHeight(20);
        foreach($data as $key => $rows){ //行写入
            $span = ord("A");
            foreach($rows as $keyName=>$value) {// 列写入
                $j = chr($span);
                $objActSheet->getRowDimension($column)->setRowHeight(20);
                $objActSheet->setCellValue($j.$column, $value);
                $span++;
            }
            $column++;
        }
        $objPHPExcel->getActiveSheet()->setTitle('chen.data');
        $objPHPExcel->setActiveSheetIndex(0);
        $fileName = iconv("utf-8", "gb2312", './Data/excel/'.date('Y-m-d_', time()).time().'.xls');
        $saveName = iconv("utf-8", "gb2312", $name.'.xls');
        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        if ($type == 0) {
            header('Content-Type: application/vnd.ms-excel');
            header("Content-Disposition: attachment;filename=\"$saveName\"");
            header('Cache-Control: max-age=0');
            $objWriter->save('php://output');
        } else {
            $objWriter->save($fileName);
            return $fileName;
        }
    }

    static function reader($file) {
        if (self::_getExt($file) == 'xls') {
            $result = import("Excel5",EXTEND_PATH.'PHPExcel/PHPExcel/Reader');
            $PHPReader = new \PHPExcel_Reader_Excel5();
        } elseif (self::_getExt($file) == 'xlsx') {
            $result = import("Excel2007",EXTEND_PATH.'PHPExcel/PHPExcel/Reader');
            $PHPReader = new \PHPExcel_Reader_Excel2007();
        } else {
            return '路径出错';
        }

        $PHPExcel     = $PHPReader->load($file);
        $currentSheet = $PHPExcel->getSheet(0);
        $allColumn    = $currentSheet->getHighestColumn();
        $allRow       = $currentSheet->getHighestRow();
        for($currentRow = 1; $currentRow <= $allRow; $currentRow++){
            for($currentColumn='A'; $currentColumn <= $allColumn; $currentColumn++){
                $address = $currentColumn.$currentRow;
                $arr[$currentRow][$currentColumn] = $currentSheet->getCell($address)->getValue();
            }
        }
        return $arr;
    }

    private static function _getExt($file) {
        return pathinfo($file, PATHINFO_EXTENSION);
    }


    public function out(){
        $file_name   = "成绩单-".date("Y-m-d H:i:s",time());
        $file_suffix = "xlsx";
        header("Content-Type: application/vnd.ms-excel");
        header("Content-Disposition: attachment; filename=$file_name.$file_suffix");
        //根据业务，自己进行模板赋值。
        return $this->fetch();
    }

    public function in(){
        $content = file_get_contents('./UploadFiles/excel/ceshi.xls');
        dump($content);exit;

    }






}
