<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

function br() {
    echo "<br>";
}

function getAccessToken($wei_id = 1) {

    $mydb = new mysql();
    $sql = "select * from weixin_access_token where delated=0 and wei_id=" . $wei_id;
    $accessTokenArr = $mydb->select($sql);

    if (time() - $accessTokenArr[0]["created"] > 7000 || empty($accessTokenArr[0]['access_token'])) {
        //重新获取token  并存数据库
        $accessTokenArr = getAccessTokenByUrl();
        if (isset($accessTokenArr['access_token'])) {
            $data = array(
                "created" => time(),
                "access_token" => $accessTokenArr['access_token'],
            );
            if ($mydb->update("weixin_access_token", $data, "wei_id = " . $wei_id, false)) {
                return $accessTokenArr['access_token'];
            } else {
                return FALSE;
            }
        } else {
            return false;
        }
    } else {
        return $accessTokenArr[0]['access_token'];
    }
}

function getAccessTokenByUrl() { // php kaiqi openssl扩展
    $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type="
            . "client_credential&appid=" . APPID . "&secret=" . APPSECRET;
    $accessTokenArr = json_decode(file_get_contents($url), true);
    return $accessTokenArr;
}
