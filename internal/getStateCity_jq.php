<?php
//getStateCity_jq.php

require_once('../main.php');
$db = DB1::getInstance();

if($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest') {
    if ( isset($_POST['idcategory']) ){
        $idcategory = htmlspecialchars($_POST['idcategory']);
        //$idcategory = '';
    }
    elseif ( isset($_GET['idcategory']) ){
        $idcategory = htmlspecialchars($_GET['idcategory']);
    }
    //$var = $YPmysql_aj->jquery_GetStateCity($idcategory);
    $YpMysqli_getStateCity = new YpMysqli();
    $var = $YpMysqli_getStateCity->jquery_GetStateCity($db,$idcategory);
    print $var;
}
$db->close();
?>
