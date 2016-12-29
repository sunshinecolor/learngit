<?php
include_once ('config/config.php');
include_once (INCLUDE_PATH."/common.php");
include_once ('php/postGreSql.php');
$res = new postGreSql($db);
if($_POST){
    $id = $_POST['id'];
    if($_POST['type'] == 'depart'){
        check_auth('user',1);
        $deparData = $res->table('tb_departments')->where('department_id = '.$id)->find();
        $parent_name = $res->table('tb_departments')->field('department_name')->where('department_id = '.$deparData['parent_id'])->find();
        $deparData['parent_name'] = $parent_name['department_name'];
        if($deparData) $mes = array('state'=>'Success','mes'=>$deparData,'type'=>$_POST['type']);
        else $mes = array('state'=>'Error','mes'=>'没有相关信息！');
        echo json_encode($mes);
    }elseif($_POST['type'] == 'project'){
        check_auth('proj',1);
        $project = $res->table('tb_projects')->where('project_id = '.$id)->find();
        if($project) $mes = array('state'=>'Success','mes'=>$project,'type'=>$_POST['type']);
        else $mes = array('state'=>'Error','mes'=>'没有相关信息！');
        echo json_encode($mes);
    }
}else{
    echo json_encode(array('status'=>222));
}
//var_dump($deparData);
//include_once ('view/man_department.php');