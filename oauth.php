<?php
//授权域名
// define("TOKEN", "heart");
// $wechatObj = new wechatCallbackapiTest();
// $wechatObj->valid();

// class wechatCallbackapiTest
// {
//   public function valid()
//     {
//         $echoStr = $_GET["echostr"];

//         //valid signature , option
//         if($this->checkSignature()){
//           echo $echoStr;
//           exit;
//         }
//     }

//     public function responseMsg()
//     {
//     //get post data, May be due to the different environments
//     $postStr = $GLOBALS["HTTP_RAW_POST_DATA"];

//         //extract post data
//     if (!empty($postStr)){
                
//                 $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
//                 $fromUsername = $postObj->FromUserName;
//                 $toUsername = $postObj->ToUserName;
//                 $keyword = trim($postObj->Content);
//                 $time = time();
//                 $textTpl = "<xml>
//               <ToUserName><![CDATA[%s]]></ToUserName>
//               <FromUserName><![CDATA[%s]]></FromUserName>
//               <CreateTime>%s</CreateTime>
//               <MsgType><![CDATA[%s]]></MsgType>
//               <Content><![CDATA[%s]]></Content>
//               <FuncFlag>0</FuncFlag>
//               </xml>";             
//         if(!empty( $keyword ))
//                 {
//                   $msgType = "text";
//                   $contentStr = "Welcome to wechat world!";
//                   $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
//                   echo $resultStr;
//                 }else{
//                   echo "Input something...";
//                 }

//         }else {
//           echo "";
//           exit;
//         }
//     }
    
//   private function checkSignature()
//   {
//         $signature = $_GET["signature"];
//         $timestamp = $_GET["timestamp"];
//         $nonce = $_GET["nonce"];  
            
//     $token = TOKEN;
//     $tmpArr = array($token, $timestamp, $nonce);
//     sort($tmpArr);
//     $tmpStr = implode( $tmpArr );
//     $tmpStr = sha1( $tmpStr );
    
//     if( $tmpStr == $signature ){
//       return true;
//     }else{
//       return false;
//     }
//   }
// }









$code = $_GET['code'];
$state = $_GET['state'];
//换成自己的接口信息
$appid = 'wx22d6c4e5154c99e0';
$appsecret = 'af96f169beb932430c7150903313e61a';

if (emptyempty($code)) $this->error('授权失败');
$token_url = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid='.$appid.'&secret='.$appsecret.'&code='.$code.'&grant_type=authorization_code';
$token = json_decode(file_get_contents($token_url));
if (isset($token->errcode)) {
 echo '<h1>错误：</h1>'.$token->errcode;
 echo '<br/><h2>错误信息：</h2>'.$token->errmsg;
 exit;
}
$access_token_url = 'https://api.weixin.qq.com/sns/oauth2/refresh_token?appid='.$appid.'&grant_type=refresh_token&refresh_token='.$token->refresh_token;
//转成对象
$access_token = json_decode(file_get_contents($access_token_url));
if (isset($access_token->errcode)) {
 echo '<h1>错误：</h1>'.$access_token->errcode;
 echo '<br/><h2>错误信息：</h2>'.$access_token->errmsg;
 exit;
}
$user_info_url = 'https://api.weixin.qq.com/sns/userinfo?access_token='.$access_token->access_token.'&openid='.$access_token->openid.'&lang=zh_CN'; //开源软件:phpfensi.com
//转成对象
$user_info = json_decode(file_get_contents($user_info_url));
if (isset($user_info->errcode)) {
 echo '<h1>错误：</h1>'.$user_info->errcode;
 echo '<br/><h2>错误信息：</h2>'.$user_info->errmsg;
 exit;
}
//打印用户信息
echo '<pre>';
print_r($user_info);
echo '</pre>';
?>



?>