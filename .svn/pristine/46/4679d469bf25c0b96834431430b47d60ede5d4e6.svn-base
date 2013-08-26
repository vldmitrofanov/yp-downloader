<?php
// AddCityUSA.php
session_start();
require_once('../main.php');
if ( $_SESSION['level'] >= 10 ){
    $db = DB1::getInstance();
    $IndustryUSA = new IndustryUSA();
    if ( empty($_POST['add_industry']) ){
        echo "Data is not complete. Aborted";
        exit;
    }
    $AddInd = mysql_real_escape_string($_POST['add_industry']);
    $addind = $IndustryUSA->AddIndustry($db,$AddInd);
    if ( $addind ){
        echo "<br /> Complete! added by ".$_SESSION['user']."<br />";
    }
}
else{
    echo "You have no rights to add city";
}
$db->close();
?>

