<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>金飞捷SVN版本管理</title>
	<link rel="stylesheet" type="text/css" href="<?php echo __CSS__;?>common.css"/>
	<link rel="stylesheet" type="text/css" href="<?php echo __CSS__;?>personal.css"/>
	<link rel="stylesheet" type="text/css" href="<?php echo __CSS__;?>man_user.css"/>
	<link rel="stylesheet" href="<?php echo __CSS__;?>man_pro_authority.css">
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
						<a class="lost" href="man_authority.php">功能权限</a>
						<a class="active" href="man_pro_authority.php">项目权限</a>
						<a class="lost generate_auth" href="javascript:;">生成授权文件</a>
					</div>
				</li>
			</ul>
		</div>
		<div class="content left">
			<div class="con_title">
				<h2>项目权限列表:</h2>
			</div>
			<!-- <div class='opreat left auth_opreat'>
				<button class='all_btn'>已分权限</button>
				<button class='lost_btn add_btn'>增加权限</button>
				<button class='lost_btn edit_btn'>编辑权限</button>
			</div>	 -->
			<div class='user_info left'>
				<div class="view_bor">
					<div class="view_all">
						<form action="" method="post" class="table_mes">
							<label for="select_list">选择用户/用户组:</label>
							<select name="select_list" id="select_list" class="select_list choice">
								<option value="">--请选择--</option>
								<option value="1">用户</option>
								<option value="0">用户组</option>
							</select>
							<select name="select_choice" class="select_choice choice">
								<option value="">--请选择--</option>
							</select>
							<select name="select_user" id="select_1" class="select_user hide mes_id">
								<option value="0">--请选择--</option>
								<?php foreach($users as $v){?>
								<option value="<?php echo $v['user_id']?>"><?php echo $v['username']?></option>
								<?php }?>
							</select>
							<select name="select_urg" id="select_0" class="select_urg hide mes_id">
								<option value="0">--请选择--</option>
								<?php foreach($groups as $v){?>
									<option value="<?php echo $v['group_id']?>"><?php echo $v['group_name']?></option>
								<?php }?>
							</select>
							<label for="select_pro">选择项目:</label>
							<select name="select_pro" id="select_pro" class="projects">
								<option value="0">--请选择--</option>
								<?php foreach($projects as $v){?>
									<option value="<?php echo $v['project_id']?>" pro_name="<?php echo $v['prj_code_name']?>" class="pro_mes" style="display: none">
                                        <?php echo $v['project_name']?>
                                    </option>
								<?php }?>
							</select>
						</form>						
						<div class="none_data none_mes">请选择用户或用户组和对应项目</div>
						<div class="none_data personal" style="display: none;">没有此项目相关权限</div>
					</div>					
				</div>
			</div>
			<div class="man_proa_op left" style="display: none;">
				<form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>" method="post" class="add_auth">
					<input type="text" name="path" class="must" placeholder="目录路径">
                    <p>提示：'/' 表示当前目录</p>
                    <p>'/svn' 表示当前文件夹下面的svn文件夹</p>
					<div class="proa_radio">
                        <input type="hidden" value="" name="mes-data" class="all_mes">
                        <input type="hidden" value="" name="project_name" class="project_name">
						<input type="radio" name="pro_auth" value="1" checked="checked"/><span class="font_pos">只读</span>
						<input type="radio" name="pro_auth" value="3"/><span class="font_pos">读写</span>
					</div>					
					<button>添加</button>
				</form>
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
            $('.add_auth').submit(function(){
                if($('.must').val() == ''){
                    alert('请输入目录路径');
                    return false;
                };
            });
			$('.generate_auth').click(function(){
				if(confirm('您确定要生成新的授权文件吗？')){
					window.location.href="license_file.php";
				}
			});
			$('#select_list').change(function(){
				$('.none_mes').css('display','block');
				$('.personal').css('display','none');
				$('.man_proa_op').css('display','none');
				$('.pro_mes').css('display','none');
				$('.data').remove();
				$('.mes_id option').prop('selected',false);
				$('.projects option').prop('selected',false);
				if($('#select_list option:selected').text()=='--请选择--'){
					$('.select_choice').show();
					$('#select_1').hide();
					$('#select_0').hide();
				}else if($('#select_list option:selected').text()=='用户'){
					$('#select_0').hide();
					$('.select_choice').hide();
					$('#select_1').show();
				}else{
					$('.select_choice').hide();
					$('#select_1').hide();
					$('#select_0').show();
				}
			});
			$('.mes_id').change(function(){
				$('.none_mes').css('display','block');
				$('.personal').css('display','none');
				$('.man_proa_op').css('display','none');
				$('.projects option').prop('selected',false);
				$('.data').remove();
				if($(this).val() != '0'){
					$('.pro_mes').css('display','block');
				}else{
					$('.pro_mes').css('display','none');
				}
			});
			$('.projects').change(function(){
				$('.data').remove();
				var type = $('#select_list').val();
				var id = $('#select_'+type+' option:selected').val();
				var pro = $(this).val();
                var all_mes = type+','+id+','+pro;
                var pro_name = $('option:selected',this).attr('pro_name');
				if(id != '0' && type != '' && pro != '0'){
					$('.man_proa_op').css('display','block');
                    $('.all_mes').val(all_mes);
                    $('.project_name').val(pro_name);
					$.ajax({
						url: "auth_list.php",
						type:'POST',
						complete :function(){}, //請求完回調的函數，無論成功與失敗都會調用，在success後
						dataType: 'json',
						data: {id:id,type:type,pro:pro},
						error: function() { alert('Ajax request 發生錯誤');},
						success: function(res) {
							if(res.state == 'Success'){
								$('.none_mes').css('display','none');
								$('.personal').css('display','none');
								var mes = res.mes;
								var str = "<table border='1' class='data'>"+
									"<tr>"+
									"<th>项目目录路径</th>"+
									"<th>权限</th>"+
									"<th>操作</th>"+
									"</tr>" ;
								if(res.type == '1'){
									$.each(mes,function(k,val){
										var auth = '';
										if(val.authorities == '1') auth = '只读(个人权限)';
										else auth = '读写(个人权限)';
										var button = "<button class='edit_proa_btn' value='"+id+","+type+","+val.pa_id+"'>删除</button>";
										str+='<tr>' +
											'<td>'+val.prj_path+'</td>' +
											'<td>'+auth+'</td>' +
											"<td>"+button+"</td>"+
											"</tr>"
									});
									$.each(res.group,function(k,val){
										var auth = '';
										if(val.authorities == '1') auth = '只读(用户组权限)';
										else auth = '读写(用户组权限)';
										var button = "";
										str+='<tr>' +
											'<td>'+val.prj_path+'</td>' +
											'<td>'+auth+'</td>' +
											"<td>"+button+"</td>"+
											"</tr>"
									});
								}else{
									$.each(mes,function(k,val){
										var auth = '';
										if(val.authorities == '1') auth = '只读(用户组权限)';
										else auth = '读写(用户组权限)';
										var button = "<button class='edit_proa_btn' value='"+id+","+type+","+val.pa_id+"'>删除</button>";
										str+='<tr>' +
											'<td>'+val.prj_path+'</td>' +
											'<td>'+auth+'</td>' +
											"<td>"+button+"</td>"+
											"</tr>"
									});
								}
								str+='</table>';
								$(str).insertAfter('.table_mes');
							}else{
								$('.none_mes').css('display','none');
								$('.personal').css('display','display');
							}
                            $('.edit_proa_btn').click(function(){
								if(confirm('您确定要删除吗？')){
									var pa_id = $(this).val();
									$.ajax({
										url: "auth_list.php",
										type:'POST',
										complete :function(){}, //請求完回調的函數，無論成功與失敗都會調用，在success後
										dataType: 'json',
										data: {id:pa_id,type:'del'},
										error: function() { alert('Ajax request 發生錯誤');},
										success: function(res) {
											alert(res.mes);
											if(res.state == 'Success'){
												window.location.reload();
											}
										}
									});
								}
                            });
						}
					});
				}else{
					$('.man_proa_op').css('display','none');
				}
			});
		});
	</script>
</body>
</html>