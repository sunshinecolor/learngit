<?php
include_once ('config/config.php');
include_once (INCLUDE_PATH."/common.php");
include_once ('php/postGreSql.php');
$res = new postGreSql($db);
if($_POST){
    $id = $_POST['id'];
    if($_POST['type'] == 'depart'){
        check_auth('user',7);
        $deparData = $res->table('tb_departments')->where('department_id = '.$id)->delete();
        if($deparData) $mes = array('state'=>'Success','mes'=>'删除成功！');
        else $mes = array('state'=>'Error','mes'=>'删除失败！');
        echo json_encode($mes);
    } elseif($_POST['type'] == 'project'){
        check_auth('proj',7);
        $mesData = $res->table('tb_projects')->where('project_id = '.$id)->find();
        if($mesData){
            shell_exec('rm -Rf /svn/repos/'.$mesData['prj_code_name']);
            $bool = $res->table('tb_projects')->where('project_id = '.$id)->delete();
            if($bool){
                $mes = array('state'=>'Success','mes'=>'删除成功！');
            }else{
                $mes = array('state'=>'Error','mes'=>'删除失败！');
            }
        }
        else $mes = array('state'=>'Error','mes'=>'删除失败,没有相关数据！');
        echo json_encode($mes);
    }
}else{
    echo json_encode(array('status'=>222));
}
//var_dump($deparData);
//include_once ('view/man_department.php');