<?php
// AddressPredict.php
//
require_once('../main.php');
if ((!empty($_GET['qgss']) && preg_match("/^[A-Za-z0-9 ]{1,}$/i",$_GET['qgss'])) 
        || (!empty($_POST['qgss']) && preg_match("/^[A-Za-z0-9 ]{1,}$/i",$_POST['qgss']))){
    if (!empty($_POST['qgss'])){
        $predict_q = $_POST['qgss']; 
    }
    elseif (!empty($_GET['qgss'])){
        $predict_q = $_GET['qgss']; 
    }
    $output = file_get_contents("http://www.yellowpages.com/proxy/predict/searchenhancers/locationprediction?q=".$predict_q."&g=".$predict_q."&ss=".$predict_q);
    //$output = $CurlParser::get_curl_data($url,$proxy_ip,$proxy_port,$proxy_loginpassw);
    //echo "hello";
    $output = strstr($output, "[" );
    $output = strstr($output, "]", TRUE)."]";
    echo $output;
}
else{
    echo "empty request";
}

//[{"suggestion":"Sigma Medical Group"},{"suggestion":"salvage yards"},{"suggestion":"Shooting Range"},{"suggestion":"Seafood Restaurants"},{"suggestion":"Shoe Stores"},{"suggestion":"Social Security Office"},{"suggestion":"Smoke Shop"},{"suggestion":"Sporting Goods"},{"suggestion":"star tribune obituaries"},{"suggestion":"Self Storage"}]

?>