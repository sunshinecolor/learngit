<?php
include_once ('config/config.php');
include_once (INCLUDE_PATH."/common.php");
include_once ('php/postGreSql.php');

$res = new postGreSql($db);

//所有部门信息查询
$allinfo = $res
    ->table('tb_users as u')
    ->field('user_id,username,realname,sexes,phone,email,department_name,de.department_id,create_time,login_count,login_time')
    ->join('tb_departments as de on u.department_id=de.department_id','left')
    ->order('de.department_name asc')
    ->where("username='{$username}'")
    ->select();

//personal基本信息查询
foreach($allinfo as $k => $v){
    if(!empty($v['user_id'])){
        $personalinfo=$v;
        break;
    }
}
$user_id = isset($personalinfo['user_id'])?$personalinfo['user_id']:'';

//所有组查询
$allgroup = $res
    ->table('tb_users as u')
    ->field('us.group_name,u.user_id,us.group_id')
    ->join('tb_users_ugrps up on u.user_id=up.user_id')
    ->join('tb_usergroups us on up.group_id=us.group_id','right')
    ->order('us.group_name asc')
    ->where("u.username='{$username}'")
    ->andselect();

//personal所在组查询
foreach($allgroup as $k => $v){
    if(!empty($v['user_id'])){
        $personalgroup[$k]=$v;
    }
}
if(isset($personalgroup) && $personalinfo['sexes']==2){
    $sexes = '女';
}else if(isset($personalgroup) && $personalinfo['sexes']==1){
    $sexes = '男';
}else{
    $sexes = '保密';
}

//权限查询
$check_auth = isset($_SESSION['check_auth'])?$_SESSION['check_auth']:'';
$user_mes = '无权限';
$proj_mes = '无权限';
$auth_mes = '无权限';
foreach($check_auth as $k => $v){
    if($check_auth['user'] == 1){
        $user_mes = '只读';
    }
    if($check_auth['user'] == 3){
        $user_mes = '读写';
    }
    if($check_auth['user'] == 7){
        $user_mes = '读写删';
    }
    if($check_auth['proj'] == 1){
        $proj_mes = '只读';
    }
    if($check_auth['proj'] == 3){
        $proj_mes = '读写';
    }
    if($check_auth['proj'] == 7){
        $proj_mes = '读写删';
    }
    if($check_auth['auth'] == 1){
        $auth_mes = '只读';
    }
    if($check_auth['auth'] == 3){
        $auth_mes = '读写';
    }
    if($check_auth['auth'] == 7){
        $auth_mes = '读写删';
    }
}


$hid = isset($_POST['hid'])?$_POST['hid']:'';

//修改个人基本资料
if($hid == 'basic'){
    $rname = isset($_POST['real_name'])?$_POST['real_name']:'';
    $sex   = isset($_POST['sex'])?$_POST['sex']:'';
    $phone = isset($_POST['phone'])?$_POST['phone']:'';
    $email = isset($_POST['email'])?$_POST['email']:'';
    $department_id = isset($_POST['department_id'])?$_POST['department_id']:'';
    $user = isset($_POST['user'])?$_POST['user']:'';

    if($rname==''){
        alert('名字不能为空');
        exit;
    }
    if($sex==''){
        alert('性别不能为空');
        exit;
    }
    if($phone==''){
        alert('电话号码不能为空');
        exit;
    }
    if($email==''){
        alert('邮箱不能为空');
        exit;
    }
    $data = array(
        'realname' => $rname,
        'sexes'    => $sex,
        'phone'    => $phone,
        'email'    => $email
    );
    $return = $res->table('tb_users')->where("username='{$username}'")->save($data);
    if($return){
        alert('信息修改成功','personal.php');
        exit;
    }else{
        alert('信息修改失败，请重试或者联系管理员');
        exit;
    }
}

//修改个人密码
if($hid == 'pw'){
    $old_pass   = isset($_POST['old_pass'])?$_POST['old_pass']:'';
    $new_pass   = isset($_POST['new_pass'])?$_POST['new_pass']:'';
    $check_pass = isset($_POST['check_pass'])?$_POST['check_pass']:'';
    if($old_pass == ''){
        alert('旧密码不能为空');
        exit;
    }
    if($new_pass == ''){
        alert('新密码不能为空');
        exit;
    }
    if($check_pass == ''){
        alert('确认密码不能为空');
        exit;
    }
    check_pw($new_pass);
    if($new_pass != $check_pass){
        alert('两次输入密码不一致，请重输');
        exit;
    }
    $pw = $res
        ->table('tb_users')
        ->field('password')
        ->where("username='{$username}'")
        ->find();
    if($pw['password'] != base64_encode(sha1($old_pass,true))){
        alert('旧密码输入错误，请重输');
        exit;
    }
    $data = array(
        'password' => base64_encode(sha1($new_pass,true))
    );
    $return = $res
        ->table('tb_users')
        ->where("username='{$username}'")
        ->save($data);
    if($return){
        alert('密码修改成功','personal.php');
        exit;
    }else{
        alert('密码修改失败，请重试或者联系管理员');
        exit;
    }

}


include_once ('view/personal.php');