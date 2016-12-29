<?php
include_once ('config/config.php');
include_once (INCLUDE_PATH."/common.php");
include_once ('php/postGreSql.php');

$res = new postGreSql($db);
if($_POST){
    check_auth('auth',1);
    $id = $_POST['id'];
    $type = $_POST['type'];
    $pro = isset($_POST['pro'])?$_POST['pro']:'';
    if($_POST['type'] == '1'){
        //个人权限
        $user = $res->table('tb_project_authority pa')
            ->field('pa.object_type,pa.project_id,pa.authorities,pa.prj_path,p.prj_code_name,pa.pa_id')
            ->join('tb_projects p on p.project_id = pa.project_id')
            ->where('pa.object_type = 1 and pa.object_id = '.$id.' and pa.project_id = '.$pro)
            ->select();
        //组权限
        $sql = "SELECT pa.object_type,pa.project_id,pa.authorities,pa.prj_path,p.prj_code_name,pa.pa_id from
                (SELECT uu.group_id,u.user_id from tb_users u INNER JOIN tb_users_ugrps uu ON u.user_id = uu.user_id WHERE u.user_id = {$id}) g
                INNER JOIN tb_project_authority pa ON pa.object_id = g.group_id AND pa.object_type = 0
                INNER JOIN tb_projects p ON p.project_id = pa.project_id WHERE pa.project_id = {$pro}";
        $result = $res->query($sql);
        empty($user)?$user = array():'';
        empty($result)?$result = array():'';
        //$user = array_merge($user,$result);
        $path = array();
        if($result){
            foreach($result as $v){
                $path[] = $v['prj_path'];
            }
            $path = array_unique($path);
            foreach($path as $key=>$val){
                foreach($result as $k=>$v){
                    if($v['prj_path'] == $val){
                        if(is_array($path[$key])){
                            if($path[$key]['authorities'] < $v['authorities']){
                                $path[$key]['authorities'] = $v['authorities'];
                            }
                        }else{
                            $path[$key] = array(
                                'object_type'   => $v['object_type'],
                                'project_id'    => $v['project_id'],
                                'authorities'   => $v['authorities'],
                                'prj_path'      => $val,
                                'prj_code_name' => $v['prj_code_name'],
                                'pa_id'         => $v['pa_id']
                            );
                        }
                    }
                }
            }
        }
        if($user) $mes = array('state'=>'Success','mes'=>$user,'group'=>$path,'type'=>$_POST['type']);
        else $mes = array('state'=>'Error','mes'=>'没有相关信息！','type'=>$_POST['type']);
        echo json_encode($mes);
    }else if($_POST['type'] == '0'){
        $group = $res->table('tb_project_authority pa')
            ->field('pa.object_type,pa.project_id,pa.authorities,pa.prj_path,p.prj_code_name,pa.pa_id')
            ->join('tb_projects p on p.project_id = pa.project_id')
            ->where('pa.object_type = 0 and pa.object_id = '.$id.' and pa.project_id = '.$pro)
            ->select();
        if($group) $mes = array('state'=>'Success','mes'=>$group,'type'=>$_POST['type']);
        else $mes = array('state'=>'Error','mes'=>'没有相关信息！');
        echo json_encode($mes);
    }elseif($_POST['type'] == 'del'){
        check_auth('auth',7);
        $delData = explode(',',$id);
        $object_type = $res->table('tb_project_authority')->field('pa_id')->where("object_type = {$delData[1]} and object_id = {$delData[0]} and pa_id = {$delData[2]}")->find();
        if(!$object_type['pa_id']){
            if($delData[1] == 1){
                echo json_encode(array('state'=>'Error','mes'=>'删除失败，无法删除组的权限！'));
                exit;
            }else{
                echo json_encode(array('state'=>'Error','mes'=>'删除失败,没有相关记录！'));
                exit;
            }
        }
        $bool = $res->table('tb_project_authority')->where('pa_id = '.$delData[2])->delete();
        if($bool) $mes = array('state'=>'Success','mes'=>'删除成功！');
        else $mes = array('state'=>'Error','mes'=>'删除失败！');
        echo json_encode($mes);
    }
}else{
    echo json_encode(array('status'=>222));
}
