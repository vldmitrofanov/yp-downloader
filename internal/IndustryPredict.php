<?php
// IndustryPredict.php
//
require_once('../main.php');
if ((!empty($_GET['q']) && preg_match("/^[A-Za-z0-9 ]{1,}$/i",$_GET['q'])) 
        || (!empty($_POST['q']) && preg_match("/^[A-Za-z0-9 ]{1,}$/i",$_POST['q']))){
    if (!empty($_POST['q'])){
        $predict_q = $_POST['q']; 
    }
    elseif (!empty($_GET['q'])){
        $predict_q = $_GET['q']; 
    }
    $output = file_get_contents("http://www.yellowpages.com/proxy/geo_aware_predict/autosuggest?q=".$predict_q);
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