<?php
include_once ('config/config.php');
include_once (INCLUDE_PATH."/common.php");
include_once ('php/postGreSql.php');
include_once ('php/Page.class.php');

$res = new postGreSql($db);

check_auth('user',1);
//分页
$count = $res
    ->table('tb_usergroups')
    ->count('group_id');
$num = isset($_GET['page'])?$_GET['page']:1;
$limit = 5;
$page = new page($count,$limit);
$link = $page->pageinfo($num);

//所有组查询
$start = ($num-1)*$limit;
$groupinfo = $res
    ->table('tb_usergroups')
    ->field('group_id,group_name,grp_code_name')
    ->order('group_id asc')
    ->limit($start,$limit);
//添加模糊查询条件
if(isset($_GET['like']) && $_GET['like']!=''){
    $like = addslashes($_GET['like']);
    $groupinfo = $res
        ->table('tb_usergroups')
        ->where("group_name like '%{$like}%' or grp_code_name like '%{$like}%'")
        ->select();
}else{
    $groupinfo = $res
        ->table('tb_usergroups')
        ->andselect();
}

//所有组的所有成员查询
$info = array();
foreach($groupinfo as $k => $v){
    $info[$k]['info']=$v;
    $personalgroup = $res
        ->table('tb_users as u')
        ->field('u.username,u.realname')
        ->join('tb_users_ugrps up on u.user_id=up.user_id')
        ->join('tb_usergroups us on up.group_id=us.group_id')
        ->order('u.realname asc')
        ->where("us.group_id='{$v['group_id']}'")
        ->select();
    if(empty($personalgroup)){
        $personalgroup[0]['realname']='暂无数据';
    }
    $info[$k]['person']=$personalgroup;

    //权限查询
    $info[$k]['auth']['user_num'] = 0;
    $info[$k]['auth']['proj_num'] = 0;
    $info[$k]['auth']['auth_num'] = 0;

    $group_id = $groupinfo[$k]['group_id'];
    $group_auth = $res
        ->table('tb_user_authority')
        ->field('module_id,authorities')
        ->where("object_id='{$group_id}' and object_type=0")
        ->select();
    //module_id=0/用户组权限查询
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

$hid = isset($_POST['hid'])?$_POST['hid']:'';
//增加用户
if($hid=='g_add'){
    check_auth('user',3);
    $uname = isset($_POST['uname'])?addslashes($_POST['uname']):'';
    $codename = isset($_POST['codename'])?addslashes($_POST['codename']):'';
    $user = isset($_POST['user'])?$_POST['user']:'';
    if($uname == ''){
        alert('组名不能为空');
        exit;
    }
    if($codename == ''){
        alert('组代码名不能为空');
        exit;
    }
    $check_groupname = $res
        ->table('tb_usergroups')
        ->field('group_id')
        ->where("group_name='{$uname}'")
        ->find();
    if($check_groupname){
        alert('组名已存在，请换组名添加');
        exit;
    }
    $check_codename = $res
        ->table('tb_usergroups')
        ->field('group_id')
        ->where("grp_code_name='{$codename}'")
        ->find();
    if($check_codename){
        alert('代码组名已存在，请换代码组名添加');
        exit;
    }
    $data = array(
        'group_name'=>$uname,
        'grp_code_name'=>$codename,
    );
    $mes1 = $res
        ->table('tb_usergroups')
        ->add($data);
    $check_group_id = $res
        ->table('tb_usergroups')
        ->field('group_id')
        ->where("group_name='{$uname}'")
        ->find();
    $uid = $check_group_id['group_id'];
    if($mes1){
        $group_data['group_id']=$uid;
        if(!empty($user)){
            foreach($user as $k => $v){
                $group_data['user_id'] = $v;
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
        alert('添加成功','./man_usergroup.php?page='.$page);
    }else{
        alert('添加失败');
    }
}

//编辑用户组
if($hid=='g_edit'){
    check_auth('user',3);
    $uid = isset($_POST['uid'])?addslashes($_POST['uid']):'';
    $user = isset($_POST['user'])?$_POST['user']:'';
    $uname = isset($_POST['uname'])?$_POST['uname']:'';
    $codename = isset($_POST['codename'])?$_POST['codename']:'';
    if($uname == ''){
        alert('组名不能为空');
        exit;
    }
    if($codename == ''){
        alert('组代码名不能为空');
        exit;
    }
    $check_groupname = $res
        ->table('tb_usergroups')
        ->field('group_id')
        ->where("group_name='{$uname}'")
        ->find();
    if($check_groupname){
        alert('组名已存在，请换组名添加');
        exit;
    }
    $check_codename = $res
        ->table('tb_usergroups')
        ->field('group_id')
        ->where("grp_code_name='{$codename}'")
        ->find();
    if($check_codename){
        alert('代码组名已存在，请换代码组名添加');
        exit;
    }
    $data = array(
        'group_name'=>$uname,
        'grp_code_name'=>$codename,
    );
    $return = $res
        ->table('tb_usergroups')
        ->where("group_id='{$uid}'")
        ->save($data);
    $mes1 = $res
        ->table('tb_users_ugrps')
        ->where("group_id='{$uid}'")
        ->delete();
    if($mes1){
        $group_data['group_id']=$uid;
        if(!empty($user)){
            foreach($user as $k => $v){
                $group_data['user_id'] = $v;
                $mes[$k] = $res
                        ->table('tb_users_ugrps')
                        ->add($group_data);
            }
            }else{
                $mes=1;
            }
        }
        $page = isset($_GET['page'])?$_GET['page']+0:1;
        if($return){
            alert('修改成功','./man_usergroup.php?page='.$page);
        }else{
            alert('修改失败');
        }
}

include_once ('view/man_usergroup.php');
