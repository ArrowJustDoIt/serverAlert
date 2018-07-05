<?php
// +----------------------------------------------------------------------
// | serverAlert 微信callbackapi
// +----------------------------------------------------------------------
class wechatCallbackapi{
    public $conn;
    public $weCore;
    public function __construct($weCore,$mySql){
        $this->conn = $mySql;
        $this->weCore = $weCore;
    }

    public function valid(){
        $echoStr = $_GET["echostr"];
        if ($this->checkSignature()) {
            echo $echoStr;
            exit;
        }
    }

    private function checkSignature(){
        $signature = $_GET["signature"];
        $timestamp = $_GET["timestamp"];
        $nonce = $_GET["nonce"];
        $token = TOKEN;
        $tmpArr = array($token, $timestamp, $nonce);
        sort($tmpArr, SORT_STRING);
        $tmpStr = implode($tmpArr);
        $tmpStr = sha1($tmpStr);

        if ($tmpStr == $signature) {
            return true;
        } else {
            return false;
        }
    }

    public function responseMsg(){
        $postStr = $GLOBALS["HTTP_RAW_POST_DATA"];
        if (!empty($postStr)) {
            $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
            $RX_TYPE = trim($postObj->MsgType);

            switch ($RX_TYPE) {
                case "event":
                    $result = $this->receiveEvent($postObj);
                    break;
                case "text":
                    $result = $this->receiveText($postObj);
                    break;
                default:
                    $result = "unknow msg type: " . $RX_TYPE;
                    break;
            }
            echo $result;
        } else {
            echo "";
            exit;
        }
    }

    private function receiveEvent($object){
        $content = "";
        switch ($object->Event)
        {
            case "subscribe":
                //扫描场景二维码进入
                if ($object->EventKey == 'qrscene_123'){
                    //插入数据
                    $arr = array(
                        'openid'        => $object->FromUserName,
                        'create_time'   => time(),
                    );
                    $res = $this->conn->insert('serverAlert',$arr);
                    if($res){
                        //入库成功
                        $content = "微信绑定成功!";
                    }else{
                        //入库失败
                        $content = "微信绑定失败,请取消关注后重试或联系管理员!";
                    }
                }
                break;
            case "unsubscribe":
                //删除绑定
                $this->conn->del("serverAlert","openid='" . $object->FromUserName . "'");
                $content = "已解绑";
                break;
            case "SCAN":
                $sql = "select * from serverAlert where openid='" . $object->FromUserName . "'";
                $openidData = $this->conn->getOne($sql);
                if($openidData){
                    //已经绑定成功
                    $content = "微信已绑定成功!";
                }else{
                    //需要重新绑定 插入数据
                    $arr = array(
                        'openid'        => $object->FromUserName,
                        'create_time'   => time(),
                    );
                    $res = $this->conn->insert('serverAlert',$arr);
                    if($res){
                        //入库成功
                        $content = "微信绑定成功!";
                    }else{
                        //入库失败
                        $content = "微信绑定失败,请取消关注后重试或联系管理员!";
                    }
                }
                break;
            default:
                $content = "receive a new event: ".$object->Event;
                break;
        }
        $result = $this->transmitText($object, $content);
        return $result;
    }

    private function receiveText($object){
        $keyword = trim($object->Content);
        switch ($keyword) {
            case "1":
                $sql = "select * from serverAlert";
                $all_res = $this->conn->getAll($sql);
                $serverAlert = "192.168.1.93服务器内存占用超过90%!";
                foreach ($all_res as $k => $v){
                    $this->weCore->sendTempMsg($v['openid'],$serverAlert);
                }
                break;
            case "2":
                $content = $this->weCore->getAccessToken();
                break;
            default:
                $content = "当前时间：" . date("Y-m-d H:i:s", time());
                break;
        }
        if($content){
            $result = $this->transmitText($object, $content);
            return $result;
        }
    }

    private function transmitText($object, $content){
        $textTpl = "<xml>
<ToUserName><![CDATA[%s]]></ToUserName>
<FromUserName><![CDATA[%s]]></FromUserName>
<CreateTime>%s</CreateTime>
<MsgType><![CDATA[text]]></MsgType>
<Content><![CDATA[%s]]></Content>
</xml>";
        $result = sprintf($textTpl, $object->FromUserName, $object->ToUserName, time(), $content);
        return $result;
    }

}