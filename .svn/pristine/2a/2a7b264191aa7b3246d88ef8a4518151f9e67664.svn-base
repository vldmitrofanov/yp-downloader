<?php
session_start();
require_once('main.php');
if ( empty($_SESSION['level']) || $_SESSION['level'] < 4 ){
    header ('Location: login.php', true, 302);
    exit();
}
if ( $_SESSION['level'] >= 4 ){
    $db = DB1::getInstance();
    cabinet_html_head("YP GRABBER proxy loader","Yellow Pages","YP GRABBER proxy loader","");
    cabinet_header("");
    $YpMysqli = new YpMysqli();
    $content = $_POST['proxy'];
    $YpMysqli->insert_proxy($db,$content);
    $db->close();
}
else{
    exit ("you have no rights for this action");
}
?>
