<?php
// AddCityUSA.php
session_start();
require_once('../main.php');
if ( $_SESSION['level'] >= 10 ){
    $db = DB1::getInstance();
    $CityStateUSA = new CityStateUSA();
    if ( empty($_POST['add_city']) || empty($_POST['stateAdd'])){
        echo "Data is not complete. Aborted";
        exit;
    }
    $AddCity = mysql_real_escape_string($_POST['add_city']);
    $State = mysql_real_escape_string($_POST['stateAdd']);
    $addcity = $CityStateUSA->AddCity($db,$AddCity,$State,0);
    if ( $addcity ){
        echo "<br /> Complete! added by ".$_SESSION['user']."<br />";
    }
}
else{
    echo "You have no rights to add city";
}
$db->close();
?>