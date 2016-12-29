<?php
include_once ('config/config.php');
include_once (INCLUDE_PATH."/common.php");
include_once ('php/postGreSql.php');

if($_POST){
    $res = new postGreSql($db);
    $id = isset($_POST['id'])?$_POST['id']:'';
    $type = isset($_POST['type'])?$_POST['type']:'';

    //增加用户组
    if($type == 'group_add'){
        //组查询
        $personalgroup = $res
            ->table('tb_users')
            ->field('user_id,realname')
            ->order('realname asc')
            ->select();
        $deparData = array(
            'personalgroup' => $personalgroup
        );
        if($deparData) $mes = array('state'=>'Success','mes'=>$deparData,'type'=>$_POST['type']);
        else $mes = array('state'=>'Error','mes'=>'没有相关信息！');
        echo json_encode($mes);
    }

    //编辑用户组
    if($type == 'group_edit'){
        //组查询
        $groupinfo = $res
            ->table('tb_usergroups')
            ->field('group_id,group_name,grp_code_name')
            ->where("group_id='{$id}'")
            ->find();
        //用户所在组查询
        $personalgroup = $res
            ->table('tb_usergroups as us')
            ->field('us.group_id,u.user_id,u.realname')
            ->join('tb_users_ugrps up on up.group_id=us.group_id')
            ->join('tb_users u on u.user_id=up.user_id','right')
            ->order('u.realname asc')
            ->where("us.group_id='{$id}'")
            ->andselect();
        $deparData = array(
            'info' => $groupinfo,
            'personalgroup' => $personalgroup
        );
        if($deparData) $mes = array('state'=>'Success','mes'=>$deparData,'type'=>$_POST['type']);
        else $mes = array('state'=>'Error','mes'=>'没有相关信息！');
        echo json_encode($mes);
    }

    //删除用户组
    if($type == 'group_del'){
        check_auth_json('user',7);
        if($id!=''){
            $return1 = @$res
                ->table('tb_users_ugrps')
                ->where("group_id='{$id}'")
                ->delete();
            if($return1){
                $return2 = @$res
                    ->table('tb_usergroups')
                    ->where("group_id='{$id}'")
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