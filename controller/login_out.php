<?php 
$is_success = setcookie("userInfo",'', time() - 3600,'/');  
echo $is_success;  