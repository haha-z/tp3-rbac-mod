<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
	<title>rbac权限管理系统</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
	  <link rel="stylesheet" href="/Public/static/css/bootstrap.min.css">
  <script src="/Public/static/js/jquery-2.1.3.min.js"></script>
	<!-- Custom styles for this template -->
  <link href="/Public/static/css/dashboard.css" rel="stylesheet">
</head>
<body>

<nav class="navbar navbar-default navbar-fixed-top">
  <div class="container-fluid">
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="/">Rbac</a>
    </div>

    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
      <ul class="nav navbar-nav">
        <li class="active"><a href="#">首页 <span class="sr-only">(current)</span></a></li>
        <li class=""><a href="#">内容管理</a></li>
        <li class=""><a href="#">用户管理</a></li>
        <li class=""><a href="#">系统管理</a></li>
      </ul>
  		<p class="navbar-text navbar-right">Hi,  <?php echo $GLOBALS['info']['name'] ?><a href="#" class="navbar-link">
			<!-- <?php if(($cuser['is_admin']) == "1"): ?>超级管理员<?php endif; ?> -->
      </a></p>
    </div><!-- /.navbar-collapse -->
  </div><!-- /.container-fluid -->
</nav>

<!-- 左边 -->
<div class="container-fluid">
	<div class="col-sm-2 col-md-2 col-lg-2 sidebar">
		<ul class="nav nav-sidebar">
   		<li class="active">权限测试页面</li>
     	<li><a href="<?php echo U('test/page1');?>" >页面一</a></li>
     	<li><a href="<?php echo U('test/page2');?>" >页面二</a></li>
     	<li><a href="<?php echo U('test/page3');?>" >页面三</a></li>
     	<li class="active">权限设置</li>
     	<li><a href="<?php echo U('User/index');?>">用户管理</a></li>
     	<li><a href="<?php echo U('Role/index');?>">角色管理</a></li>
     	<li><a href="<?php echo U('Access/index');?>">权限管理</a></li>
	    </ul>
	</div>

	<!-- 右边 -->
	<div class="col-sm-10 col-sm-offset-2 col-md-10 col-md-offset-2 col-lg-10 col-lg-offset-2 ">
    <div id="content">
      <!-- 左边 -->
<div class="row">
  <div class="col-xs-9 col-sm-9 col-md-9 col-lg-9">
    <h5>新增用户</h5>
  </div>
</div>
<hr/>
<div class="row">
  <div class="form-horizontal user_set_wrap" role="form">
    <div class="form-group">
      <label class="col-xs-2 col-sm-2 col-md-2 col-lg-2 control-label">用户</label>
      <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
        <input type="text" name="name" class="form-control" placeholder="请输入用户名" value="<?php echo ($user["name"]); ?>">
      </div>
      
    </div>
    <div class="form-group">
      <label class="col-xs-2 col-sm-2 col-md-2 col-lg-2 control-label">邮箱</label>
      <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
        <input type="text" name="email" class="form-control" placeholder="请输入邮箱" value="<?php echo ($user["email"]); ?>">
      </div>
    </div>
     <div class="form-group">
      <label class="col-xs-2 col-sm-2 col-md-2 col-lg-2 control-label">所属角色</label>
      <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
          <?php if(is_array($role)): foreach($role as $key=>$v): ?><div class="checkbox">
              <input type="checkbox" name="role_ids[]" value="<?php echo ($v["id"]); ?>"><?php echo ($v["name"]); ?>
            </div><?php endforeach; endif; ?>
      </div>
    </div>
    <div class="col-xs-6 col-xs-offset-2 col-sm-6 col-sm-offset-2 col-md-6 col-md-offset-2 col-lg-6 col-sm-lg-2 ">
      <input type="hidden" name="id" value="<?php echo ($user["id"]); ?>"/>
      <button type="submit" class="btn btn-primary pull-right " id="save">确定</button>
    </div>
  </div>
</div>
<script type="text/javascript">
  $(".user_set_wrap #save").click(function(){
    // var btn_target = $(this);
    // if (btn_target).hasClass('disabled') {
    //   alert("正在处理，不要重复提交！");return false;
    // }

    var id = $(" .user_set_wrap input[name=id]").val();
    var name = $(" .user_set_wrap input[name=name]").val();
    var email = $(".user_set_wrap input[name=email]").val();


    var role_ids = [];
    $(".user_set_wrap input[name='role_ids[]']").each( function(){
      if( $(this).prop("checked") ){
        role_ids.push( $(this).val() );
      }
    });

    // btn_target.addClass("disabled");

    alert(role_ids);
    $.ajax({
      type:"POST",
      data:{
        id:id,
        name:name,
        email:email,
        role_ids:role_ids,
      },
      url:"<?php echo U('Home/User/set');?>",
      async:true,
      timeout:5000,    //超时时间
      dataType:'json', //返回的数据格式：json/xml/html/script/jsonp/text
      success:function(data, textStatus){
        alert(data.msg);
        console.log(data.msg)
        if (data.status == 1 ) {window.location.href = "<?php echo U('Home/User/index');?>";}
      },
      error:function(data, textStatus){
        alert(data.msg);
        console.log(data.msg)
      },
    });
  });
</script>
</body>
</html>
    </div>
    <hr/>
		<footer>
			<p class="pull-left">@哈哈周</p>
			<p class="pull-right">Power by bootstrap</p>
		</footer>
	</div>
</div>


</body>
</html>