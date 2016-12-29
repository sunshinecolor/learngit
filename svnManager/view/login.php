<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>金飞捷SVN版本管理</title>
		<link rel="stylesheet" href="<?php echo __CSS__; ?>common.css" />
		<link rel="stylesheet" href="<?php echo __CSS__; ?>login.css" />
</head>
<body>
	<h1>Subversion 版本管理系统管理后台</h1>
	<form id="login" method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>">
		<input class="uname" type="text" name="username" id="" value="" placeholder="用户名"/>
		<input type="password" name="password" id="" placeholder="密码"/><br />
		<input type="checkbox" name="" id="" /><span class="keep_pass">记住密码</span><br />
		<input type="submit" name="" value="登录" />
		<span class="lost_pass">忘记密码?</span>
		<p class='hide'>系统不提供忘记密码功能，如忘记密码，请与管理员联系重置密码</p>
	</form>
	<script src="<?php echo __JS__; ?>jquery-1.11.0.js" type="text/javascript" charset="utf-8"></script>
	<script src="<?php echo __JS__; ?>common.js" type="text/javascript" charset="utf-8"></script>
	<script>
		$('.lost_pass').hover(function(){
			$('#login p.hide').toggle();
		},function(){
			$('#login p.hide').toggle();
		});
	</script>
</body>
</html>