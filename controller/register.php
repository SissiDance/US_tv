<?php
/**
 * Created by PhpStorm.
 * User: 冰淇淋
 * Date: 2020/7/28 0028
 * Time: 下午 9:04
 * Use ：注册用户
 */
require_once './connet.php';
$nickname = $_REQUEST['nickname'];
$account = $_REQUEST['account'];
$password = $_REQUEST['password'];

//判断是否重复
$sql = "select count(*) as num from user where ACCOUNT = $account";
$res = mysqli_query($con,$sql);
$res = mysqli_fetch_assoc($res);
if ($res['num'] == 1){
    echo -1;
    return ;
}


$sql = "insert into user(ACCOUNT,PASSWORD,USER_NAME) values ('$account','$password','$nickname')";

$res = mysqli_query($con,$sql);


if ($res == 1) echo 1;
else echo 0;
