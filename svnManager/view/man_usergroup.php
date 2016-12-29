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
						<a class="lost" href="man_user.php">用户信息</a>
						<a class="active" href="man_usergroup.php">用户组信息</a>
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
                    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>" method="get">
                        <input type="text" name="like" id="" placeholder="请输入组名、组代码名关键字"/>
                        <input type="submit" value="搜索" />
                    </form>
                </div>
			</div>
			<div class='opreat left'>
				<button class='all_btn'>所有用户组</button>
				<button class='lost_btn add_btn add_btn_groupajax' attr-name="group_add" >增加用户组</button>
			</div>	
			<div class='user_info left'>
				<div class="view_bor">
					<div class="view_all">
						<table border="1">
							<tr>
								<th>用户组ID</th>
								<th>用户组名</th>
								<th>用户组代码名</th>
								<th>用户组成员</th>
								<th>功能权限</th>
								<th>删改操作</th>
							</tr>
                            <?php foreach($info as $k => $v){ ?>
                            <tr>
								<td><?php echo $v['info']['group_id'] ?></td>
								<td><?php echo $v['info']['group_name'] ?></td>
								<td><?php echo $v['info']['grp_code_name'] ?></td>
								<td><?php foreach($v['person'] as $key => $value){ echo $value['realname'].' / ';} ?></td>
								<td><?php if(isset($v['auth'])){echo '用户、部门('.$v['auth']['user_mes'].')<br /> 项目('.$v['auth']['proj_mes'].')<br /> 授权('.$v['auth']['auth_mes'].')'; }else{ echo '无权限';} ?></td>
								<td><button class="edit_btn edit_btn_groupajax" value="<?php echo $v['info']['group_id'] ?>" attr-name="group_edit">编辑</button>
                                    <button class="del_btn del_btn_groupajax" value="<?php echo $v['info']['group_id'] ?>" attr-name="group_del">删除</button>
                                </td>
							</tr>
                            <?php } ?>
                        </table>
                        <?php echo $link ?>
					</div>					
					<form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>" method="post" class='hide user_add edit_personal personal_pos'>
						<label for="uname">用户组名:</label>
						<input type="text" name="uname" id="uname" /><br />
						<label for="uname">用户组代码名:</label>
						<input type="text" name="codename" id="codename" /><br />
						<label for="usergrop_team">用户组成员:</label>
						<div class='user_group' id="g_a_group">
						</div>
                        <br />
                        <input type="hidden" name="hid" value="g_add">
						<input type="submit" value="添加"value="<?php echo $v['info']['group_id'] ?>" attr-name="group_add" />
					</form>
					<form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>" method="post" class='hide user_edit edit_personal personal_pos'>
						<label for="uid">用户组ID:</label>
						<input type="text" disabled="disabled" id="g_e_id"/><br />
						<input type="hidden" name="uid" id="g_e_id_h"/>
						<label for="uname">用户组名:</label>
						<input type="text" name="uname" id="g_e_name" /><br />
						<label for="uname">用户组代码名:</label>
						<input type="text" name="codename" id="g_e_codename"/><br />
						<label for="usergrop_team">用户组成员:</label>
						<div class='user_group' id="g_e_group">
						</div>
                        <br />
                        <input type="hidden" name="hid" value="g_edit">
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
    $('.add_btn_groupajax').click(function(){
        var type = $(this).attr('attr-name');
        $.ajax({
            url: "man_usergroup_ajax.php",
            type:'POST',
            complete :function(){}, //請求完回調的函數，無論成功與失敗都會調用，在success後
            dataType: 'json',
            data: {type:type},
            error: function() { alert('Ajax request 發生錯誤');},
            success: function(res) {
                if (res.state == 'Error'){//没有数据
                    alert(res.mes);
                }else{//有数据
                    if(res.type == 'group_add'){
                        var personalgroup = res.mes.personalgroup;
                        var	str = '';
                        console.log(personalgroup);
                        $.each(personalgroup, function(key, val) {
                            str = str + "<input type=\"checkbox\" name=\"user[]\" id=\"\" value=\"";
                            str = str + val['user_id'];
                            str = str + "\" /><span class=\"font_pos\">";
                            str = str + val['realname'];
                            str = str + "</span>";
                        });
                        $('#g_a_group').children().remove();
                        $('#g_a_group').append(str);
                    }
                }
            }
        });
    });

    $('.edit_btn_groupajax').click(function(){
        var id = $(this).val();
        var type = $(this).attr('attr-name');
        $.ajax({
            url: "man_usergroup_ajax.php",
            type:'POST',
            complete :function(){}, //請求完回調的函數，無論成功與失敗都會調用，在success後
            dataType: 'json',
            data: {id:id,type:type},
            error: function() { alert('Ajax request 發生錯誤');},
            success: function(res) {
                if (res.state == 'Error'){//没有数据
                    alert(res.mes);
                }else{//有数据
                    if(res.type == 'group_edit'){
                        $('#g_e_id').val(res.mes.info.group_id);
                        $('#g_e_id_h').val(res.mes.info.group_id);
                        $('#g_e_name').val(res.mes.info.group_name);
                        $('#g_e_codename').val(res.mes.info.grp_code_name);
                        var group = res.mes.personalgroup;
                        var	str = '';
                        $.each(group, function(key, val) {
                            str = str + "<input type=\"checkbox\" name=\"user[]\"";
                            if(val['group_id']==res.mes.info.group_id){
                                str = str + "checked=\"checked\"";
                            }
                            str = str + " id=\"\" value=\"";
                            str = str + val['user_id'];
                            str = str + "\" />";
                            str = str + "<span class=\"font_pos\">";
                            str = str + val['realname'];
                            str = str + "</span>";
                        });
                        $('#g_e_group').children().remove();
                        $('#g_e_group').append(str);
                    }
                }
            }
        });
    });

    $('.del_btn_groupajax').click(function(){
        if(confirm('您确定要删除吗？')){
            var id = $(this).val();
            var type = $(this).attr('attr-name');
            $.ajax({
                url: "man_usergroup_ajax.php",
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
                            if(res.type == 'group_del'){
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