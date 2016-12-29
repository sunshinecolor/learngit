<?php


function alert($msg='null',$url=''){
    if($msg == 'null'){
        echo "<script>location.href='{$url}'</script>";
	exit;
    }else if($url){
        echo "<script>alert('{$msg}');location.href='{$url}'</script>";
	exit;
    }else{
        echo "<script>alert('{$msg}');window.history.back();</script>";
	exit;
    }

}

function deldir($path){
    //给定的目录不是一个文件夹
    if(!is_dir($path)){
        return null;
    }
    $fh = opendir($path);
    while(($row = readdir($fh)) !== false){
        //过滤掉虚拟目录
        if($row == '.' || $row == '..'){
            continue;
        }
        if(!is_dir($path.'/'.$row)){
            unlink($path.'/'.$row);
        }
        deldir($path.'/'.$row);
    }
    //关闭目录句柄，否则出Permission denied
    closedir($fh);
    //删除文件之后再删除自身
    rmdir($path);
    return true;
}

function dump($du){
    echo'<pre>';
    print_r($du);
    echo'</pre>';
}

//用户名验证
function check_un($uname,$url=''){
    $res = preg_match_all("/^[a-zA-Z][a-zA-Z\d_]{2,19}$/",$uname,$array);
    if(!$res){
        alert('用户名必须为3~20位的英文字母、数字和下划线组合，且首字符是英文字母',$url);
        exit;
    }
}

//密码验证
function check_pw($pw,$url=''){
    $res = preg_match_all("/^[a-zA-Z\d_]{6,32}$/",$pw,$array);
    if(!$res){
        alert('密码必须为6~32位，英文字母、数字和下划线的组合',$url);
        exit;
    }
}

//权限检查、$type为检查类型，$auth为检查权限的级别，$url为检查不通过的跳转地址；
function check_auth($type,$auth,$url=''){
    if(!($type=='user' || $type=='proj' || $type=='auth')){
        alert('参数1出错');
    }
    if(!($auth==1 || $auth==3 || $auth==7)){
        alert('参数2出错');
    }
    if($type == 'user'){
        $auth_u = $_SESSION['check_auth']['user'];
        if($auth_u < 1 && $auth == 1){
            alert('无查看权限',$url);
            exit;
        }
        if($auth_u < 3 && $auth == 3){
            alert('无修改权限',$url);
            exit;
        }
        if($auth_u < 7 && $auth == 7){
            alert('无删除权限',$url);
            exit;
        }
    }
    if($type == 'proj'){
        $auth_p = $_SESSION['check_auth']['proj'];
        if($auth_p < 1 && $auth == 1){
            alert('无查看权限',$url);
            exit;
        }
        if($auth_p < 3 && $auth == 3){
            alert('无修改权限',$url);
            exit;
        }
        if($auth_p < 7 && $auth == 7){
            alert('无删除权限',$url);
            exit;
        }
    }
    if($type == 'auth'){
        $auth_a = $_SESSION['check_auth']['auth'];
        if($auth_a < 1 && $auth == 1){
            alert('无查看权限',$url);
            exit;
        }
        if($auth_a < 3 && $auth == 3){
            alert('无修改权限',$url);
            exit;
        }
        if($auth_a < 7 && $auth == 7){
            alert('无删除权限',$url);
            exit;
        }
    }
}

//权限检查、$type为检查类型，$auth为检查权限的级别
function check_auth_json($type,$auth){
    if(!($type=='user' || $type=='proj' || $type=='auth')){
        alert('参数1出错');
    }
    if(!($auth==1 || $auth==3 || $auth==7)){
        alert('参数2出错');
    }
    $mes = array('auth_state'=>1,'mes'=>'');
    if($type == 'user'){
        $auth_u = $_SESSION['check_auth']['user'];
        if($auth_u < 1 && $auth == 1){
            $mes['auth_state'] = 0;
            $mes['mes'] = '无查看权限';
            echo json_encode($mes);
            exit;
        }
        if($auth_u < 3 && $auth == 3){
            $mes['auth_state'] = 0;
            $mes['mes'] = '无修改权限';
            echo json_encode($mes);
            exit;
        }
        if($auth_u < 7 && $auth == 7){
            $mes['auth_state'] = 0;
            $mes['mes'] = '无删除权限';
            echo json_encode($mes);
            exit;
        }
    }
    if($type == 'proj'){
        $auth_p = $_SESSION['check_auth']['proj'];
        if($auth_p < 1 && $auth == 1){
            $mes['auth_state'] = 0;
            $mes['mes'] = '无查看权限';
            echo json_encode($mes);
            exit;
        }
        if($auth_p < 3 && $auth == 3){
            $mes['auth_state'] = 0;
            $mes['mes'] = '无修改权限';
            echo json_encode($mes);
            exit;
        }
        if($auth_p < 7 && $auth == 7){
            $mes['auth_state'] = 0;
            $mes['mes'] = '无删除权限';
            echo json_encode($mes);
            exit;
        }
    }
    if($type == 'auth'){
        $auth_a = $_SESSION['check_auth']['auth'];
        if($auth_a < 1 && $auth == 1){
            $mes['auth_state'] = 0;
            $mes['mes'] = '无查看权限';
            echo json_encode($mes);
            exit;
        }
        if($auth_a < 3 && $auth == 3){
            $mes['auth_state'] = 0;
            $mes['mes'] = '无修改权限';
            echo json_encode($mes);
            exit;
        }
        if($auth_a < 7 && $auth == 7){
            $mes['auth_state'] = 0;
            $mes['mes'] = '无删除权限';
            echo json_encode($mes);
            exit;
        }
    }

}



