<?php
// proxy_curl_loader.php

require_once( dirname(__FILE__) . '/main.php' );
set_time_limit(0);
$db = DB1::getInstance();
$YpMysqli = new YpMysqli();
$content = CurlParser::get_curl_data("http://spys.ru/proxylist/","","",5,"");
$matches = "";
        preg_match_all("/;[a-z0-9]{6}=[0-9]{1}/i", $content, $matches);
        foreach( $matches["0"] as $key => $value){            
            $value = strstr($value, "=",true);
            $code_arr[] = trim($value, ";");
        }
        $digits_arr = array("0","1","2","3","4","5","6","7","8","9",);
        $content = str_replace($code_arr,$digits_arr,$content);
        $content = str_replace('+(',"",$content);
        $content = preg_replace("/\^[a-z0-9]{4}\)/","", $content);

$content = str_replace('<script type="text/javascript">document.write("<font class=spy2>:<\/font>"',":",$content);

if ($YpMysqli->insert_proxy($db,$content)){
    echo "[".date('Y-m-d H:i:s')."] Proxylist loaded \n";
}

?>

