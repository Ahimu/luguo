<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件
function datemonthtime($month)
{
$year=date('Y',time());
$m = mktime(0,0,0,$month,1,$year);
$t =date('t',strtotime($m));
$end = mktime(23,59,59,$month,$t,$year);
$res['starttime']=$m;
$res['endtime']=$end;
return $res;
}


function qrcode($data,$filename,$picPath=false,$logo='false',$size='8',$level='L',$padding=2,$saveandprint=false){
    vendor("phpqrcode.phpqrcode");//引入工具包
    // 下面注释了把二维码图片保存到本地的代码,如果要保存图片,用$fileName替换第二个参数false
    $path =ROOT_PATH."\uploads\\QRcode"; //图片输出路径
    $picth=BASE_PATH."/uploads/QRcode";
    //$path =BASE_PATH."/uploads/QRcode"; //图片输出路径
    if(empty($path)){
        mkdir($path);
    }


    //在二维码上面添加LOGO
    if(empty($logo) || $logo=== false) { //不包含LOGO
        if ($filename==false) {
            $picth=$picth.'/'.$filename;
            \QRcode::png($data, false, $level, $size, $padding, $saveandprint); //直接输出到浏览器，不含LOGO
        }else{
            $picth=$picth.'/'.$filename;
            $filename=$path.'/'.$filename; //合成路径
            \QRcode::png($data, $filename, $level, $size, $padding, $saveandprint); //直接输出到浏览器，不含LOGO
        }
    }else { //包含LOGO
        if ($filename==false){
            $picth=$picth.'/'.$filename;
            $filename=tempnam('','').'.png';//生成临时文件
           die('参数错误');
        }else {
            //生成二维码,保存到文件
            $picth=$picth.'/'.$filename;
            $filename = $path . '\\' . $filename; //合成路径

        }
        $logo=BASE_PATH.'/public/static/home/images/ewmlogo.png';
        QRcode::png($data, $picth, $level, $size, $padding);
        $QR = imagecreatefromstring(file_get_contents($picth));
        $logo = imagecreatefromstring(file_get_contents($logo));
        $QR_width = imagesx($QR);
        $QR_height = imagesy($QR);
        $logo_width = imagesx($logo);
        $logo_height = imagesy($logo);
        $logo_qr_width = $QR_width / 5;
        $scale = $logo_width / $logo_qr_width;
        $logo_qr_height = $logo_height / $scale;
        $from_width = ($QR_width - $logo_qr_width) / 2;
        imagecopyresampled($QR, $logo, $from_width, $from_width, 0, 0, $logo_qr_width, $logo_qr_height, $logo_width, $logo_height);
        if ($filename === false) {
            Header("Content-type: image/png");
            imagepng($QR);
        } else {
            if ($saveandprint === true) {
                imagepng($QR, $filename);
                header("Content-type: image/png");//输出到浏览器
                imagepng($QR);
            } else {
                imagepng($QR, $filename);
            }
        }
    }
    return $picth;
    //return $filename;
}

/**
 * 判断当前访问的用户是  PC端  还是 手机端  返回true 为手机端  false 为PC 端
 *  是否移动端访问访问
 * @return boolean
 */
function isMobile()
{
    // 如果有HTTP_X_WAP_PROFILE则一定是移动设备
    if (isset ($_SERVER['HTTP_X_WAP_PROFILE']))
        return true;

    // 如果via信息含有wap则一定是移动设备,部分服务商会屏蔽该信息
    if (isset ($_SERVER['HTTP_VIA']))
    {
        // 找不到为flase,否则为true
        return stristr($_SERVER['HTTP_VIA'], "wap") ? true : false;
    }
    // 脑残法，判断手机发送的客户端标志,兼容性有待提高
    if (isset ($_SERVER['HTTP_USER_AGENT']))
    {
        $clientkeywords = array ('nokia','sony','ericsson','mot','samsung','htc','sgh','lg','sharp','sie-','philips','panasonic','alcatel','lenovo','iphone','ipod','blackberry','meizu','android','netfront','symbian','ucweb','windowsce','palm','operamini','operamobi','openwave','nexusone','cldc','midp','wap','mobile');
        // 从HTTP_USER_AGENT中查找手机浏览器的关键字
        if (preg_match("/(" . implode('|', $clientkeywords) . ")/i", strtolower($_SERVER['HTTP_USER_AGENT'])))
            return true;
    }
    // 协议法，因为有可能不准确，放到最后判断
    if (isset ($_SERVER['HTTP_ACCEPT']))
    {
        // 如果只支持wml并且不支持html那一定是移动设备
        // 如果支持wml和html但是wml在html之前则是移动设备
        if ((strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') !== false) && (strpos($_SERVER['HTTP_ACCEPT'], 'text/html') === false || (strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') < strpos($_SERVER['HTTP_ACCEPT'], 'text/html'))))
        {
            return true;
        }
    }
    return false;
}

function send_mail($toemail,$name,$subject,$content)
{
       vendor('phpmailer.PHPMailerAutoload');
       $mail= new PHPMailer();
       /*服务器相关信息*/
       $mail->IsSMTP();                 //设置使用SMTP服务器发送
       $mail->SMTPAuth  = true;               //开启SMTP认证
       $mail->Host     = 'smtp.163.com';        //设置 SMTP 服务器,自己注册邮箱服务器地址 QQ则是ssl://smtp.qq.com
       $mail->Port = 25;
       $mail->Username   = 'cat_lkk@163.com';  //发信人的邮箱名称，本人网易邮箱 zzy9i7@163.com 这里就写
       $mail->Password   = 'lishishouquanma1';    //发信人的邮箱密码
      // $mail->SMTPSecure = 'tls';
       /*内容信息*/
       $mail->IsHTML(true);               //指定邮件格式为：html *不加true默认为以text的方式进行解析
       $mail->CharSet    ="UTF-8";               //编码
       $mail->From       = 'cat_lkk@163.com';             //发件人完整的邮箱名称
       $mail->FromName   = $name;            //发信人署名
       $mail->Subject    = $subject;               //信的标题
       $mail->MsgHTML($content);                 //发信主体内容
       //$mail->AddAttachment("15.jpg");         //附件
       /*发送邮件*/
       $mail->AddAddress($toemail);              //收件人地址
       //使用send函数进行发送
       if($mail->Send()) {
           return true;
       } else {
            //self::$error=$mail->ErrorInfo;
            var_dump($mail->ErrorInfo);die;
            return   false;
       }
}
