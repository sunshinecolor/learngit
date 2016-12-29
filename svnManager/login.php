<?php
include_once ('config/config.php');
include_once (INCLUDE_PATH."/common.php");
include_once ('php/postGreSql_login.php');

if($_POST){
    $res = new postGreSql($db);
    $username = isset($_POST['username'])?addslashes($_POST['username']):'';
    $password = isset($_POST['password'])?addslashes($_POST['password']):'';
    if($username == ''){
        alert ('用户名不能为空');
        exit;
    }
    if($password == ''){
        alert ('密码不能为空');
        exit;
    }

    //检验用户状态和用户名是否存在
    $user = $res
        ->table('tb_users')
        ->field('user_id,password,active')
        ->where("username='{$username}'")
        ->find();
	if($user==array()){
		alert('用户名不存在');
		exit;
	}
    if($user['active']!=1){
        alert('用户处于不可用状态，如需使用请联系管理员激活');
        exit;
    }

    //验证用户密码
	$word = base64_encode(sha1($password,true));
	if(isset($user['password']) && $user['password']==$word){
		$_SESSION['user_id'] = $user['user_id'];
		$_SESSION['user_name'] = $username;
		$_SESSION['user_login'] = 1;

		//生成功能授权 SESSION
		$user_id = isset($user['user_id'])?$user['user_id']:'';
        //初始化授权信息
        $check_auth['user'] = 0;
        $check_auth['proj'] = 0;
        $check_auth['auth'] = 0;

        //object_type=1/用户的授权检测
		$authority = $res
			->table('tb_user_authority')
			->field('module_id,authorities')
			->where("object_id='{$user_id}' and object_type=1")
			->select();
        if(!empty($authority)){
            foreach($authority as $k => $v){
                if($v['module_id']==1){
                    if($check_auth['user'] < $v['authorities']){
                        $check_auth['user'] = $v['authorities'];
                    }
                }
                if($v['module_id']==2){
                    if($check_auth['proj'] < $v['authorities']){
                        $check_auth['proj'] = $v['authorities'];
                    }
                }
                if($v['module_id']==3){
                    if($check_auth['auth'] < $v['authorities']){
                        $check_auth['auth'] = $v['authorities'];
                    }
                }
            }
        }

        //object_type=0/用户继承所在组的授权检测
        $group = $res
            ->table('tb_users_ugrps')
            ->field('group_id')
            ->where("user_id='{$user_id}'")
            ->select();
        if(!empty($group)){
            foreach($group as $k => $v){
                $object_id = $v['group_id'];
                $group_auth = $res
                    ->table('tb_user_authority')
                    ->field('module_id,authorities')
                    ->where("object_id='{$object_id}' and object_type=0")
                    ->select();
                if(!empty($group_auth)){
                    foreach($group_auth as $key => $value){
                        if($value['module_id']==1){
                            if($check_auth['user'] < $value['authorities']){
                                $check_auth['user'] = $value['authorities'];
                            }
                        }
                        if($value['module_id']==2){
                            if($check_auth['proj'] < $value['authorities']){
                                $check_auth['proj'] = $value['authorities'];
                            }
                        }
                        if($value['module_id']==3){
                            if($check_auth['auth'] < $value['authorities']){
                                $check_auth['auth'] = $value['authorities'];
                            }
                        }
                    }
                }

            }
        }
        $_SESSION['check_auth'] = $check_auth;

		//记录登录次数和最后登录时间
		$count = $res
            ->table('tb_users')
            ->field('login_count')
            ->where("username='{$username}'")
            ->find();
        $data = array(
            'login_count' => $count['login_count'] + 1,
         );
        $res
            ->table('tb_users')
            ->where("username='{$username}'")
            ->save($data);

		alert('null','index.php');
	}else{
		alert('用户名或者密码错误，请重新登录',$url='');
	}
}


include_once ('view/login.php');