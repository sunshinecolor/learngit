<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>金飞捷SVN版本管理</title>
	<link rel="stylesheet" type="text/css" href="<?php echo __CSS__; ?>common.css"/>
	<link rel="stylesheet" type="text/css" href="<?php echo __CSS__; ?>personal.css"/>
</head>
<body>
<?php include_once ('view/header.php') ?>
		<div class="content left ">
            <div class="con_title">
                <h2>个人信息:</h2>
            </div>
			<div class="con_left left">
				<div class='personal_all'>
					<ul class="personal_pos">
						<li><span>用户ID：</span><?php echo $personalinfo['user_id'] ?></li>
						<li><span>用户名：</span><?php echo $personalinfo['username'] ?></li>
						<li><span>姓名：</span><?php echo $personalinfo['realname'] ?></li>
						<li><span>性别：</span><?php echo $sexes ?></li>
						<li><span>电话：</span><?php echo $personalinfo['phone'] ?></li>
						<li><span>邮箱：</span><?php echo $personalinfo['email'] ?></li>
						<li><span>权限：</span><?php echo '用户、部门('.$user_mes.')&nbsp&nbsp|&nbsp&nbsp项目('.$proj_mes.')&nbsp&nbsp|&nbsp&nbsp授权('.$auth_mes.')' ?></li>
						<li><span>所属部门：</span><?php echo $personalinfo['department_name'] ?></li>
						<li><span>所属用户组：</span><?php if(!empty($personalgroup)){ foreach ($personalgroup as $value){ echo $value['group_name'].' / ';};}else{echo '暂无数据';} ?></li>
						<li><span>创建时间：</span><?php echo $personalinfo['create_time'] ?></li>
						<li><span>登录次数：</span><?php echo $personalinfo['login_count'] ?></li>
						<li><span>最后一次登录时间：</span><?php echo $personalinfo['login_time'] ?></li>
					</ul>
					<button class="basic_edit">基本信息修改</button>
					<button class="pass_edit">密码修改</button>
				</div>
				<form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>" method="post" class='edit_personal personal_pos hide'>
					<label for="uname">用户名:</label>
					<input type="text" name="uname" id="uname" value="<?php echo $personalinfo['username'] ?>" disabled="disabled" /><br />
					<label for="real_name">姓名:</label>
					<input type="text" name="real_name" id="real_name" value="<?php echo $personalinfo['realname'] ?>" /><br />
					<label for="sex">性别:</label>
					<input type="radio" name="sex" value="0" id="sex" <?php if($personalinfo['sexes']==0) echo 'checked="checked"' ?> /><span class="font_pos">保密</span>
					<input type="radio" name="sex" value="2" id="" <?php if($personalinfo['sexes']==2) echo 'checked="checked"' ?> /><span class="font_pos">女</span>
					<input type="radio" name="sex" value="1" id="" <?php if($personalinfo['sexes']==1) echo 'checked="checked"' ?> /><span class="font_pos">男</span><br />
					<label for="phone">电话:</label>
					<input type="text" name="phone" id="phone" value="<?php echo $personalinfo['phone'] ?>" /><br />
					<label for="email">邮箱:</label>
					<input type="text" name="email" id="email" value="<?php echo $personalinfo['email'] ?>" /><br />
                    <input type="submit" value="保存"/>
                    <input type="hidden" name="hid" value="basic">
                </form>
				<form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>" method="post" class="edit_personal personal_pos edit_pass hide">
					<label for="uid">旧密码:</label>
					<input type="password" name="old_pass" id="uid" /><br />
					<label for="new_pass">新密码:</label>
					<input type="password" name="new_pass" id="new_pass" /><span class="tip">密码必须为6~32位，英文字母、数字和下划线的组合</span><br />
					<label for="check_pass">确认密码:</label>
					<input type="password" name="check_pass" id="check_pass" />
					<input type="submit" value="保存"/>
					<input type="hidden" name="hid" value="pw">
				</form>
			</div>
			<?php if($personalinfo['login_count']==1){
                echo "<div class=\"con_right right\"> <button class=\"wbasic_edit\">基本信息完善</button> </div>";
            } ?>
		</div>
	</section>
	<footer>
		<b>Copyright©2016</b>
		<span>深圳市金飞捷科技有限公司</span>
		AII Rights Reserved.
	</footer>
	<script src="<?php echo __JS__; ?>jquery-1.11.0.js" type="text/javascript" charset="utf-8"></script>
	<script src="<?php echo __JS__; ?>common.js" type="text/javascript" charset="utf-8"></script>
</body>
</html>