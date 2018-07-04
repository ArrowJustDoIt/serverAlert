<?php
    // +----------------------------------------------------------------------
    // | serverAlert 入口文件
    // +----------------------------------------------------------------------
    define("TOKEN", "weixin");

    require_once("./class/weChatInit.class.php");
    require_once("./class/weChatCore.class.php");
    require_once("./class/mysql.class.php");
    require_once("./config.php");

    $mySql      = new mysql($config['database']);
    $weCore     = new weChatCore($config);
    $weChatInit = new wechatCallbackapi($weCore,$mySql);
//    $weCore->sendTempMsg($config['openid'],$content = "192.168.1.93内存占用超过90%!");
    if (!isset($_GET['echostr'])) {
        $weChatInit->responseMsg();
    }else{
        $weChatInit->valid();
    }