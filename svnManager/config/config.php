<?php
/**
 * Created by PhpStorm.
 * User: pengyingpeng
 * Date: 2016/7/22 0022
 * Time: 9:24
 */
// 设置编码
session_start();
header('content-type:text/html;charset=utf-8');
// 设置时区
date_default_timezone_set('PRC');
// 错误提示设置
error_reporting(E_ALL);

$config = array(
    'host'     => '192.168.1.108',
    'dbname'   => 'superpeng',
    'user'     => 'superpeng',
    'password' => '1234567a',
    'port'     => '5432'
);
$db = @pg_connect("host=".$config['host']." dbname=".$config['dbname']." user=".$config['user']." password=".$config['password']." port=".$config['port']);


// 本地路径
define('BOOT_PATH',dirname(dirname(__FILE__)));
define('INCLUDE_PATH',BOOT_PATH.'/common');
//define('INCLUDE_PATH_CLASS',BOOT_PATH.'/include/class/');
//define('DATA_PATH',BOOT_PATH.'/data/');
define('UPLOADS_PATH',BOOT_PATH.'/uploads/');


// WEB路径
define('WEB_PATH','http://'.$_SERVER['HTTP_HOST'].'/svnManager/');
define('__IMG__',WEB_PATH.'public/images/');
define('__CSS__',WEB_PATH.'public/css/');
define('__JS__',WEB_PATH.'public/js/');
$username = isset($_SESSION['user_name'])?$_SESSION['user_name']:'';

//SVN相关
define('SVNADMIN','sudo svnadmin create /svn/repos/');
define('REPOS_PATH','/svn/repos/');
