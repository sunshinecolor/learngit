<header id='header'>
	<a href="" class='logo'>
		<img src="<?php echo __IMG__; ?>logo.png" alt="" />
	</a>
	<div class="hea_right right">
		<a class='login' >欢迎 <?php echo $username; ?> 登录</a>
		<a class='logout' href="logout.php">退出</a>
	</div>
</header>
<section class="clearfix">
<div class="lnav left">
	<ul>
		<?php $html = substr($_SERVER['PHP_SELF'],strrpos($_SERVER['PHP_SELF'],'/')+1,strlen($_SERVER['PHP_SELF']));?>
		<li>
            <a class="<?php if($html=='index.php') echo 'active';else echo 'lost';?>" href="index.php">首页</a>
        </li>
		<li>
            <a class="<?php if($html=='personal.php') echo 'active';else echo 'lost';?>" href="personal.php">个人中心</a>
        </li>
<!--		<li>-->
<!--            <a class="--><?php //if($html=='man_wpro.php') echo 'active';else echo 'disable';?><!--" href="man_wpro.php">全部项目</a>-->
<!--        </li>-->
		<li>
            <a class="<?php if($_SESSION['check_auth']['user']>=1 ){if(($html=='man_user.php')) echo 'active';else echo 'lost';echo '" href="man_user.php"'; }else{
                echo 'disable" href="javascript:void(0)"';
            } ?> >用户管理</a>
        </li>
		<li>
            <a class="<?php if($_SESSION['check_auth']['user']>=1 ){if($html=='man_department.php') echo 'active';else echo 'lost';echo '" href="man_department.php"'; }else{
                echo 'disable" href="javascript:void(0)"';
            } ?> >部门管理</a>
        </li>
		<li>
            <a class="<?php if($_SESSION['check_auth']['proj']>=1 ){if($html=='man_pro.php') echo 'active';else echo 'lost';echo '" href="man_pro.php"'; }else{
                echo 'disable" href="javascript:void(0)"';
            } ?> >项目管理</a>
        </li>
		<li class='last_bott'>
            <a class="<?php if($_SESSION['check_auth']['auth']>=1 ){if($html=='man_authority.php') echo 'active';else echo 'lost';echo '" href="man_authority.php"'; }else{
                echo 'disable" href="javascript:void(0)"';
            } ?> >授权管理</a>
        </li>
	</ul>
</div>