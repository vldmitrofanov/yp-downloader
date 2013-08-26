<?php
//getIndustry_jq.php

require_once('../main.php');
$db = DB1::getInstance();

if($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest') {
    if ( isset($_POST['region']) ){
        $region = htmlspecialchars($_POST['region']);
    }
    elseif ( isset($_GET['region']) ){
        $region = htmlspecialchars($_GET['region']);
    }
    $YpMysqli_getIndustry = new YpMysqli();
    $var = $YpMysqli_getIndustry->jquery_GetIndustry($db,$region);
    print $var;
}
$db->close();
?>
