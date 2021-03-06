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
      <a class="navbar-brand" href="<?php echo U('Index/index');?>">Rbac</a>
    </div>

    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
      <ul class="nav navbar-nav">
        <li class="active"><a href="#">首页 <span class="sr-only">(current)</span></a></li>
      </ul>
  		<p class="navbar-text navbar-right">Hi,  <?php echo $GLOBALS['info']['name'] ?><a href="#" class="navbar-link">
			<!-- <?php if(($cuser['is_admin']) == "1"): ?>超级管理员<?php endif; ?> -->
      </a></p>
      <p class="navbar-text navbar-right"><a href="<?php echo U('Public/logout');?>" class="navbar-link">退出</a></p>
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
      <li><a href="<?php echo U('test/page4');?>" >页面4</a></li>
     	<li class="active">权限设置</li>
     	<li><a href="<?php echo U('User/index');?>">用户管理</a></li>
     	<li><a href="<?php echo U('Role/index');?>">角色管理</a></li>
     	<li><a href="<?php echo U('Access/index');?>">权限管理</a></li>
      <li><a href="<?php echo U('Access/index');?>">操作日志</a></li>
	    </ul>
	</div>

	<!-- 右边 -->
	<div class="col-sm-10 col-sm-offset-2 col-md-10 col-md-offset-2 col-lg-10 col-lg-offset-2 ">
    <div id="content">
      <div class="row">
  <div class="col-xs-9 col-sm-9 col-md-9  col-lg-9">
    <h5>为【<?php echo ($user["name"]); ?>】设置权限</h5>
  </div>
</div>
<hr/>
<div class="row">
  <div class="form-horizontal role_access_set_wrap" role="form">
    <div class="form-group">
        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
          <?php if(is_array($access_list)): foreach($access_list as $key=>$v): ?><div class="checkbox">
               <label>
                <input type="checkbox" name="access_ids[]" value="<?php echo ($v["id"]); ?>" <?php if( in_array( $v['id'] ,$select_access_ids ) ):?> checked <?php endif;?>  /> <?php echo ($v['title']); ?>
                </label>
            </div><?php endforeach; endif; ?>
        </div>
    </div>
    <div class="col-xs-6 col-xs-offset-2 col-sm-6 col-sm-offset-2 col-md-6  col-md-offset-2 col-lg-6 col-sm-lg-2 ">
      <input type="hidden" name="id" value="<?php echo ($user["id"]); ?>">
      <button type="button" class="btn btn-primary pull-right save">确定</button>
    </div>
  </div>
</div>


<script type="text/javascript">
   $(".role_access_set_wrap .save").click(function(){
    var btn_target = $(this);

    if (btn_target.hasClass('disabled')) {
      alert("正在处理，不要重复提交！");
      return false;
    }

    var id = $(" .role_access_set_wrap input[name=id]").val();

    var access_ids = [];
    $(".role_access_set_wrap input[name='access_ids[]']").each( function(){
      if( $(this).prop("checked") ){
        access_ids.push( $(this).val() );
      }
    });

    btn_target.addClass("disabled");

    $.ajax({
      type:"POST",
      data:{
        id:id,
        access_ids:access_ids,
      },
      url:"<?php echo U('Home/Role/access');?>",
      async:true,
      timeout:5000,    //超时时间
      dataType:'json', //返回的数据格式：json/xml/html/script/jsonp/text
      success:function(data){
        alert(data.msg);
        console.log(data.msg);
        btn_target.removeClass("disabled");
        if (data.status == 1 ) {window.location.href = "<?php echo U('Home/role/index/');?>";}
      },
      error:function(data, textStatus){
        alert(data.msg);
        console.log(data.msg);
        btn_target.removeClass("disabled");
      },
    });
  });
</script>

    </div>
    <hr/>
		<footer>
			<p class="pull-left">@haha-z</p>
		</footer>
	</div>
</div>


</body>
</html>