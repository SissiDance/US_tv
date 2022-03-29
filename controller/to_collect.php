<?php
/**
 * Created by PhpStorm.
 * User: 冰淇淋
 * Date: 2020/7/29 0029
 * Time: 下午 7:39
 * Use ：收藏
 */
require_once './connet.php';
$is_collect = $_REQUEST['is_collect'];
$id = $_REQUEST['id'];
$userInfo = isset($_COOKIE['userInfo']) ? $_COOKIE['userInfo']:'';      //登录的用户信息
$is_login = empty($userInfo) ? 0 : 1;                                   //判断是否已经登录

if (!$is_login){
    echo -1;
    return ;
}
$userInfo = json_decode($userInfo,true);
$account = $userInfo['ACCOUNT'];
$nickname = $userInfo['USER_NAME'];
if ($is_collect){
    $sql  ="delete from user_collection where USER_ACCOUNT = '$account' and COLLECTED_SERIES_ID = $id";
}else{
    $sql  ="insert into user_collection(USER_ACCOUNT,COLLECTED_SERIES_ID) values ('$account',$id)";
}
$res = mysqli_query($con,$sql);
echo $res;

