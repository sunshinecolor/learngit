<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>金飞捷SVN版本管理</title>
	<link rel="stylesheet" type="text/css" href="<?php echo __CSS__; ?>common.css"/>
</head>
<body>
<?php include_once ('view/header.php') ?>
		<div class="content left">
			<div class="con_title">
				<h2>近期更新项目:</h2>
				<div class="search">
					<input type="text" name="" id="" placeholder="请输入关键字"/>
					<input type="submit" value="搜索" />
				</div>
			</div>
			<ul class="wpro_view">
				<?php foreach($pro_info as $k => $v){ ?>
				<li>
					<a class='black' href="">
						<h3><?php echo $v['project_name'].'（项目编号：'.$v['prj_code_name'].'）' ?></h3>
<!--						<h4>项目路径：</h4><span>/mcc3.0</span><br />-->
<!--						<h4>最近更新：</h4><span>/mcc3.0/design/需求分析配图-v1.0.eddx</span><br />-->
<!--						<h4>更新时间：</h4><span>2016-7-19</span>,&nbsp;-->
<!--						<h4>更新人：</h4><span>温筠庭</span>-->
						<img src="<?php echo __IMG__; ?>wpro.jpg"/>
					</a>					
				</li>
				<?php } ?>
			</ul>
            <?php echo $link ?>
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