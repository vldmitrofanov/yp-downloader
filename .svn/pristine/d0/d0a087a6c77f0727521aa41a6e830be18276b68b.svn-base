<?php
session_start();
require_once('main.php');
if ( empty($_SESSION['level']) || $_SESSION['level'] < 4 ){
    header ('Location: login.php', true, 302);
    exit();
}
if ( $_SESSION['level'] >= 4 ){
    cabinet_html_head("YP GRABBER proxy loader","Yellow Pages","YP GRABBER proxy loader","");
    cabinet_header("");
    echo "<div id=\"phpinfo\">";
    phpinfo();
    echo "</div>";
}
?>
