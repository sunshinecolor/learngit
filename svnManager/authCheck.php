<?php
include_once ('config/config.php');
include_once (INCLUDE_PATH."/common.php");
include_once ('php/postGreSql.php');
$res = new postGreSql($db);
if($_POST){
    check_auth('auth',1);
    $id = $_POST['id'];
    if($_POST['type'] == 'user'){
        $user = $res->table('tb_user_authority')->field('module_id,authorities')->where('object_type = 1 and object_id = '.$id)->select();
       /* $sql  = "SELECT ua.module_id,ua.authorities from (SELECT uu.group_id,u.user_id from tb_users u INNER JOIN tb_users_ugrps uu ON u.user_id = uu.user_id
                WHERE u.user_id = {$id}) g INNER JOIN tb_user_authority ua ON ua.object_id = g.group_id WHERE ua.object_type = 0";
        $result = $res->query($sql);
        empty($user)?$user = array():'';
        empty($result)?$result = array():'';
        $user = array_merge($user,$result);*/
        if($user) $mes = array('state'=>'Success','mes'=>$user,'type'=>$_POST['type']);
        else $mes = array('state'=>'Error','mes'=>'没有相关信息！');
        echo json_encode($mes);
    }elseif($_POST['type'] == 'group'){
        $group = $res->table('tb_user_authority')->field('module_id,authorities')->where('object_type = 0 and object_id = '.$id)->select();
        if($group) $mes = array('state'=>'Success','mes'=>$group,'type'=>$_POST['type']);
        else $mes = array('state'=>'Error','mes'=>'没有相关信息！');
        echo json_encode($mes);
    }elseif($_POST['type'] == 'auth-user'){
        $group = $res->table('tb_project_authority')->field('project_id,authorities')->where('object_type = 1 and object_id = '.$id)->select();
        if($group) $mes = array('state'=>'Success','mes'=>$group,'type'=>$_POST['type']);
        else $mes = array('state'=>'Error','mes'=>'没有相关信息！');
        echo json_encode($mes);
    }else{
        $group = $res->table('tb_project_authority')->field('project_id,authorities')->where('object_type = 0 and object_id = '.$id)->select();
        if($group) $mes = array('state'=>'Success','mes'=>$group,'type'=>$_POST['type']);
        else $mes = array('state'=>'Error','mes'=>'没有相关信息！');
        echo json_encode($mes);
    }
}else{
    echo json_encode(array('status'=>222));
}
//var_dump($deparData);
//include_once ('view/man_department.php');