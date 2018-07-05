<?php
    // +----------------------------------------------------------------------
    // | serverAlert 配置文件
    // +----------------------------------------------------------------------
    $config = [
        'TOKEN'         =>  'weixin',
        'wxappID'       =>  'wxxxxxxxxxxxxxxxxx',
        'wxappsecret'   =>  'wxxxxxxxxxxxxxxxxx',
        'template_id'   =>  'wxxxxxxxxxxxxxxxxx',
        'database'      =>  [
            'host'      =>  'wxxxxxxxxxxxxxxxxx',
            'port'      =>  '3306',
            'user'      =>  'wxxxxxxxxxxxxxxxxx',
            'pass'      =>  'wxxxxxxxxxxxxxxxxx',
            'db'        =>  'wxxxxxxxxxxxxxxxxx',
            'charset'   =>  'utf8',
        ],
    ];

    require_once("./class/weChatInit.class.php");
    require_once("./class/weChatCore.class.php");
    require_once("./class/mysql.class.php");

    $mySql      = new mysql($config['database']);
    $weCore     = new weChatCore($config);
    $weChatInit = new wechatCallbackapi($weCore,$mySql);