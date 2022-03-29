<?php
require_once './controller/connet.php';

$userInfo = isset($_COOKIE['userInfo']) ? $_COOKIE['userInfo']:0;
if (empty($userInfo)){
    echo "<script>alert('请先登录');window.location.href = './login.html'</script>";
}
$userInfo = json_decode($userInfo,true);
//拿到评论
$sql  ="select a.*,b.TV_NAME_CH from user_details as a,us_tv as b where a.USER_ACCOUNT ='".$userInfo['ACCOUNT']."'and a.COMMENTED_SERIES_ID = b.ID";
$res = mysqli_query($con,$sql);
$comment = [];
while ($row = mysqli_fetch_assoc($res)){
    $comment[] = $row;
}

//拿到收藏
$sql  ="select a.USER_ACCOUNT,a.COLLECTED_SERIES_ID,b.ID,b.TV_NAME_CH,b.TV_POSTER from user_collection as a,us_tv as b where a.USER_ACCOUNT ='".$userInfo['ACCOUNT']."'and a.COLLECTED_SERIES_ID = b.ID";
$res = mysqli_query($con,$sql);
$collection = [];
while ($row = mysqli_fetch_assoc($res)){
    $collection[] = $row;
}
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>LOVE 美剧</title>
    <link rel="icon" href="./img/mei.ico" type="image/x-ico">
    <link rel="stylesheet" type="text/css" href="./static/jqui/smoothness/jquery-ui.min.css">
    <link href="http://cdn.bootcss.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="./static/css/tv.css">
    <link rel="stylesheet" type="text/css" href="./static/css/user.css">
    <script src="./static/js/index.js"></script>
    <script src="./static/js/jquery.js"></script>
    <script src="./static/jqui/jquery-ui.min.js"></script>
</head>

<body>
<style>
    .on{
        color: #87CEFA !important;
    }
</style>
<div id="navi">

    <div id="navi1">
        <img width="150px" src="img/title.png">
    </div>

    <div id="navi2">
        <ul>
            <li><a href="./index.php?flag=1">最近更新</a></li>
            <li><a href="./index.php?flag=2">最热放映</a></li>
            <li><a href="./index.php?flag=3">最受好评</a></li>
            <li><a class="on" href="./user.php">个人中心</a></li>
        </ul>
    </div>

    <div id="navi3">
        <div class="search bar">
            <form method="get" action="./index.php">
                <input type="text" name="search" placeholder="请输入您要搜索的内容...">
                <button type="submit"></button>
            </form>
        </div>

    </div>
    <div id="navi4" onclick="login_out()"><a href="#" id="log_out">退出</a></div>

<!--    <div id="navi4">-->
<!--        <a id="log" href="user.html">个人中心</a>-->
<!--    </div>-->

</div>

<div id="detail">

    <div id="heart_user">
        <div style="display: inline-block;">
            <p id="like_user">&#10084;</p>
        </div>

        <div style="display: inline-block; height: 60px;">
            <h2>我的收藏</h2>
        </div>
    </div>

    <div id="mine">
        <?php foreach ($collection as $k=>$v) {?>
        <div id="mine0" class="ad">
            <div>
                <a href="./tv_detail.php?id=<?php echo $v['ID'] ?>">
                    <img id="p_user" class="c" src="<?php echo $v['TV_POSTER']; ?>">
                </a>
                <br>
                <a href="./tv_detail.php?id=<?php echo $v['ID'] ?>" id="title_user"><?php echo $v['TV_NAME_CH'] ?></a>
            </div>
        </div>
        <?php } ?>
    </div>

    <script>
    function login_out(){
        $.ajax({
            url:'./controller/login_out.php',
            type:'post',
            data:{},
            success:function(res){
                if(res == 1){
                    alert('退出成功');
                    window.location.href = './login.html';
                }else{
                    alert('退出失败'); 
                }
            }
        })
    }
        
    </script>

    <div id="comment">
        <div style="display: inline-block;">
            <p id="comment_user">&#9749;</p>
        </div>

        <div style="display: inline-block; height: 60px;">
            <h2>我的评论</h2>
        </div>
        <?php foreach ($comment as $k=>$v){ ?>
        <div id="user_comment"><span id="user1" style="color: #87CEFA;"><?php echo $v['TV_NAME_CH'] ?></span>:<?php echo $v['COMMENT'] ?></div>
        <?php } ?>
    </div>
</div>



</body>

</html>