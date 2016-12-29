<?php
include_once ('config/config.php');
include_once (INCLUDE_PATH."/common.php");
include_once ('php/postGreSql.php');
$res = new postGreSql($db);
if($_POST){
    check_auth('auth',3);
    $mes = $_POST;
    $str = explode(',',$mes['mes-data']);
    $path = $mes['path'];
    $pro_mes = $res->table('tb_project_authority')
        ->where("object_id = {$str[1]} and object_type = {$str[0]} and project_id = {$str[2]} and prj_path = '{$path}' and authorities = {$mes['pro_auth']}")
        ->find();
    if(empty($pro_mes)){
        if($mes['path'] != '/'){
            if(!is_dir('/svn/repos/'.$mes['project_name'].$mes['path'])){
                alert('不存在的目录路径');
                exit;
            }
        }
        $saveData = array(
            'object_id' => $str[1],
            'object_type' => $str[0],
            'project_id' => $str[2],
            'prj_path' => $path,
            'authorities' => $mes['pro_auth']
        );
        $bool = $res->table('tb_project_authority')->add($saveData);
        if($bool){
            alert('添加成功','man_pro_authority.php');
        }else{
            alert('添加失败');
        }
    }else{
        alert('已拥有此目录权限');
    }
}else{
    check_auth('auth',1);
    $users  = $res->table('tb_users')->field('username,user_id')->select();
    $groups = $res->table('tb_usergroups')->field('group_name,group_id')->select();
    $projects = $res->table('tb_projects')->field('project_id,project_name,prj_code_name')->select();
    //var_dump($groups);echo '222';
}
include_once ('view/man_pro_authority.php');