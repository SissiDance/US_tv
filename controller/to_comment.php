<?php
/**
 * Created by PhpStorm.
 * User: 冰淇淋
 * Date: 2020/7/29 0029
 * Time: 下午 7:23
 * Use ：评论
 */
require_once './connet.php';

$content = isset($_REQUEST['content']) ? $_REQUEST['content'] :'';      //评论内容
$id = $_REQUEST['id'];          //美剧id
$userInfo = isset($_COOKIE['userInfo']) ? $_COOKIE['userInfo']:'';      //登录的用户信息
$is_login = empty($userInfo) ? 0 : 1;                                   //判断是否已经登录

if (!empty($content)){
    if (!$is_login){
        echo -1;
        return ;
    }
    $userInfo = json_decode($userInfo,true);
    $account = $userInfo['ACCOUNT'];
    $nickname = $userInfo['USER_NAME'];

    $sql  ="insert into user_details(USER_ACCOUNT,USER_NAME,COMMENT,COMMENTED_SERIES_ID) values ('$account','$nickname','$content',$id)";
    $res = mysqli_query($con,$sql);
    if ($res){
        echo 1;
    }else{
        echo 0;
    }
}