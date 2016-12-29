<?php
include_once ('config/config.php');
include_once (INCLUDE_PATH."/common.php");
include_once ('php/postGreSql.php');
include_once ('php/Page.class.php');

$res = new postGreSql($db);

check_auth('user',1);
//分页
$count = $res
    ->table('tb_users as u')
    ->count('user_id');
$num = isset($_GET['page'])?$_GET['page']:1;
$limit = 5;
$page = new page($count,$limit);
$link = $page->pageinfo($num);

//所有用户基本资料查询
$start = ($num-1)*$limit;
$res->field('user_id,username,realname,department_name,active')
    ->join('tb_departments as de on u.department_id=de.department_id','left')
    ->order('user_id asc')
    ->limit($start,$limit);
//添加模糊查询条件
if(isset($_GET['like']) && $_GET['like']!=''){
    $like = addslashes($_GET['like']);
    $personalinfo = $res
        ->table('tb_users as u')
        ->where("username like '%{$like}%' or realname like '%{$like}%' or department_name like '%{$like}%'")
        ->select();
}else{
    $personalinfo = $res
        ->table('tb_users as u')
        ->andselect();
}

//所有用户所在组查询
$info = array();
if(!empty($personalinfo)){
    foreach($personalinfo as $k => $v){
        $info[$k]['info']=$v;
        $personalgroup = $res
            ->table('tb_users as u')
            ->field('us.group_name,us.group_id')
            ->join('tb_users_ugrps up on u.user_id=up.user_id')
            ->join('tb_usergroups us on up.group_id=us.group_id')
            ->where("u.username='{$v['username']}'")
            ->select();
        $info[$k]['group']=$personalgroup;

        //功能权限查询
        $info[$k]['auth']['user_num'] = 0;
        $info[$k]['auth']['proj_num'] = 0;
        $info[$k]['auth']['auth_num'] = 0;
        $personalauth = $res
            ->table('tb_user_authority')
            ->field('module_id,authorities')
            ->order('authorities asc')
            ->where("object_id='{$v['user_id']}' and object_type=1")
            ->select();
        //module_id=1/用户功能权限查询(不含所在组权限)
        if(!empty($personalauth)) {
            foreach ($personalauth as $key => $value) {
                if ($value['module_id'] == 1) {
                    if ($info[$k]['auth']['user_num'] < $value['authorities']) {
                        $info[$k]['auth']['user_num'] = $value['authorities'];
                    }
                }
                if ($value['module_id'] == 2) {
                    if ($info[$k]['auth']['proj_num'] < $value['authorities']) {
                        $info[$k]['auth']['proj_num'] = $value['authorities'];
                    }
                }
                if ($value['module_id'] == 3) {
                    if ($info[$k]['auth']['auth_num'] < $value['authorities']) {
                        $info[$k]['auth']['auth_num'] = $value['authorities'];
                    }
                }
            }
        }
        //module_id=0/用户继承所在用户组的功能权限查询
        if(!empty($info[$k]['group'])){
            foreach($info[$k]['group'] as $key => $value){
                $object_id = $value['group_id'];
                $group_auth = $res
                    ->table('tb_user_authority')
                    ->field('module_id,authorities')
                    ->where("object_id='{$object_id}' and object_type=0")
                    ->select();
                if(!empty($group_auth)){
                    foreach($group_auth as $key => $value){
                        if($value['module_id']==1){
                            if($info[$k]['auth']['user_num'] < $value['authorities']){
                                $info[$k]['auth']['user_num'] = $value['authorities'];
                            }
                        }
                        if($value['module_id']==2){
                            if($info[$k]['auth']['proj_num'] < $value['authorities']){
                                $info[$k]['auth']['proj_num'] = $value['authorities'];
                            }
                        }
                        if($value['module_id']==3){
                            if($info[$k]['auth']['auth_num'] < $value['authorities']){
                                $info[$k]['auth']['auth_num'] = $value['authorities'];
                            }
                        }
                    }
                }
            }
        }

        $info[$k]['auth']['user_mes'] = '无权限';
        $info[$k]['auth']['proj_mes'] = '无权限';
        $info[$k]['auth']['auth_mes'] = '无权限';
        if($info[$k]['auth']['user_num'] == 1){
            $info[$k]['auth']['user_mes'] = '只读';
        }
        if($info[$k]['auth']['user_num'] == 3){
            $info[$k]['auth']['user_mes'] = '读写';
        }
        if($info[$k]['auth']['user_num'] == 7){
            $info[$k]['auth']['user_mes'] = '读写删';
        }
        if($info[$k]['auth']['proj_num'] == 1){
            $info[$k]['auth']['proj_mes'] = '只读';
        }
        if($info[$k]['auth']['proj_num'] == 3){
            $info[$k]['auth']['proj_mes'] = '读写';
        }
        if($info[$k]['auth']['proj_num'] == 7){
            $info[$k]['auth']['proj_mes'] = '读写删';
        }
        if($info[$k]['auth']['auth_num'] == 1){
            $info[$k]['auth']['auth_mes'] = '只读';
        }
        if($info[$k]['auth']['auth_num'] == 3){
            $info[$k]['auth']['auth_mes'] = '读写';
        }
        if($info[$k]['auth']['auth_num'] == 7){
            $info[$k]['auth']['auth_mes'] = '读写删';
        }

    }

}


$hid = isset($_POST['hid'])?$_POST['hid']:'';
//增加用户
if($hid=='u_add'){
    check_auth('user',3);
    $uname = isset($_POST['uname'])?addslashes($_POST['uname']):'';
    $real_name = isset($_POST['real_name'])?addslashes($_POST['real_name']):'';
    $user_state = isset($_POST['user_state'])?addslashes($_POST['user_state']):'';
    $user_departments = isset($_POST['user_departments'])?addslashes($_POST['user_departments']):'';
    $user_group = isset($_POST['user_group'])?$_POST['user_group']:'';
    $new_pass = isset($_POST['new_pass'])?addslashes($_POST['new_pass']):'';
    $check_pass = isset($_POST['check_pass'])?addslashes($_POST['check_pass']):'';

    //验证用户名
    if($uname == ''){
        alert('姓名不能为空');
        exit;
    }
    check_un($uname);
    $check_username = $res
        ->table('tb_users')
        ->where("username='{$uname}'")
        ->find();
    if($check_username){
        alert('用户名已存在，请换用户名注册');
        exit;
    }
    if($real_name == ''){
        alert('姓名不能为空');
        exit;
    }
    //验证密码
    if($new_pass == ''){
        alert('密码不能为空');
        exit;
    }
    check_pw($new_pass);
    if($new_pass!=$check_pass){
        $data['password']=base64_encode(sha1($new_pass,true));
        alert('两次输入密码不一致，请重输');
        exit;
    }
    $data = array(
        'username'=>$uname,
        'realname'=>$real_name,
        'create_time'=>date("Y-m-d h:i:s",time()),
        'department_id'=>$user_departments,
        'active'=>$user_state,
        'password'=>base64_encode(sha1($new_pass,true))
    );
    $mes1 = $res
        ->table('tb_users')
        ->add($data);
    $check_user_id = $res
        ->table('tb_users')
        ->field('user_id')
        ->where("username='{$uname}'")
        ->find();
    $uid = $check_user_id['user_id'];
    if ($mes1) {
            $group_data['user_id'] = $uid;
            if (!empty($user_group)) {
                foreach ($user_group as $k => $v) {
                    $group_data['group_id'] = $v;
                    $mes[$k] = $res
                        ->table('tb_users_ugrps')
                        ->where("user_id='{$uid}'")
                        ->add($group_data);
                }
            } else {
                $mes = 1;
            }
        }
    $page = isset($_GET['page'])?$_GET['page']+0:1;
    if($mes1){
        alert('添加成功','./man_user.php?page='.$page);
    }else{
        alert('添加失败');
    }
}

//编辑用户
if($hid=='u_edit'){
    check_auth('user',3);
    $uid = isset($_POST['uid'])?addslashes($_POST['uid']):'';
    $real_name = isset($_POST['real_name'])?addslashes($_POST['real_name']):'';
    $user_departments = isset($_POST['user_departments'])?addslashes($_POST['user_departments']):'';
    $user_state = isset($_POST['user_state'])?addslashes($_POST['user_state']):'';
    $user_group = isset($_POST['user_group'])?$_POST['user_group']:'';
    $new_pass = isset($_POST['new_pass'])?addslashes($_POST['new_pass']):'';
    $check_pass = isset($_POST['check_pass'])?addslashes($_POST['check_pass']):'';
    if($real_name == ''){
        alert('姓名不能为空');
        exit;
    }
    $data = array(
        'realname'=>$real_name,
        'active'=>$user_state,
        'department_id'=>$user_departments
    );
    if(!empty($new_pass)){
        check_pw($new_pass);
        if($new_pass==$check_pass){
            $data['password']=base64_encode(sha1($new_pass,true));
        }else{
            alert('两次输入密码不一致，请重输');
        }
    }
    if(!empty($uid)){
        $mes1 = $res
            ->table('tb_users')
            ->where("user_id='{$uid}'")
            ->save($data);

        $mes2 = $res
            ->table('tb_users_ugrps')
            ->where("user_id='{$uid}'")
            ->delete();
        if($mes2){
            $group_data['user_id']=$uid;
            if(!empty($user_group)){
                foreach($user_group as $k => $v){
                    $group_data['group_id'] = $v;
                    $mes[$k] = $res
                        ->table('tb_users_ugrps')
                        ->add($group_data);
                }
            }else{
                $mes=1;
            }
        }
        $page = isset($_GET['page'])?$_GET['page']+0:1;
        if($mes1){
            alert('修改成功','./man_user.php?page='.$page);
        }else{
            alert('修改失败');
        }
    }

}


include_once ('view/man_user.php');
