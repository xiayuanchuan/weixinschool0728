<?php

//var_dump($_SERVER);
//var_dump($_REQUEST);
define("APP_PATH", "");
include './Common/config/config.php';
include './Common/mysql/mysql.php';
include './Common/functions/functions.php';

//
//$mydb=new mysql();
//$sql="select * from weixin_user";
//$res=$mydb->execute($sql);
////var_dump($mydb->fetch_array($res));
global $access_token = getAccessToken(1);
