<?php
/**
 * Created by PhpStorm.
 * User: 冰淇淋
 * Date: 2020/7/28 0028
 * Time: 下午 9:13
 * Use ：用户登录
 */
require_once './connet.php';
$account = $_REQUEST['account'];
$password = $_REQUEST['password'];

$sql = "select ID,ACCOUNT,USER_NAME from user where ACCOUNT = $account and PASSWORD = $password";
$res = mysqli_query($con,$sql);
$res = mysqli_fetch_assoc($res);
if ($res){
    $userInfo = json_encode($res);
    setcookie('userInfo',$userInfo,time()+60*60*2,'/');
    echo 1;
}else{
    echo -1;
}

