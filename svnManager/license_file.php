<?php
include_once ('config/config.php');
include_once (INCLUDE_PATH."/common.php");
include_once ('php/postGreSql.php');
$res = new postGreSql($db);
check_auth('auth',3);
//$authorities = $res->table('tb_user_authority')->where('object_id = '.$id.' and object_type = 1 and module_id = 3')->field('authorities')->find();
if(file_exists('/svn/conf/svn-repos.authz')){
    $result = fopen('/svn/conf/svn-repos.authz','w+');
    if(!$result) alert('授权失败，无法打开文件！');
    fwrite($result,'');
    fclose($result);
    $result = fopen('/svn/conf/svn-repos.authz','a+');
    if(!$result) alert('授权失败，无法打开文件！');
    fwrite($result,"[groups]");
//用户组
    $group = $res->field('group_name,group_id')->table('tb_usergroups')->select();
    foreach($group as $k=>$v){
        $groupData = $res->table('tb_users_ugrps uu')
            ->field('u.username')
            ->join('tb_usergroups up on uu.group_id = up.group_id')
            ->join('tb_users u on u.user_id = uu.user_id')
            ->where("uu.group_id = ".$v['group_id'])
            ->select();
        $str = $v['group_name'].'=';
        if(!empty($groupData)){
            foreach($groupData as $val){
                $str .= $val['username'].",";
            }
            $str = substr($str,0,strlen($str)-1);
        }
        fwrite($result,"\n".$str);
        unset($groupData);
        unset($str);
    }
//根目录权限
    fwrite($result,"\n\n[/]\n*=\n");
    $all_path = $res->table('tb_project_authority')->where('project_id = 0')->select();
    if(!empty($all_path)){
        foreach($all_path as $v){
            if($v['object_type'] == 0){
                $name = $res->table('tb_usergroups')->field('group_name')->where('group_id = '.$v['object_id'])->find();
                $str.='@'.$name['group_name'].'=';
            }else{
                $name = $res->table('tb_users')->field('username')->where('user_id = '.$v['object_id'])->find();
                $str.=$name['username'].'=';
            }
            switch($v['authorities']){
                case 1;
                    $str.='r';
                    break;
                case 3;
                    $str.='rw';
            }
            $str.="\n";
            fwrite($result,$str);
            unset($str);
        }
    }
//项目目录权限
    $projects = $res->table('tb_projects')->field('prj_code_name,project_id')->select();
    foreach($projects as $k=>$v){
        $str = "\n[".$v['prj_code_name'].":/]\n*=\n";
        $authorities = $res->table('tb_project_authority')->where('project_id = '.$v['project_id'])->select();
        if(!empty($authorities)){
            foreach($authorities as $val){
                $path[] = $val['prj_path'];
                if($val['prj_path'] == '/'){
                    if($val['object_type'] == 0){
                        $name = $res->table('tb_usergroups')->field('group_name')->where('group_id = '.$val['object_id'])->find();
                        $str.='@'.$name['group_name'].'=';
                    }else{
                        $name = $res->table('tb_users')->field('username')->where('user_id = '.$val['object_id'])->find();
                        $str.=$name['username'].'=';
                    }
                    switch($val['authorities']){
                        case 1;
                            $str.='r';
                            break;
                        case 3;
                            $str.='rw';
                    }
                    $str.="\n";
                }
            }
            $path = array_unique($path);
        }
        fwrite($result,$str);
        if(!empty($path)){
            foreach($path as $val){
                if($val != '/'){
                    $pro_path = "\n[".$v['prj_code_name'].":".$val."]\n*=\n";
                    $path_all_mes = $res->table('tb_project_authority')->where("project_id = {$v['project_id']} and prj_path = '{$val}'")->select();
                    foreach($path_all_mes as $value){
                        if($value['object_type'] == 0){
                            $name = $res->table('tb_usergroups')->field('group_name')->where('group_id = '.$value['object_id'])->find();
                            $pro_path.='@'.$name['group_name'].'=';
                        }else{
                            $name = $res->table('tb_users')->field('username')->where('user_id = '.$value['object_id'])->find();
                            $pro_path.=$name['username'].'=';
                        }
                        switch($value['authorities']){
                            case 1;
                                $pro_path.='r';
                                break;
                            case 3;
                                $pro_path.='rw';
                        }
                        $pro_path.="\n";
                    }
                    fwrite($result,$pro_path);
                }
            }
            unset($path);
        }
    }
    fclose($result);
    alert('生成授权文件成功');
}else{
    alert('授权失败，授权文件不存在！');
}


