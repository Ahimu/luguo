<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:71:"E:\wampserver\wamp64\www\luguo./application/index\view\index\index.html";i:1516007546;}*/ ?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>layui</title>
  <meta name="renderer" content="webkit">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
  <link rel="stylesheet" href="_ADMIN_/layui/css/layui.css"  media="all">
  <!-- 注意：如果你直接复制所有代码到本地，上述css路径需要改成你本地的 -->
</head>
<body>
<style media="screen">
h2{ font-size: 26px;text-align: center;color: #393D49;}
h3{ font-size: 16px;color: #393D49;text-align: center;margin-top: 10px;}
p { text-indent:2em ;font-size: 16px;color: #393D49;margin-top: 10px;}
.fot{text-align: center;margin-top: 30px;}
</style>
<ul class="layui-nav layui-bg-cyan">
  <li class="layui-nav-item layui-this"><a href="<?php echo url('index/index'); ?>">首页</a></li>
  <!-- <li class="layui-nav-item"><a href="<?php echo url('video/index'); ?>">电影</a></li> -->
  <li class="layui-nav-item"><a href="<?php echo url('joke/index',['title'=>'段子']); ?>">笑话</a></li>
  <!-- <li class="layui-nav-item"><a href="">社区</a></li> -->
</ul>
<blockquote class="layui-elem-quote">每日一文--<?php echo $list['title']; ?></blockquote>
<div class="layui-container">
  <div class="layui-row">


    <h2><?php echo $list['title']; ?></h2>
    <h3>作者:<?php echo $list['author']; ?> &nbsp;&nbsp;&nbsp;时间:<?php echo $time['curr']; ?></h3>
    <p>
        <?php echo $list['content']; ?>
    </p>
    <div class="fot" >
        <a href="<?php echo url('index',['time'=>$time['prev']]); ?>" class="layui-btn">上一篇</a>
        <a <?php if($tor==$time['curr']){echo 'href="javascript:;"';}else{?> href="<?php echo url('index',['time'=>$time['next']]); ?>" <?php }?>class="layui-btn">下一篇</a>
    </div>
 </div>
</div>

<script src="_ADMIN_/layui/layui.js" charset="utf-8"></script>
<!-- 注意：如果你直接复制所有代码到本地，上述js路径需要改成你本地的 -->
<script>
layui.use('element', function(){
  var element = layui.element; //导航的hover效果、二级菜单等功能，需要依赖element模块

  //监听导航点击
  element.on('nav(demo)', function(elem){
    //console.log(elem)
    layer.msg(elem.text());
  });
});
</script>

</body>
</html>
