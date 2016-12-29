<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>金飞捷SVN版本管理</title>
	<link rel="stylesheet" type="text/css" href="<?php echo __CSS__; ?>common.css"/>
	<link rel="stylesheet" type="text/css" href="<?php echo __CSS__; ?>personal.css"/>
	<link rel="stylesheet" type="text/css" href="<?php echo __CSS__; ?>man_user.css"/>
	<link rel="stylesheet" type="text/css" href="<?php echo __CSS__; ?>department.css"/>
</head>
<body>
        <?php include_once (BOOT_PATH."/view/header.php");?>
		<div class="content left">
			<div class="con_title">
				<h2>功能及信息列表:</h2>
                <div class="search">
                    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>" method="get">
                        <input type="text" name="keyword" id="" placeholder="请输入部门名"/>
                        <input type="submit" value="搜索"/>
                    </form>
                </div>
			</div>		
			<div class='opreat left'>
				<button class='all_btn'>所有部门</button>
				<button class='lost_btn add_btn'>增加部门</button>


			</div>	
			<div class='user_info left'>
				<div class="view_bor">
					<div class="view_all department_view">
						<table border="1">
							<tr>
								<th>部门ID</th>
								<th>部门名</th>
								<th>直属部门</th>
								<th>部门成员</th>
								<th>删改操作</th>
							</tr>
							<?php foreach($deparData as $v){?>
							<tr>
								<td><?php echo $v['department_id'];?></td>
								<td><?php echo $v['department_name'];?></td>
								<td><?php if(isset($v['parent_name'])) echo $v['parent_name'];else echo '无';?></td>
								<td><?php if(!empty($v['users'])) echo $v['users'];else echo '无';?></td>
								<td><button class="edit_btn" value="<?php echo $v['department_id'];?>" attr-name="depart">编辑</button>
									<button class="del_btn" value="<?php echo $v['department_id'];?>" attr-name="depart">删除</button></td>
							</tr>
							<?php }?>
						</table>
						<?php echo $link;?>
					</div>					
					<form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>" name="add-depart" method="post" class='hide user_add edit_personal personal_pos'>
						<label for="dname">*部门名:</label>
						<input type="text" name="dname" id="dname" class="add-mus"/><br />
						<label for="up_dname">直属部门:</label>
						<select name="up_dname" id="up_dname">
							<option value="0">请选择</option>
							<?php foreach($deparDatas as $va){?>
							<option value="<?php echo $va['department_id']?>"><?php echo $va['department_name']?></option>
							<?php }?>
						</select><br />
						<input type="hidden" name="department" value="add"/>
						<label for="up_dname"></label>
						<input type="button" class="sub" attr-name="add-depart" value="添加"/>
					</form>
					<form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>" name="edit-depart" method="post" class='hide user_edit edit_personal personal_pos'>
						<input type="hidden" name="did" id="did"/>
						<label for="dname">*部门名:</label>
						<input type="text" name="e_dname" id="e-dname" class="edit-mus"/><br />
						<label for="up_dname">直属部门:</label>
						<select name="e_up_dname" id="e_up_dname" >
							<option value="0">请选择</option>
							<?php foreach($deparDatas as $va){?>
								<option value="<?php echo $va['department_id']?>"><?php echo $va['department_name']?></option>
							<?php }?>
						</select><br />
						<input type="hidden" name="department" value="edit"/>
						<label for="up_dname"></label>
						<input type="button" class="sub" attr-name="edit-depart" value="修改"/>
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
			$('.sub').click(function(){
				var type = $(this).attr('attr-name');
				var name = '';
				var id = '';
				var bool = true;
				if(type == 'edit-depart'){
					id = $('#did').val();
					name = $('#e-dname').val();
					$('.edit-mus').each(function(){
						//alert()
						var value = $(this).val();
						if(value.length <= 0){
							alert('请输入带星号（*）必填项！');
							bool = false;
							return false;
						}
					});
				}else{
					name = $('#dname').val();
					$('.add-mus').each(function(){
						var value = $(this).val();
						if(value.length <= 0){
							alert('请输入带星号（*）必填项！');
							bool = false;
							return false;
						}
					});
				}
				if(bool == false) return false;
				$.ajax({
					url: "nameCheck.php",
					type:'POST',
					complete :function(){}, //請求完回調的函數，無論成功與失敗都會調用，在success後
					dataType: 'json',
					data: {id:id,name:name,type:'depart'},
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
									if($(this).val() == mes.parent_id){
										$(this).siblings().prop('selected',false);
										$(this).prop('selected',true);
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