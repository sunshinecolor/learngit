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
	<?php include_once (BOOT_PATH.'/view/header.php');?>
		<div class="content left">
			<div class="con_title">
				<h2>功能及信息列表:</h2>	
				<div class="search">
					<form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>" method="get">
					<input type="text" name="keyword" id="" placeholder="请输入项目名或项目代码名"/>
					<input type="submit" value="搜索" />
					</form>
				</div>
			</div>		
			<div class='opreat left'>
				<button class='all_btn'>所有项目</button>
				<button class='lost_btn add_btn'>增加项目</button>
			</div>	
			<div class='user_info left'>
				<div class="view_bor">
					<div class="view_all">
						<table border="1">
							<tr>
								<th>项目ID</th>
								<th>项目名</th>
								<th>项目代码名</th>
								<th>删改操作</th>
							</tr>
							<?php foreach($project as $v){?>
							<tr>
								<td><?php echo $v['project_id'];?></td>
								<td><?php echo $v['project_name'];?></td>
								<td><?php echo $v['prj_code_name'];?></td>
								<td><button class="edit_btn" value="<?php echo $v['project_id'];?>" attr-name="project">编辑</button>
									<button class="del_btn" value="<?php echo $v['project_id'];?>" attr-name="project">删除</button></td>
							</tr>
							<?php }?>
						</table>
						<?php echo $link;?>
					</div>					
					<form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>" name="add-project" method="post" class='hide user_add edit_personal personal_pos'>
						<label for="pname">*项目名:</label>
						<input type="text" name="pname" id="pname" class="add-mus"/><br />
						<label for="pcode_name">*项目代码名称:</label>
						<input type="text" name="pcode_name" id="pcode_name" class="add-mus"/><br />
						<p style="margin:8px 0;color: #980c10">提示：项目代码名称只能输入英文字母、下划线、数字</p>
						<input type="hidden" value="add" name="type"/>
						<label for="pcode_name"></label>
						<input type="button" value="添加" class="sub" name="add-project"/>
					</form>
					<form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>" name="edit-project" method="post" class='hide user_edit edit_personal personal_pos'>
						<label for="pname">*项目名:</label>
						<input type="text" name="e_pname" id="e_pname" class="edit-mus"/><br />
						<label for="pcode_name">*项目代码名称:</label>
						<input type="text" name="e_pcode_name" id="e_pcode_name" class="edit-mus"/><br />
						<p style="margin:8px 0;color: #980c10">提示：项目代码名称只能输入英文字母、下划线、数字</p>
						<input type="hidden" value="" id="e_proid" name="proid"/>
						<input type="hidden" value="edit" name="type"/>
						<label for="pcode_name"></label>
						<input type="button" value="修改" class="sub" name="edit-project"/>
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
	<script type="text/javascript">
		$(function(){
			$('.sub').click(function(){
				var type = $(this).attr('name');
				var name = '';
				var id = '';
				var bool = true;
				var reg = /^\w+$/;
				if(type == 'edit-project'){
					id = $('#e_proid').val();
					name = $('#e_pname').val();
					code_name = $('#e_pcode_pname').val();
					$('.edit-mus').each(function(){
						var value = $(this).val();
						if(value.length <= 0){
							alert('请输入带星号（*）必填项！');
							bool = false;
							return false;
						}
					});
					if(bool == false) return false;
					if(!reg.test($("#e_pcode_name").val())){
						alert('项目代码名称只能输入数字、字母、下划线');
						bool = false;
						return false;
					}
				}else{
					name = $('#pname').val();
					code_name = $('#pcode_pname').val();
					$('.add-mus').each(function(){
						var value = $(this).val();
						if(value.length <= 0){
							alert('请输入带星号（*）必填项！');
							bool = false;
							return false;
						}
					});
					if(bool == false) return false;
					if(!reg.test($("#pcode_name").val())){
						alert('项目代码名称只能输入数字、字母、下划线');
						bool = false;
						return false;
					}
				}
				if(bool == false) return false;
				$.ajax({
					url: "nameCheck.php",
					type:'POST',
					complete :function(){}, //請求完回調的函數，無論成功與失敗都會調用，在success後
					dataType: 'json',
					data: {id:id,name:name,type:'project',code_name:code_name},
					error: function() { alert('Ajax request 发生错误'); },
					success: function(res) {
						if(res.state == 'Error'){
							alert(res.mes);
							bool = false;
							return false;
						}else{
							$('form[name='+type+']').submit();
						}
					}
				});
				if(bool == false) return false;
			});
			$('.del_btn').click(function(){
				if(confirm('您确定要删除吗？')){
					var id = $(this).val();
					var type = $(this).attr('attr-name');
					$.ajax({
						url: "departDel.php",
						type:'POST',
						complete :function(){}, //請求完回調的函數，無論成功與失敗都會調用，在success後
						dataType: 'json',
						data: {id:id,type:type},
						error: function() { alert('Ajax request 發生錯誤');},
						success: function(res) {
							alert(res.mes);
							if (res.state == 'Success'){//删除成功
								window.location.reload();
							}
						}
					});
				}
			});
			$('.edit_btn').click(function(){
				var id = $(this).val();
				var type = $(this).attr('attr-name');
				$.ajax({
					url: "departCheck.php",
					type:'POST',
					complete :function(){}, //請求完回調的函數，無論成功與失敗都會調用，在success後
					dataType: 'json',
					data: {id:id,type:type},
					error: function() { alert('Ajax request 發生錯誤');},
					success: function(res) {
						if (res.state == 'Error'){//没有数据
							alert(res.mes);
						}else{//有数据
							if(res.type == 'depart'){
								var mes = res.mes;
								$('#did').val(mes.department_id);
								$('#e-dname').val(mes.department_name);
								$('#111').val(mes.department_id);
								$('#e_up_dname option').each(function(){
									//alert($(this).val());
									if($(this).val() == mes.parent_id){
										$(this).siblings().attr('selected',false);
										$(this).attr('selected',true);
									}
								});
							}else if(res.type == 'project'){
								var mes = res.mes;
								$('#e_pname').val(mes.project_name);
								$('#e_pcode_name').val(mes.prj_code_name);
								$('#e_proid').val(mes.project_id);
							}
							$('.view_all').hide();
							$('.view_bor form').hide();
							$('.user_edit').show();
						}
					}
				});
			});
		})
	</script>
</body>
</html>