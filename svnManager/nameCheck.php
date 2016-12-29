<?php
include_once ('config/config.php');
include_once (INCLUDE_PATH."/common.php");
include_once ('php/postGreSql.php');
$res = new postGreSql($db);
if($_POST){
    $id   = isset($_POST['id'])?$_POST['id']:'';
    $name = strtolower($_POST['name']);
    $type = $_POST['type'];
    if($type == 'depart'){
        check_auth('user',1);
        $departId = $res->table('tb_departments')->field('department_id')->where("department_name = '{$name}'")->find();
        if(empty($id)){
            if($departId) $mes = array('state'=>'Error','mes'=>'此部门名称已重复，请您重新输入！');
            else $mes = array('state'=>'Success','mes'=>'可用！');
        }else{
            if($departId){
                if($departId['department_id'] == $id){
                    $mes = array('state'=>'Success','mes'=>'可用！');
                }else{
                    $mes = array('state'=>'Error','mes'=>'此部门名称已重复，请您重新输入！');
                }
            }
            else $mes = array('state'=>'Success','mes'=>'可用！');
        }
        echo json_encode($mes);
    }elseif($type == 'project'){
        check_auth('proj',1);
        $code_name = isset($_POST['code_name'])?$_POST['code_name']:'';
        $departId = $res->table('tb_projects')->field('project_id')->where("project_name = '{$name}'")->find();
        $code = $res->table('tb_projects')->field('project_id')->where("project_name = '{$code_name}'")->find();
        if(empty($id)){
            if($departId) $mes = array('state'=>'Error','mes'=>'此项目名称已重复，请您重新输入！');
            if($code) $mes = array('state'=>'Error','mes'=>'此项目代码名称已重复，请您重新输入！');
            else $mes = array('state'=>'Success','mes'=>'可用！');
        }else{
            if($departId){
                if($departId['project_id'] == $id){
                    $mes = array('state'=>'Success','mes'=>'可用！');
                }else{
                    $mes = array('state'=>'Error','mes'=>'此项目名称已重复，请您重新输入！');
                }
            }elseif($code){
                if($departId['project_id'] == $id){
                    $mes = array('state'=>'Success','mes'=>'可用！');
                }else{
                    $mes = array('state'=>'Error','mes'=>'此项目代码名称已重复，请您重新输入！');
                }
            }
            else $mes = array('state'=>'Success','mes'=>'可用！');
        }
        echo json_encode($mes);
    }

}
