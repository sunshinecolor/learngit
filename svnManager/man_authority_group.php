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
        if(isset($message)) alert($message,'man_authority_group.php?page='.$num);
        else alert('修改成功！','man_authority_group.php?page='.$num);
    }
}else{
    check_auth('auth',1);
    $keyword = isset($_GET['keyword'])?$_GET['keyword']:'';
    $where = isset($_GET['keyword'])?"group_name like '%{$keyword}%'":'';
    $count = $res->table('tb_usergroups')->where($where)->count();
    $num = isset($_GET['page'])?$_GET['page']:1;
    $show = 5;
    $page = new page($count,$show);
    $link = $page->pageinfo($num,'&keyword='.$keyword);
    $start = ($num-1)*$show;
    $groups = $res->table('tb_usergroups')->where($where)->order('group_id')->limit($start,$show)->select();
    foreach($groups as $k=>$v){
        $module = $res->table('tb_user_authority')->field('module_id,authorities')->where('object_type = 0 and object_id = '.$v['group_id'])->select();
        if(!empty($module)) $groups[$k]['auth'] = $module;
    }
}
include_once ('view/man_authority_group.php');