<?php
include_once ('config/config.php');
include_once (INCLUDE_PATH."/common.php");
include_once ('php/postGreSql.php');
include_once ('php/Page.class.php');
$res = new postGreSql($db);
if($_POST){
    check_auth('user',3);
    $mes = $_POST;
    if($mes['department'] == 'edit'){
        $resData['department_name'] = $mes['e_dname'];
        $resData['parent_id'] = $mes['e_up_dname'];
        $bool = $res->table('tb_departments')->where('department_id = '.$mes['did'])->save($resData);
        if($bool !== false || $bool !== 0){
            alert('修改成功！','./man_department.php');
        }else{
            alert('修改失败！');
        }
    }else{
        //var_dump($mes);exit;
        $resData['department_name'] = $mes['dname'];
        $resData['parent_id'] = $mes['up_dname'];
        $resData['order_no'] = 7;
        if($bool = $res->table('tb_departments')->add($resData)){
            alert('添加成功！','./man_department.php');
        }else{
            alert('添加失败！');
        }
    }
}else{
    check_auth('user',1);
    $keyword = isset($_GET['keyword'])?$_GET['keyword']:'';
    $where = isset($_GET['keyword'])?"department_name like '%{$keyword}%'":'';
    $count = $res->table('tb_departments')->where($where)->count();
    $num = isset($_GET['page'])?$_GET['page']:1;
    $show = 5;
    $page = new page($count,$show);
    $link = $page->pageInfo($num,'&keyword='.$keyword);
    $start = ($num-1)*$show;
    $deparData = $res->table('tb_departments')->where($where)->order('order_no')->limit($start,$show)->select();
    $deparDatas = $res->table('tb_departments')->order('order_no')->select();
    foreach($deparData as $k=>$v){
        $users = $res->table('tb_departments d')->field('u.username')->join('tb_users u on u.department_id = d.department_id')->where('d.department_id = '.$v['department_id'])->select();
        $parent_name = $res->table('tb_departments')->field('department_name')->where('department_id = '.$v['parent_id'])->find();
        unset($deparData[$k]['parent_id']);
        $str = '';
        if(!empty($users)){
            foreach($users as $val){
                $str.=$val['username'].', ';
            }
            $str = substr($str,0,strlen($str)-2);
        }
        $deparData[$k]['parent_name'] = $parent_name['department_name'];
        $deparData[$k]['users'] = $str;
    }
}
include_once ('view/man_department.php');