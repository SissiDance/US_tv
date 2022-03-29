<?php
/**
 * Created by PhpStorm.
 * User: 冰淇淋
 * Date: 2020/7/28 0028
 * Time: 上午 12:00
 * Use ：获取美剧列表
 */
require_once './connet.php';
$page = 1;
$limit = 20;
$start = ($page - 1) * $limit;
$sql = "select ID,TV_NAME_CH,TV_POSTER from us_tv limit $start,$limit";
$res = mysqli_query($con,$sql);
$data = [];
while ($row = mysqli_fetch_assoc($res)){
    $data[] = $row;
}
echo json_encode($data,JSON_UNESCAPED_LINE_TERMINATORS);