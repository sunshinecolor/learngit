<?php
include_once ('config/config.php');
include_once (INCLUDE_PATH."/common.php");
include_once ('php/postGreSql.php');
include_once ('php/Page.class.php');

$res = new postGreSql($db);

//分页
$count = $res
    ->table('tb_projects')
    ->count();
$num = isset($_GET['page'])?$_GET['page']:1;
$limit = 5;
$page = new page($count,$limit);
$link = $page->pageinfo($num);

$start = ($num-1)*$limit;
$pro_info = $res
    ->table('tb_projects')
    ->order('project_name asc')
    ->limit($start,$limit)
    ->select();

include_once ('view/index.php');




