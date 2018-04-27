<?php 
var_dump(uniqid());
$filename = 'Uploads/user/'.time().".png";
$url = 'https://wx.qlogo.cn/mmopen/vi_32/Q0j4TwGTfTK5Xoiaq1LibgU3w47kY3sKlc7TzUrKpQmbH1qLRfZrPjlRp7pkF0M8ClcVKd81TVKCmph1icJSV7BcA/96';
echo http_down($url,$filename,60);

        function http_down($url, $filename, $timeout = 60) {
            $path = dirname($filename);
            if (!is_dir($path) && !mkdir($path, 0755, true)) {
                return false;
            }
            $fp = fopen($filename, 'w');
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_FILE, $fp);
            curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false); 
            curl_exec($ch);
            curl_close($ch);
            fclose($fp);
            return $filename;
        }
 ?>

 