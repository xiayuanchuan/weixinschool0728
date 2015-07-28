<?php

define("APPID", "wx52ccadef50ddd909");
define("SECRET", "e42a84503fea938d98fdc03138ce5f33");
$filename = "./accessToken_value.txt";
if (file_exists($filename)) {
    $access_token_json = file_get_contents($filename);
} else {
    $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=" . APPID . "&secret=" . SECRET;
    $access_token_json = file_get_contents($url);
    file_put_contents($filename,$access_token_json);
}
$access_token_arr = json_decode($access_token_json, true);
if (isset($access_token_arr['errmsg']) && $access_token_arr['errmsg'] != "") {
    //错误   根据errcode的值来做相应的处理
}
$access_token=$access_token_arr['access_token'];
var_dump($access_token_arr);
?>