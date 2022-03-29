<?php
/**
 * Created by PhpStorm.
 * User: 冰淇淋
 * Date: 2020/7/28 0028
 * Time: 下午 10:35
 * Use ：美剧详情
 */
require_once './controller/connet.php';
$awards = require_once './controller/awards.php';
$id = $_REQUEST['id'];          //美剧id
$userInfo = isset($_COOKIE['userInfo']) ? $_COOKIE['userInfo']:'';      //登录的用户信息
$is_login = empty($userInfo) ? 0 : 1;                                   //判断是否已经登录
$content = isset($_REQUEST['content']) ? $_REQUEST['content'] :'';      //评论内容

if (!empty($content)){
    if (!$is_login){
        echo "<script>alert('请先登录')</script>";
        return ;
    }
    $userInfo = json_decode($userInfo,true);
    $account = $userInfo['ACCOUNT'];
    $nickname = $userInfo['USER_NAME'];

    $sql  ="insert into user_details(USER_ACCOUNT,USER_NAME,COMMENT,COMMENTED_SERIES_ID) values ('$account','$nickname','$content',$id)";
    $res = mysqli_query($con,$sql);
    if ($res){
        echo "<script>alert('评论成功')</script>";
    }
}
//是否已被收藏
if ($is_login){
    $userInfo = json_decode($userInfo,true);
    $account = $userInfo['ACCOUNT'];
}else{
    $account = -1;
}

$is_collect = 0;
$sql = "select count(*) as num from user_collection where COLLECTED_SERIES_ID = $id and USER_ACCOUNT ='$account'";
$res = mysqli_query($con,$sql);
$res = mysqli_fetch_assoc($res);
if ($res['num']){
    $is_collect = 1;
}

//获取该美剧详情
$sql = "select *from us_tv where ID = $id";
$res = mysqli_query($con,$sql);
$data = mysqli_fetch_assoc($res);

//获取该美剧获奖信息
$sql = "select *from awards where ID = $id";
$res = mysqli_query($con,$sql);
$detail = mysqli_fetch_assoc($res);
$detail = array_filter($detail);

$newArr = [];
$i = 0;
$other = '';
foreach ($detail as $k=>$v){
    if ($k == 'ID' || $k=='TV_NAME_CH' || $k=='TV_NAME_EN') continue;
    $newArr[$i]['english'] = $k;
    if (strstr($k,'CCA') != false){
        $other = '评论家选择奖'.$v;
    }
    if (strstr($k,'GGA') != false){
        $other = '金球奖'.$v;
    }
    if (strstr($k,'EM') != false){
        $other = '艾美奖'.$v; 
    } 
    $newArr[$i]['other'] = $other;
    $newArr[$i]['name'] = '';
    $i++;
}

foreach ($newArr as $k=>$v){
    $newArr[$k]['name'] = $awards[$v['english']];
}

// print_r($newArr);return ;
$data1 = [];
$data2 = [];
$data3 = [];
$data4 = [];
$data5 = [];
$data6 = []; 
foreach ($newArr as $k=>$v){
    $type = $v['other'];
    switch ($type){
        case '金球奖提名':$data1[] = $v['name'];break;
        case '金球奖获奖':$data2[] = $v['name'];break;
        case '评论家选择奖提名':$data3[] = $v['name'];break;
        case '评论家选择奖获奖':$data4[] = $v['name'];break;
        case '艾美奖提名':$data5[] = $v['name'];break; 
        case '艾美奖获奖':$data6[] = $v['name'];break;  
    }
}

//print_r($data1);
//print_r($data2);
//print_r($data3);
//print_r($data4);



//获取该美剧评论
$sql = "select *from user_details where commented_series_id = $id";
$res = mysqli_query($con,$sql);
$comment = [];
while ($row = mysqli_fetch_assoc($res)){
    $comment[] = $row;
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
    <script src="./static/js/index.js"></script>
    <script src="./static/js/jquery.js"></script>
    <script src="./static/jqui/jquery-ui.min.js"></script>
</head>

<body>

<div id="navi">

    <div id="navi1">
        <img width="150px" src="img/title.png">
    </div>

    <div id="navi2">
        <ul>
            <li><a href="./index.php?flag=1">最近更新</a></li>
            <li><a href="./index.php?flag=2">最热放映</a></li>
            <li><a href="./index.php?flag=3">最受好评</a></li>
            <?php if ($is_login){ ?>
                <li><a href="./user.php">个人中心</a></li>
            <?php } ?>
        </ul>
    </div>

    <div id="navi3">

        <div class="search bar">
            <form>
                <input type="text" placeholder="请输入您要搜索的内容...">
                <button type="submit"></button>
            </form>
        </div>

    </div>

    <?php if (!$is_login){ ?>
        <div id="navi4">
            <a href="./login.html" id="log">登录</a>
        </div>
    <?php } ?>
</div>


<div id="detail">
    <div id="detail1">
        <img id="poster" src="<?php echo $data['TV_POSTER'] ?>"><br>
    </div>

    <div id="detail2">
        <p>中文名称：<?php echo $data['TV_NAME_CH'] ?></p>
        <p>英文名称：<?php echo $data['TV_NAME_EN'] ?></p>
        <p>首播时间：<?php echo $data['TV_Premiere'] ?></p>
        <p>出品公司：<?php echo $data['TV_COMPANY'] ?></p>
        <p>专业评分/人数：<?php echo $data['TOMATOMETER'] ?>/<?php echo $data['CRITIC_RATINGS'] ?></p>
        <p>观众评分/人数：<?php echo $data['AUDIENCE_SCORE'] ?>/<?php echo $data['USER_RATINGS'] ?></p>
        <p>获奖情况:</p>
        <p>金球奖提名:
            <?php
            if (empty($data1)) echo '无';
            else{
                foreach ($data1 as $k=>$v){
                    echo $v.'&nbsp&nbsp';
                }
            }

            ?>
        </p>
        <p>金球奖获奖:
            <?php
                if (empty($data2)) echo '无';
                else{
                    foreach ($data2 as $k=>$v){
                        echo $v.'&nbsp&nbsp';
                    }
                }

            ?>
        </p>
        <p>评论家选择奖提名:
            <?php
            if (empty($data3)) echo '无';
            else{
                foreach ($data3 as $k=>$v){
                    echo $v.'&nbsp&nbsp';
                }
            }
            ?>
        </p>
        <p>评论家选择奖获奖:
            <?php
            if (empty($data4)) echo '无';
            else{
                foreach ($data4 as $k=>$v){
                    echo $v.'&nbsp&nbsp';
                }
            }
            ?>
        </p>
        <p>艾美奖提名:
            <?php
            if (empty($data5)) echo '无';
            else{
                foreach ($data5 as $k=>$v){
                    echo $v.'&nbsp&nbsp';
                }
            }
            ?>
        </p>
        <p>艾美奖获奖:
            <?php
            if (empty($data6)) echo '无';
            else{
                foreach ($data6 as $k=>$v){
                    echo $v.'&nbsp&nbsp';
                }
            }
            ?>
        </p>
    </div>

    <div id="heart">
        <a data-collect="<?php echo $is_collect ?>" class="like <?php echo $is_collect ? 'cs':'' ?>">&#10084;</a>
    </div>

    <script>
        $(function () {
            $(".like").click(function () {
                var that = this;
                var is_collect = $(this).hasClass('cs') ? 1 : 0; 
                var id = <?php echo $id ?>;
                var url = './controller/to_collect.php';
                var data = {
                    'is_collect':is_collect,
                    'id':id
                };
                $.ajax({
                    url:url,
                    type:'post',
                    data:data,
                    success:function (res) {
                       if (res == -1){
                           alert('请先登录');
                           window.location.href = './login.html';
                       }
                       if (res == 1 && is_collect == 1){
                           alert('取消收藏成功');
                           $(that).toggleClass('cs');
                       }
                       if (res == 1 && is_collect == 0){
                           alert('收藏成功');
                           $(that).toggleClass('cs');
                       }
                       if (res == 0){
                           alert('操作失败');
                       }
                       return ;
                    }
                })
            })
        })
    </script>
    <input type="hidden" name="id" value="<?php echo $id ?>">
    <div id="comment">

        <h2>评论区</h2>
        <?php foreach ($comment as $k=>$v) {?>
        <div id="user_comment">用户<span id="user1" style="color: #87CEFA;"><?php echo $v['USER_NAME'] ?></span>:<?php echo $v['COMMENT'] ?></div>
        <?php }?>
        <h3>我要评论：</h3>
        <div id="user_comment">
            <textarea id="content" name="content" placeholder="请输入......"></textarea>
        </div>
        <button onclick="to_comment()" id="my_comment"
                style="width: 80px; height: 40px; margin-left: 770px; margin-top: 30px; margin-bottom: 50px;
                font-size: 17px; font-family: 'Microsoft YaHei', 'SimHei'; color: white;
                background-color: black;border-radius: 5px;">提交</button>

    </div>
</div>
<script>
    /**
     * 评论
     * @returns {boolean}
     */
function to_comment() {
    var id = $('input[name="id"]').val();
    var content = $('#content').val();
    var url = './controller/to_comment.php';
    var data = {
        'id':id,
        'content':content,
    };
    $.ajax({
        url:url,
        type:'post',
        data:data,
        success:function (res) {
            if (res == -1){
                alert('评论失败,请先登录');
                window.location.href = './login.html';
            }
            if (res == 1){
                alert('评论成功');
                window.location.reload();
            }
            if (res == 0){
                alert('评论失败');
            }
            return ;
        }
    })

    return false;
}
</script>

</body>

</html>
