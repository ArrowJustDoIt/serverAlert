<?php
    // +----------------------------------------------------------------------
    // | serverAlert 微信入口文件
    // +----------------------------------------------------------------------
    require_once("./config.php");
    define("TOKEN", $config['TOKEN']);

    if (!isset($_GET['echostr'])) {
        $weChatInit->responseMsg();
    }else{
        $weChatInit->valid();
    }