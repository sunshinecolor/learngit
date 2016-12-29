<?php
include_once ('config/config.php');
include_once (INCLUDE_PATH."/common.php");
include_once ('php/postGreSql.php');
include_once ('php/Page.class.php');
$res = new postGreSql($db);
if($_POST){
    check_auth('auth',3);
    $mes = $_POST;
    $idData = explode(',',$mes['aname-id']);
    $saveData['object_id'] = $idData[1];
    $saveData['object_type'] = $idData[0];
    $num = isset($_GET['page'])?$_GET['page']:1;
    if(isset($mes['edit-type']) && $mes['edit-type'] == 'module'){
        $bool = $res->table('tb_user_authority')->where("object_type = ".$idData[0]." and object_id = ".$idData['1'])->delete();
        if($bool){
            if(!empty($mes['facility'])){
                foreach($mes['facility'] as $v){
                    $facData = explode(',',$v);
                    $saveData['module_id'] = $facData[0];
                    $saveData['authorities'] = $facData[1];
                    $bool = $res->table('tb_user_authority')->add($saveData);
                    if(!$bool){
                        $res->table('tb_user_authority')->where("object_type = ".$idData[0]." and object_id = ".$idData['1'])->delete();
                        $message = '修改失败，请您稍后重试！';
                        break;
                    }
                }
            }
        }
        if(isset($message)) alert($message,'man_authority.php?page='.$num);
        else alert('修改成功！','man_authority.php?page='.$num);
    }
}else{
    check_auth('auth',1);
    $keyword = isset($_GET['keyword'])?$_GET['keyword']:'';
    $where = isset($_GET['keyword'])?"u.username like '%{$keyword}%' or u.realname like '%{$keyword}%'":'';
    $count = $res->table('tb_users u')->where($where)->count();
    $num = isset($_GET['page'])?$_GET['page']:1;
    $show = 5;
    $page = new page($count,$show);
    $link = $page->pageinfo($num,'&keyword='.$keyword);
    $start = ($num-1)*$show;
    $users = $res->table('tb_users u')
        ->where($where)
        ->field('u.user_id,u.realname,d.department_name,u.username')
        ->join('tb_departments d on d.department_id = u.department_id','left')
        ->limit($start,$show)
        ->order('u.user_id')
        ->select();
    foreach($users as $k=>$v){
        $mes = $res->table('tb_user_authority')->field('module_id,authorities')->where('object_type = 1 and object_id = '.$v['user_id'])->select();
        $sql_group = "SELECT up.group_name from tb_users u INNER JOIN tb_users_ugrps uu ON u.user_id = uu.user_id
                INNER JOIN tb_usergroups up ON up.group_id = uu.group_id WHERE uu.user_id = {$v['user_id']}";
        $groups = $res->query($sql_group);
        empty($groups)?$groups = array():$users[$k]['groups'] = $groups;
        $auth_user = array();
        if(!empty($mes)){
            foreach($mes as $va){
                if($va['module_id'] == 1){
                    $module_max[] = $va['authorities'];
                }elseif($va['module_id'] == 2){
                    $pro_max[] = $va['authorities'];
                }else{
                    $auth_max[] = $va['authorities'];
                }
            }
            if(!empty($module_max)){
                $module = max($module_max);
                if($module == 1){
                    $auth_user[] = '用户、部门（只读）';
                }elseif($module == 3){
                    $auth_user[] = '用户、部门（读写）';
                }else{
                    $auth_user[] = '用户、部门（读写删）';
                }
            }
            if(!empty($pro_max)){
                $module = max($pro_max);
                if($module == 1){
                    $auth_user[] = '项目（只读）';
                }elseif($module == 3){
                    $auth_user[] = '项目（读写）';
                }else{
                    $auth_user[] = '项目（读写删）';
                }
            }
            if(!empty($auth_max)){
                $module = max($auth_max);
                if($module == 1){
                    $auth_user[] = '授权（只读）';
                }elseif($module == 3){
                    $auth_user[] = '授权（读写）';
                }else{
                    $auth_user[] = '授权（读写删）';
                }
            }
        }
        unset($module_max);
        unset($pro_max);
        unset($auth_max);
        //用户组权限
        $auth_group =array();
        $sql = "SELECT g.user_id,ua.* from (SELECT uu.group_id,u.user_id from tb_users u INNER JOIN tb_users_ugrps uu ON u.user_id = uu.user_id
                WHERE u.user_id = {$v['user_id']}) g INNER JOIN tb_user_authority ua ON ua.object_id = g.group_id WHERE ua.object_type = 0";
        $result = $res->query($sql);
        if(!empty($result)){
            foreach($result as $va) {
                if ($va['module_id'] == 1) {
                    $module_max[] = $va['authorities'];
                } elseif ($va['module_id'] == 2) {
                    $pro_max[] = $va['authorities'];
                } else {
                    $auth_max[] = $va['authorities'];
                }
            }
            if(!empty($module_max)){
                $module = max($module_max);
                if($module == 1){
                    $auth_group[] = '用户、部门（只读）';
                }elseif($module == 3){
                    $auth_group[] = '用户、部门（读写）';
                }else{
                    $auth_group[] = '用户、部门（读写删）';
                }
            }
            if(!empty($pro_max)){
                $module = max($pro_max);
                if($module == 1){
                    $auth_group[] = '项目（只读）';
                }elseif($module == 3){
                    $auth_group[] = '项目（读写）';
                }else{
                    $auth_group[] = '项目（读写删）';
                }
            }
            if(!empty($auth_max)){
                $module = max($auth_max);
                if($module == 1){
                    $auth_group[] = '授权（只读）';
                }elseif($module == 3){
                    $auth_group[] = '授权（读写）';
                }else{
                    $auth_group[] = '授权（读写删）';
                }
            }
        }
        unset($module_max);
        unset($pro_max);
        unset($auth_max);
        $users[$k]['auth_user'] = array_unique($auth_user);
        $users[$k]['auth_group'] = array_unique($auth_group);
        unset($auth_user);
        unset($auth_group);
        unset($groups);
    }
}
include_once ('view/man_authority.php');