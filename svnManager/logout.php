<?php
include_once ('config/config.php');
include_once (INCLUDE_PATH."/common.php");
include_once ('php/postGreSql.php');

$res = new postGreSql($db);
//记录最后登录时间
$data = array(
    'login_time'  => date("Y-m-d h:i:s",time()),
);
$res
    ->table('tb_users')
    ->where("username='{$username}'")
    ->save($data);

//Session_Start();
session_unset();
session_destroy();

alert('null','login.php');