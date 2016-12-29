<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>金飞捷SVN版本管理</title>
	<link rel="stylesheet" type="text/css" href="<?php echo __CSS__;?>common.css"/>
	<link rel="stylesheet" type="text/css" href="<?php echo __CSS__;?>personal.css"/>
	<link rel="stylesheet" type="text/css" href="<?php echo __CSS__;?>man_user.css"/>
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
			<li class="lost"><a class="lost" href="man_user.php">用户管理</a></li>
			<li><a class="lost" href="man_department.php">部门管理</a></li>
			<li><a class="lost" href="man_pro.php">项目管理</a></li>
			<li class="lnav_man_user last_bott">
				<a class="active" href="man_authority.php">授权管理</a>
				<div class='manuser_list'>
					<a class="active" href="man_authority.php">功能权限</a>
					<a class="lost" href="man_pro_authority.php">项目权限</a>
					<a class="lost generate_auth" href="javascript:;">生成授权文件</a>
				</div>
			</li>
		</ul>
	</div>
	<div class="content left">
		<div class="con_title">
			<h2>功能及信息列表:</h2>
			<div class="search">
				<form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>" method="get">
					<input type="text" name="keyword" id="" placeholder="请输入用户组名"/>
					<input type="submit" value="搜索" />
				</form>
			</div>
		</div>
        <div class='opreat left auth_opreat'>
			<button class='lost_btn all_btn proa_all_btn'>个人操作权限</button>
			<button class='all_btn'>用户组操作权限</button>
        </div>
		<div class='user_info left' style="margin:20px auto;">
			<div class="view_bor view_usera_bor">
				<div class="view_all">
					<table border="1" class="department-mes">
						<tr>
							<th>用户组名</th>
							<th>用户代码名</th>
							<th>功能权限</th>
							<th>编辑操作</th>
						</tr>
						<?php foreach($groups as $k=>$v){?>
							<tr>
							<td class="username"><?php echo $v['grp_code_name'];?></td>
								<td class="username"><?php echo $v['group_name'];?></td>
								<td>
									<?php if(isset($v['auth'])){
										$str = '';
										foreach($v['auth'] as $va){
											switch($va['module_id']){
												case 1:
													$str.='用户、部门';
													break;
												case 2:
													$str.='项目';
													break;
												default:
													$str.='授权';
											}
											switch($va['authorities']){
												case 1:
													$str.='（只读）';
													break;
												case 3:
													$str.='（读写）';
													break;
												default:
													$str.='（读写删）';
													break;
											}
											$str.='</br>';
										}
										echo $str;
									}else{
										echo '无';
									}?>
								</td>
								<td><button class="edit_btn" value="<?php echo $v['group_id'];?>" attr-name="group" user-name="<?php echo $v['group_name']?>" edit-id="<?php echo $v['group_id']?>">编辑</button></td>
							</tr>
						<?php }?>
					</table>
                    <?php echo $link;?>

				</div>
				<form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>" method="post" class='hide user_edit edit_personal personal_pos'>
					<label for="aname">用户组名:</label>
					<input type="text" name="aname" id="aname" disabled="disabled"/><br />
					<input type="hidden" name="aname-id" id="aname-id"/><br />
					<label for="pcode_name">管理权限:</label>
					<div class='user_authority'>
						<p>
                            <input type="hidden" class="facility-mes" value="1"/><span class="font_pos">用户、部门管理</span>
                            <input type="checkbox" name="facility[]" value="1,1" class="facility-details"/><span class="font_pos">只读</span>
                            <input type="checkbox" name="facility[]" value="1,3" class="facility-details"/><span class="font_pos">读写</span>
                            <input type="checkbox" name="facility[]" value="1,7" class="facility-details"/><span class="font_pos">读写删</span>
                        </p>
                        <p>
                            <input type="hidden" class="facility-mes" value="2"/><span class="font_pos">项目管理</span>
                            <input type="checkbox" name="facility[]" value="2,1" class="facility-details"/><span class="font_pos">只读</span>
                            <input type="checkbox" name="facility[]" value="2,3" class="facility-details"/><span class="font_pos">读写</span>
                            <input type="checkbox" name="facility[]" value="2,7" class="facility-details"/><span class="font_pos">读写删</span>
                        </p>
                        <p><input type="hidden" class="facility-mes" value="3"/><span class="font_pos">授权管理</span>
                            <input type="checkbox" name="facility[]" value="3,1" class="facility-details"/><span class="font_pos">只读</span>
                            <input type="checkbox" name="facility[]" value="3,3" class="facility-details"/><span class="font_pos">读写</span>
							<input type="checkbox" name="facility[]" value="3,7" class="facility-details"/><span class="font_pos">读写删</span>
                        </p>
					</div><br />
                    <input type="hidden" name="edit-type" value="module"/>
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
<script src="<?php echo __JS__;?>jquery-1.11.0.js" type="text/javascript" charset="utf-8"></script>
<script src="<?php echo __JS__;?>common.js" type="text/javascript" charset="utf-8"></script>
<script type="text/javascript">
	$(function(){
		$('.lost_btn').click(function(){
			window.location.href='man_authority.php';
		});
        $('.generate_auth').click(function(){
            if(confirm('您确定要生成新的授权文件吗？')){
                window.location.href="license_file.php";
            }
        });


        $("input[type='checkbox']").click(function(){
            $(this).siblings().prop('checked',false);
        });
        $('.edit_btn').click(function(){
            var id = $(this).val();
            var type = $(this).attr('attr-name');
            var name = $(this).attr('user-name');
            if (type == 'user' || type=='group'){
                if (type == 'user'){
                    $('#aname-id').val('1,'+id);
                }else{
                    $('#aname-id').val('0,'+id);
                }
                $('#aname').val(name);
            }
            $("input[type='checkbox']").prop('checked',false);
            $.ajax({
                url: "authCheck.php",
                type:'POST',
                complete :function(){}, //請求完回調的函數，無論成功與失敗都會調用，在success後
                dataType: 'json',
                data: {id:id,type:type},
                error: function() { alert('Ajax request 發生錯誤');},
                success: function(res) {
                    if (res.state == 'Success'){//有数据
                        var mes = res.mes;
						if (res.type == 'user' || res.type=='group'){
							$.each(mes,function(index,val){
								$('.facility-mes').each(function(){
									$(this).prop('checked',false);
									if($(this).val() == val.module_id){
										//$(this).prop('checked',true);
										var module_id = $(this).val();
										$(this).siblings('.facility-details').each(function(){
											//$(this).prop('checked',false);
											if($(this).val() == module_id+","+val.authorities){
												$(this).prop('checked',true);
											}
										});
									}
								});
							});
						}
                    }
                }
            });
        });
	});



</script>
</body>
</html>