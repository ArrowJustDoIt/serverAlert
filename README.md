serverAlert
======
    这货是在服务器发生问题或者是你能想到的任何需要发送模板消息给微信的场景下使用的通过一系列的配置后通过简单的调用即可发送指定content到指定的管理员或者用户了.
使用的时候需要配置好config.php,大概配置和流程如下...
```php
return [
        'TOKEN'         =>  'weixin',
        'wxappID'       =>  'wxxxxxxxxxxxxxxxxx', //appid
        'wxappsecret'   =>  'wxxxxxxxxxxxxxxxxx', //appsecret
        'template_id'   =>  'wxxxxxxxxxxxxxxxxx', //模板id
        'database'      =>  [
            'host'      =>  'wxxxxxxxxxxxxxxxxx',
            'port'      =>  '3306',
            'user'      =>  'wxxxxxxxxxxxxxxxxx',
            'pass'      =>  'wxxxxxxxxxxxxxxxxx',
            'db'        =>  'wxxxxxxxxxxxxxxxxx',
            'charset'   =>  'utf8',
        ],
    ];
```
配置好后,在微信后台服务器配置url填写index.php,token要和config里的token相对应,验证通过后通过访问send.php获取场景二维码
```php
require_once("./config.php");
//获取场景二维码
$weCore->getQrcodeImg();
```
通过二维码关注公众号后自动绑定微信,然后就可以发消息到微信了~
```php
require_once("./config.php");
//发送模板消息
$weCore->sendTempMsg(openid,"192.168.1.93 内存占用超过90%!");
```
