<?php
include_once ('config/config.php');
include_once (INCLUDE_PATH."/common.php");
include_once ('php/postGreSql.php');
include_once ('php/Page.class.php');
$res = new postGreSql($db);
if($_POST){
    check_auth('proj',3);
    $mes = $_POST;
    if($mes['type'] == 'add'){
        $pro['project_name']  = strtolower($mes['pname']);
        $pro['prj_code_name'] = strtolower($mes['pcode_name']);
        $pro['order_no'] = 7;
        if($bool = $res->table('tb_projects')->add($pro)){
            shell_exec('sudo svnadmin create /svn/repos/'.$pro['prj_code_name']);
            if(is_dir('/svn/repos/'.$pro['prj_code_name'])){
                shell_exec('/svn/chauth');
                alert('添加成功！','./man_pro.php');
            }else{
                $res->table('tb_projects')->where("project_name = '{$pro['project_name']}'")->delete();
                alert('添加失败,创建版本库失败！');
            }
        }else{
            alert('添加失败！');
        }
    }else{
        check_auth('proj',1);
        $pro['project_name']  = strtolower($mes['e_pname']);
        $pro['prj_code_name'] = strtolower($mes['e_pcode_name']);
        $oldName = $res->table('tb_projects')->field('prj_code_name')->where('project_id = '.$mes['proid'])->find();
        $boolData = true;
        if($pro['prj_code_name'] != $oldName['prj_code_name']){
            $boolData = rename('/svn/repos/'.$oldName['prj_code_name'],'/svn/repos/'.$pro['prj_code_name']);
        }
        if($boolData){
            $bool = $res->table('tb_projects')->where('project_id = '.$mes['proid'])->save($pro);
            if($bool !== false || $bool !== 0){
                alert('修改成功！','./man_pro.php');
            }else{
                alert('修改失败！');
            }
        }else{
            alert('修改失败！');
        }
    }
}else{
    $keyword = isset($_GET['keyword'])?$_GET['keyword']:'';
    $where = isset($_GET['keyword'])?"project_name like '%{$keyword}%' or prj_code_name like '%{$keyword}%'":'';
    $count = $res->table('tb_projects')->where($where)->count();
    $num = isset($_GET['page'])?$_GET['page']:1;
    $show = 5;
    $page = new page($count,$show);
    $link = $page->pageInfo($num,'&keyword='.$keyword);
    $start = ($num-1)*$show;
    $project = $res->table('tb_projects')->order('order_no')->where($where)->limit($start,$show)->select();
}
include_once ('view/man_pro.php');