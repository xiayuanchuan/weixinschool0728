<?php

/**
 * wechat php test
 */
//define your token
define("TOKEN", "weixin");
// $weixinData=array();
$wechatObj = new wechatCallbackapiTest();
$wechatObj->valid();

class wechatCallbackapiTest {

    public function valid() {
        $echoStr = $_GET["echostr"];

        //valid signature , option
        if ($this->checkSignature()) {
            echo $echoStr;


            file_put_contents("./wx_sam.txt", $echoStr);
            $this->responseMsg();
//            file_put_contents("./wx_samresponseMsg.txt", $echoStr);


            exit;
        }
    }

    public function responseMsg() {
        $_SESSION['content'] = $fromUsername;
        //get post data, May be due to the different environments
        $postStr = $GLOBALS["HTTP_RAW_POST_DATA"];

        file_put_contents("./wx_samresponseMsg.txt", json_encode($GLOBALS));
        file_put_contents("./wx_samresponseMsgpostStr.txt", json_encode($postStr));

        //extract post data
        if (!empty($postStr)) {
            /* libxml_disable_entity_loader is to prevent XML eXternal Entity Injection,
              the best way is to check the validity of xml by yourself */
            libxml_disable_entity_loader(true);
            $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
            $fromUsername = $postObj->FromUserName;
            $toUsername = $postObj->ToUserName;
            $keyword = trim($postObj->Content);
            $MsgType = trim($postObj->MsgType);
            $time = time();
            $textTpl = "<xml>
							<ToUserName><![CDATA[%s]]></ToUserName>
							<FromUserName><![CDATA[%s]]></FromUserName>
							<CreateTime>%s</CreateTime>
							<MsgType><![CDATA[%s]]></MsgType>
							<Content><![CDATA[%s]]></Content>
							<FuncFlag>0</FuncFlag>
							</xml>";
            if (!empty($keyword)) {
                //最好是用$MsgType来判断， f否则有可能无法处理用户的其他输入
                if($keyword=="摇一摇"){
                    //发送图文消息
                    $textTpl = "<xml>
                            <ToUserName><![CDATA[%s]]></ToUserName>
                            <FromUserName><![CDATA[%s]]></FromUserName>
                            <CreateTime>%s</CreateTime>
                            <MsgType><![CDATA[%s]]></MsgType>
                            <ArticleCount>1</ArticleCount>
                            <Articles>
                            <item>
                            <Title><![CDATA[%s]]></Title> 
                            <Description><![CDATA[%s]]></Description>
                            <PicUrl><![CDATA[%s]]></PicUrl>
                            <Url><![CDATA[%s]]></Url>
                            </item>
                            </Articles>
                            </xml> ";
                    $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, "news", "摇一摇","拿起你的手机一起来摇一摇","http://mp.weixin.qq.com/wiki/static/assets/ac9be2eafdeb95d50b28fa7cd75bb499.png","http://www.baidu.com");
                    echo $resultStr;
                    exit;
                }
                $msgType = "text";
                $contentStr = "Welcome to wechat world!您的输入类型为：" . $MsgType . $keyword . $_SESSION['content'] . "---" . $fromUsername;
            } else {
                $contentStr = "Welcome to wechat world!您的输入类型为：" . $MsgType . $keyword . $_SESSION['content'];
            }
            $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
            echo $resultStr;
        } else {
            echo "";
            exit;
        }
    }

    private function checkSignature() {
        // you must define TOKEN by yourself
        if (!defined("TOKEN")) {
            throw new Exception('TOKEN is not defined!');
        }

        $signature = $_GET["signature"];
        $timestamp = $_GET["timestamp"];
        $nonce = $_GET["nonce"];

        $token = TOKEN;
        $tmpArr = array($token, $timestamp, $nonce);
        // use SORT_STRING rule
        sort($tmpArr, SORT_STRING);
        $tmpStr = implode($tmpArr);
        $tmpStr = sha1($tmpStr);

        if ($tmpStr == $signature) {
            return true;
        } else {
            return false;
        }
    }

}

?>