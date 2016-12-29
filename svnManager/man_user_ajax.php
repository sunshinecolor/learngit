<?php
include_once ('config/config.php');
include_once (INCLUDE_PATH."/common.php");
include_once ('php/postGreSql.php');

if($_POST){
    $res = new postGreSql($db);
    $id = isset($_POST['id'])?$_POST['id']:'';
    $type = isset($_POST['type'])?$_POST['type']:'';

    //增加用户
    if($type == 'user_add'){
        //查询部门
        $department_data = $res
            ->table('tb_departments')
            ->field('department_id,department_name')
            ->select();
        //组查询
        $group_data = $res
            ->table('tb_usergroups')
            ->field('group_id,group_name')
            ->select();
        $deparData = array(
            'departments' => $department_data,
            'group'=> $group_data
        );
        if($deparData) $mes = array('state'=>'Success','mes'=>$deparData,'type'=>$_POST['type']);
        else $mes = array('state'=>'Error','mes'=>'没有相关信息！');
        echo json_encode($mes);
    }

    //编辑用户
    if($type == 'user_edit'){
        //基本信息查询
        $personalinfo = $res
            ->table('tb_users as u')
            ->field('user_id,username,realname,sexes,phone,email,department_name,create_time,login_count,login_time,active')
            ->join('tb_departments as de on u.department_id=de.department_id','left')
            ->where("user_id='{$id}'")
            ->find();
        //所在部门查询
        $departments = $res
            ->table('tb_departments as de')
            ->field('department_name,user_id')
            ->join('tb_users us on us.department_id=de.department_id','left')
            ->where("us.user_id='{$id}'")
            ->andselect();
        //查询部门ID
        foreach($departments as $k => $v){
            $department_id = $res
                ->table('tb_departments as de')
                ->field('department_id')
                ->where("department_name='{$v['department_name']}'")
                ->find();
            $departments[$k]['department_id']=$department_id['department_id'];
        }
        //所在组查询
        $personalgroup = $res
            ->table('tb_users as u')
            ->field('us.group_name,u.user_id')
            ->join('tb_users_ugrps up on u.user_id=up.user_id')
            ->join('tb_usergroups us on up.group_id=us.group_id','right')
            ->where("u.user_id='{$id}'")
            ->andselect();
        //查询组ID
        foreach($personalgroup as $k => $v){
            $group_id = $res
                ->table('tb_usergroups')
                ->field('group_id')
                ->where("group_name='{$v['group_name']}'")
                ->find();
            $personalgroup[$k]['group_id']=$group_id['group_id'];
        }
        $deparData = array(
            'info' => $personalinfo,
            'departments' => $departments,
            'group'=> $personalgroup
        );
        if($deparData) $mes = array('state'=>'Success','mes'=>$deparData,'type'=>$_POST['type']);
        else $mes = array('state'=>'Error','mes'=>'没有相关信息！');
        echo json_encode($mes);
    }

    //删除用户
    if($type == 'user_del'){
        check_auth_json('user',7);
        if($id!=''){
            $return1 = @$res
                ->table('tb_users_ugrps')
                ->where("user_id='{$id}'")
                ->delete();
            if($return1){
                $return2 = $res
                    ->table('tb_users')
                    ->where("user_id='{$id}'")
                    ->delete();
            }
        }
        $msg = $return2?1:0;
        $deparData = array(
            'msg' => $msg,
        );
        if($deparData) $mes = array('state'=>'Success','mes'=>$deparData,'type'=>$_POST['type']);
        else $mes = array('state'=>'Error','mes'=>'没有相关信息！');
        echo json_encode($mes);
    }
}else{
    echo json_encode(array('status'=>222));
}