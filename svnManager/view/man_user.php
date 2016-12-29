<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>金飞捷SVN版本管理</title>
	<link rel="stylesheet" type="text/css" href="<?php echo __CSS__; ?>common.css"/>
	<link rel="stylesheet" type="text/css" href="<?php echo __CSS__; ?>personal.css"/>
	<link rel="stylesheet" type="text/css" href="<?php echo __CSS__; ?>man_user.css"/>
</head>
<body>
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
				<li><a class="lost" href="index.php">首页</a></li>
				<li><a class="lost" href="personal.php">个人中心</a></li>
				<li class="lnav_man_user">
					<a class="active" href="man_user.php">用户管理</a>
					<div class='manuser_list'>
						<a class="active" href="man_user.php">用户信息</a>
						<a class="lost" href="man_usergroup.php">用户组信息</a>
					</div>
				</li>
				<li><a class="lost" href="man_department.php">部门管理</a></li>
				<li><a class="lost" href="man_pro.php">项目管理</a></li>
				<li class="last_bott"><a class="lost" href="man_authority.php">授权管理</a></li>
			</ul>
		</div>
		<div class="content left">
			<div class="con_title">
				<h2>功能及信息列表:</h2>
                <div class="search">
                    <form action="" method="get">
                        <input type="text" name="like" id="" placeholder="请输入用户名、姓名、部门关键字"/>
                        <input type="submit" value="搜索" />
                    </form>
                </div>
			</div>
			<div class='opreat left'>
				<button class='all_btn'>所有用户</button>
				<button class='lost_btn add_btn add_btn_personalajax' attr-name="user_add" >增加用户</button>
			</div>
			<div class='user_info left'>
				<div class="view_bor">
					<div class="view_all">
						<table border="1">
							<tr>
								<th>用户ID</th>
								<th>用户名</th>
								<th>姓名</th>
								<th>所属部门</th>
                                <th>所属用户组</th>
                                <th>功能权限</th>
                                <th>用户状态</th>
<!--                                <th>项目权限</th>-->
                                <th>删改操作</th>
							</tr>
							<?php foreach($info as $k =>$v){ ?>
							<tr>
								<td><?php echo $v['info']['user_id'] ?></td>
								<td><?php echo $v['info']['username'] ?></td>
								<td><?php echo $v['info']['realname'] ?></td>
								<td><?php echo $v['info']['department_name'] ?></td>
                                <td><?php if(!empty($v['group'])) {foreach ($v['group'] as $key => $value){ echo $value['group_name'].' / ';}}else{ echo '暂无数据';}  ?></td>
                                <td><?php if(!empty($v['auth'])){echo '用户、部门('.$v['auth']['user_mes'].')<br /> 项目('.$v['auth']['proj_mes'].')<br /> 授权('.$v['auth']['auth_mes'].')'; }else{ echo '无权限';} ?></td>
                                <td><?php if($v['info']['active']==1) {echo '可用';}else{ echo '不可用';} ?></td>
                                <td><button class="edit_btn edit_btn_personalajax" value="<?php echo $v['info']['user_id'] ?>" attr-name="user_edit">编辑</button>
                                    <button class="del_btn del_btn_personalajax" value="<?php echo $v['info']['user_id'] ?>" attr-name="user_del">删除</button>
                                </td>
							</tr>
							<?php }; ?>
						</table>
                        <?php echo $link ?>
					</div>					
					<form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>" method="post" class='hide user_add edit_personal personal_pos'>
						<label for="uname" >用户名:</label>
						<input type="text" name="uname" id="uname" />
                        <span class="tip">用户名必须为3~20位的英文字母、数字和下划线的组合，且首字符是英文字母</span><br />
						<label for="real_name">姓名:</label>
						<input type="text" name="real_name" id="real_name" /><br />
                        <label for="">所属部门:</label>
                        <select id="u_a_departments" name="user_departments">
                        </select><br />
                        <label for="check">所属用户组:</label>
                        <div class='user_group' id="u_a_group">
                        </div>
                        <br />
                        <label for="user_state">用户状态:</label>
                        <input type="radio" name="user_state" value="1" checked="checked" /><span class="font_pos">可用</span>
                        <input type="radio" name="user_state" value="0" /><span class="font_pos">不可用</span><br />
						<label for="new_pass">登录密码:</label>
						<input type="password" name="new_pass" id="new_pass" /> <span class="tip">密码必须为6~32位，英文字母、数字和下划线的组合</span><br />
						<label for="check_pass">确认密码:</label>
						<input type="password" name="check_pass" id="check_pass" /><br />
                        <input type="hidden" name="hid" value="u_add">
                        <input type="submit"  value="添加"/>
					</form>
					<form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>" method="post" class='hide user_edit edit_personal personal_pos'>
						<label for="uid">用户ID:</label>
						<input type="text" disabled="disabled" id="u_e_uid" /><br />
						<input type="hidden" id="u_e_uid_h"  name="uid" />
						<label for="uname">用户名:</label>
						<input type="text" disabled="disabled" name="uname" id="u_e_uname" /><br />
						<label for="real_name">姓名:</label>
						<input type="text" name="real_name" id="u_e_real_name" /><br />
						<label for="">所属部门:</label>
						<select id="u_e_departments" name="user_departments">
						</select><br />
						<label for="check">所属用户组:</label>
						<div class='user_group' id="u_e_group">
						</div>
                        <br />
                        <label for="user_state">用户状态:</label>
                        <input type="radio" name="user_state" value="1" id="user_state_e_1" checked="" /><span class="font_pos">可用</span>
                        <input type="radio" name="user_state" value="0" id="user_state_e_0" checked="" /><span class="font_pos">不可用</span><br />
						<label for="new_pass">登录密码:</label>
						<input type="password" name="new_pass" id="u_e_new_pass" /><span class="tip">密码必须为6~32位，英文字母、数字和下划线的组合</span><br />
						<label for="check_pass">确认密码:</label>
						<input type="password" name="check_pass" id="u_e_check_pass" />
                        <input type="hidden" name="hid" value="u_edit">
                        <input type="submit" value="修改"/>
					</form>
				</div>
			</div>
		</div>
	</section>
	<footer>
		<b>Copyright©2016</b>
		<span>深圳市金飞捷科技有限公司</span>
		AII Rights Reserved.
	</footer>
	<script src="<?php echo __JS__; ?>jquery-1.11.0.js" type="text/javascript" charset="utf-8"></script>
	<script src="<?php echo __JS__; ?>common.js" type="text/javascript" charset="utf-8"></script>
	<script>
    $('.add_btn_personalajax').click(function(){
        var type = $(this).attr('attr-name');
        $.ajax({
            url: "man_user_ajax.php",
            type:'POST',
            complete :function(){}, //請求完回調的函數，無論成功與失敗都會調用，在success後
            dataType: 'json',
            data: {type:type},
            error: function() { alert('Ajax request 發生錯誤');},
            success: function(res) {
                if (res.state == 'Error'){//没有数据
                    alert(res.mes);
                }else{//有数据
                    if(res.type == 'user_add'){
                        var departments = res.mes.departments;
                        var str = '';
                        $.each(departments, function(key, val) {
                            str = str + "<option value=\"";
                            str = str + val['department_id'];
                            str = str + "\" >";
                            str = str + val['department_name'];
                            str = str + "</option>";
                        });
                        $('#u_a_departments').children().remove();
                        $('#u_a_departments').append(str);
                        var group = res.mes.group;
                        var	str = '';
                        $.each(group, function(key, val) {
                            str = str + "<input value=\"";
                            str = str + val['group_id'];
                            str = str + "\" type=\"checkbox\" name=\"user_group[]\" id=\"check=\" ";
                            str = str + " /><span class=\"font_pos\">";
                            str = str + val['group_name'];
                            str = str + "</span>";
                        });
                        $('#u_a_group').children().remove();
                        $('#u_a_group').append(str);
                    }
                }
            }
        });
    });

    $('.edit_btn_personalajax').click(function(){
        var id = $(this).val();
        var type = $(this).attr('attr-name');
        $.ajax({
            url: "man_user_ajax.php",
            type:'POST',
            complete :function(){}, //請求完回調的函數，無論成功與失敗都會調用，在success後
            dataType: 'json',
            data: {id:id,type:type},
            error: function() { alert('Ajax request 發生錯誤');},
            success: function(res) {
                if (res.state == 'Error'){//没有数据
                    alert(res.mes);
                }else{//有数据
                    if(res.type == 'user_edit'){
                        $('#u_e_uid').val(res.mes.info.user_id);
                        $('#u_e_uid_h').val(res.mes.info.user_id);
                        $('#u_e_uname').val(res.mes.info.username);
                        $('#u_e_real_name').val(res.mes.info.realname);
                        if(res.mes.info.active == 1){
                            $('#user_state_e_1').attr("checked",'checked');
                            $('#user_state_e_0').removeAttr("checked");
                        }else{
                            $('#user_state_e_1').removeAttr("checked");
                            $('#user_state_e_0').attr("checked",'checked');
                        }
                        var departments = res.mes.departments;
                        var str = '';
                        $.each(departments, function(key, val) {
                            str = str + "<option value=\"";
                            str = str + val['department_id'];
                            if(val['user_id']==res.mes.info.user_id){
                                str = str + "\" selected=\"selected\">";
                            }else{
                                str = str + "\" >";
                            }
                            str = str + val['department_name'];
                            str = str + "</option>";
                        });
                        $('#u_e_departments').children().remove();
                        $('#u_e_departments').append(str);
                        var group = res.mes.group;
                        var	str = '';
                        $.each(group, function(key, val) {
                            str = str + "<input value=\"";
                            str = str + val['group_id'];
                            str = str + "\" type=\"checkbox\" name=\"user_group[]\" id=\"check=\" ";
                            if(val['user_id']==res.mes.info.user_id){
                                str = str + "checked=\"checked\"";
                            }
                            str = str + " /><span class=\"font_pos\">";
                            str = str + val['group_name'];
                            str = str + "</span>";
                        });
                        $('#u_e_group').children().remove();
                        $('#u_e_group').append(str);
                    }
                }
            }
        });
    });

    $('.del_btn_personalajax').click(function(){
        if(confirm('您确定要删除吗？')){
            var id = $(this).val();
            var type = $(this).attr('attr-name');
            $.ajax({
                url: "man_user_ajax.php",
                type:'POST',
                complete :function(){}, //請求完回調的函數，無論成功與失敗都會調用，在success後
                dataType: 'json',
                data: {id:id,type:type},
                error: function() { alert('Ajax request 發生錯誤');},
                success: function(res) {
                    if (res.state == 'Error'){//没有数据
                        alert(res.mes);
                    }else{//有数据
                        if(res.auth_state == 0){
                            alert(res.mes);
                        }else{
                            if(res.type == 'user_del'){
                                if(res.mes.msg==1){
                                    alert('删除成功');
                                    window.location.reload();
                                }else{
                                    alert('删除失败');
                                };
                            }
                        }
                    }
                }
            });
        }
    });
    </script>

</body>
</html>