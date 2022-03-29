<?php
require_once './controller/connet.php';


$flag = isset($_REQUEST['flag']) ? $_REQUEST['flag'] : 1;        //1最近更新 2最热放映 3最受好评
$search = isset($_REQUEST['search']) ? $_REQUEST['search'] : '';          //搜索关键字
$userInfo = isset($_COOKIE['userInfo']) ? $_COOKIE['userInfo'] : '';      //登录的用户信息
$is_login = empty($userInfo) ? 0 : 1;                                   //判断是否已经登录
$page = 1;              //页码
$limit = 95;            //一页的数量
$start = ($page - 1) * $limit;
if ($flag == 1) {
    $sql = "select ID,TV_NAME_CH,TV_POSTER from us_tv where TV_NAME_CH like '%$search%' order by TV_Premiere desc limit $start,$limit";
}
if ($flag == 2) {
    $sql = "select ID,TV_NAME_CH,TV_POSTER from us_tv where TV_NAME_CH like '%$search%'  order by (`CRITIC_RATINGS` + `USER_RATINGS`) desc limit $start,$limit";
}
if ($flag == 3) {
    $sql = "select ID,TV_NAME_CH,TV_POSTER from us_tv where TV_NAME_CH like '%$search%'  order by (0.6 * `TOMATOMETER` + 0.4 * `AUDIENCE_SCORE`) desc limit $start,$limit";
}
$res = mysqli_query($con, $sql);
$data = [];
while ($row = mysqli_fetch_assoc($res)) {
    $data[] = $row;
}

?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>LOVE 美剧</title>
    <link rel="icon" href="img/mei.ico" type="image/x-ico">
    <link rel="stylesheet" type="text/css" href="./static/jqui/smoothness/jquery-ui.min.css">
    <link href="http://cdn.bootcss.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="./static/css/tv.css">
    <script src="./static/js/index.js"></script>
    <script src="./static/js/jquery.js"></script>
    <script src="./static/jqui/jquery-ui.min.js"></script>
</head>

<body>
    <style>
        .on {
            color: #87CEFA !important;
        }
    </style>
    <div id="navi">

        <div id="navi1">
            <img width="150px" src="img/title.png">
        </div>

        <div id="navi2">
            <ul>
                <li><a class="<?php echo $flag == 1 ? 'on' : '' ?>" id="effect1" href="./index.php?flag=1">最近更新</a></li>
                <li><a class="<?php echo $flag == 2 ? 'on' : '' ?>" id="effect2" href="./index.php?flag=2">最热放映</a></li>
                <li><a class="<?php echo $flag == 3 ? 'on' : '' ?>" id="effect3" href="./index.php?flag=3">最受好评</a></li>
                <?php if ($is_login) { ?>
                    <li><a href="./user.php">个人中心</a></li>
                <?php } ?>
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

        <?php if (!$is_login) { ?>
            <div id="navi4">
                <a href="./login.html" id="log">登录</a>
            </div>
        <?php } ?>

    </div>

    <div id="add">
        <?php foreach ($data as $k => $v) { ?>
            <div id="add0" class="ad">
                <div>
                    <a href="./tv_detail.php?id=<?php echo $v['ID'] ?>">
                        <img data-id="1" id="p" class="c" src=" <?php echo $v['TV_POSTER'] ?>">
                    </a>
                    <br>
                    <a href="./tv_detail.php?id=<?php echo $v['ID'] ?>" id="title"><?php echo $v['TV_NAME_CH'] ?></a>
                </div>
            </div>
        <?php } ?>
    </div>
</body>

</html>