<?php
namespace app\admin\controller;
use think\Db;
use think\Request;
use think\Session;
use think\Controller;
use think\Config;
use org\Backup;
class Database extends Controller
{

        protected $db = '', $datadir =  './public/Data/';

        function _initialize(){
               parent::_initialize();
               $db=db('');
               $this->db =   db::connect();
        }

        public function index(){
            $dbtables = Db::query("SHOW TABLE STATUS LIKE '".config('prefix')."%'");
            $total = 0;
            foreach ($dbtables as $k => $v)
            {
                $dbtables[$k]['size'] = format_bytes($v['Data_length'] + $v['Index_length']);
                $total += $v['Data_length'] + $v['Index_length'];
            }
            $db= new Backup();
            $list=$db->dataList();
            //var_dump($list);
           $this->assign('dbtables', $dbtables);
           $this->assign('total', format_bytes($total));
           $this->assign('tableNum', count($dbtables));

           return $this->fetch();
        }

        public function tablelist(){
            $table=input('table');
            $list = Db::query("SHOW FULL FIELDS FROM $table");
            //var_dump($list);
            $this->assign('table', $table);
            $this->assign('list', $list);
            return $this->fetch();

        }

        public function newtables(){
            if (request()->isPost()){
                    $table=input('tablename');
                    $tables = Db::getTables();
                    $table="sh_".$table;
                    if(in_array($table,$tables)){
                        return $this->error('表名已存在!');
                    }
                    $sql="CREATE TABLE IF NOT EXISTS `$table` (
                    `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '信息id',
                    `title` varchar(30) NOT NULL COMMENT '标题',
                    `picurl` varchar(100) NOT NULL COMMENT '上传内容地址',
                    `linkurl` varchar(255) NOT NULL COMMENT '跳转链接',
                    `orderid` smallint(5) unsigned NOT NULL COMMENT '排列排序',
                    `posttime` int(10) unsigned NOT NULL COMMENT '提交时间',
                    `open` enum('1','0') NOT NULL COMMENT '审核状态',
                    PRIMARY KEY (`id`)
                    ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ";
                    Db::query($sql);
                    return  $this->redirect('tablelist',['table'=>$table]);
            }else{
                    return $this->fetch();
            }

        }

        public function deltable(){
            $table=input('name');
            $sql="drop  table $table ";
            Db::query($sql);
            $result['info'] = '删除表成功!';
            $result['status'] = 1;
            return $result;

        }

        public function addfield(){

            if (request()->isPost()){
                //构建数组
                $data = input('post.');
                $fieldname=$data['fieldname'];
                $fielddesc=$data['fielddesc'];
                $fieldlong=$data['fieldlong'];
                $kong=$data['kong'];
                $table=$data['table'];
                $arr=Db::query("show columns from $table");
                foreach ($arr as $v) {
                    $Field[]=$v['Field'];
                }
                if(in_array($fieldname, $Field)){
                    return $this->error('字段名已存在!');
                }
                //return json_encode(in_array($fieldname, $Field));
                if($kong==1){
                    $str="NOT NULL";
                }else{
                    $str="NULL";
                }
                if($data['fieldsel']=='enum'){
                    $sql="ALTER TABLE `$table`
                    ADD COLUMN `$fieldname`  enum('1','0') ".$str." COMMENT '$fielddesc' AFTER `id`";
                }else{
                    $sql="  ALTER TABLE `$table`
                     ADD COLUMN `$fieldname`  varchar($fieldlong) ".$str." COMMENT '$fielddesc' AFTER `id`";
                }
                Db::query($sql);
                return  $this->redirect('tablelist',['table'=>$table]);
            }else{
                $table=input('table');
                $this->assign('table', $table);
                return $this->fetch();
            }
        }

        public function delfield(){
            $fieldname=input('name');
            $table=input('table');
            $sql="ALTER TABLE $table DROP $fieldname";
            Db::query($sql);
            $result['info'] = '删除成功!';
            $result['status'] = 1;
            return $result;
        }

        public function optimize() {
            $tableName = input('tableName');
            $res=DB::query("OPTIMIZE TABLE $tableName");
            if($res) {
                $result['msg'] = '优化表成功!';
                $result['code'] = 1;
                return $result;
            }else{
                $result['msg'] = '优化表失败!';
                $result['code'] = 0;
                return $result;
            }
        }

        //修复
        public function repair() {
            $tableName = input('tableName');
            $res=DB::query("REPAIR TABLE $tableName");
            if($res) {
                $result['msg'] = '修复表成功!';
                $result['code'] = 1;
                return $result;
            }else{
                $result['msg'] = '修复表失败!';
                $result['code'] = 0;
                return $result;
            }
        }

        public function backup(){
            $db= new Backup();

            $tables = input('tables/a');
            foreach($tables as $key=>$table) {
                $file=['name'=>date('Ymd-His'),'part'=>1];
                $start= $db->setFile($file)->backup($table, 0);
              }

            if($start==0){
                $result['msg'] = '成功备份数据库!';
                $result['code'] = 1;
                return $result;
            }else{
                $result['msg'] = '备份数据库失败!';
                $result['code'] = 1;
                return $result;
            }
        }
        public function restore(){
                $db= new Backup();
                $list=$db->fileList();
                $this->assign('list', $list);
            return $this->fetch();
        }
        public function import(){
                $db= new Backup();
                $time = input('time');
                $part = null;
                $start = null;
                $list  = $db->getFile('timeverif',$time);
                if(is_array($list)){
                    session::set('backup_list', $list);
                    $part = 1;
                    $start = 0;
                }else{
                    $result['msg'] = '备份文件可能已经损坏，请检查！!';
                    $result['code'] = 0;
                    return $result;
                }
                $list=session::get('backup_list');

                $start= $db->setFile($list)->import($start);

                   $result['msg'] = '还原成功!';
                   $result['code'] = 1;
                   return $result;
              





        }
        public function downFile() {
           $file = $this->request->param('file');
           $file=date("Ymd-His",$file)."-1.sql";

           $type = $this->request->param('type');
           if (empty($file) || empty($type) || !in_array($type, array("zip", "sql"))) {
               $this->error("下载地址不存在");
           }
           $path = array("zip" => $this->datadir."zipdata/", "sql" => $this->datadir);
           $filePath = $path[$type] . $file;

           if (!file_exists($filePath)) {
               $this->error("该文件不存在，可能是被删除");
           }
           $filename = basename($filePath);
           header("Content-type: application/octet-stream");
           header('Content-Disposition: attachment; filename="' . $filename . '"');
           header("Content-Length: " . filesize($filePath));
           readfile($filePath);
        }

        public function del() {
            $db= new Backup();
            $time = input('time');
            //批量删除
            $a=$db->delFile($time);
            if($a){
               $result['msg'] = '删除成功!';
               $result['code'] = 1;
               return $result;
            }else{
               $result['msg'] = '删除失败!';
               $result['code'] = 0;
               return $result;
            }
        }






}
