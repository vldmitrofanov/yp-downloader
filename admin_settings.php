<?php

session_start();
require_once 'main.php';
$db = DB1::getInstance();
$YpMysqli = new YpMysqli();
//$db2 = DB2::getInstance();
function local_exit(){
    global $db;
    echo "</div>";
    cabinet_content_bottom();
    cabinet_sidebar();
    cabinet_footer();
    $db->close();
    exit();
}
if ( empty($_SESSION['level']) || $_SESSION['level'] < 10 ){
    header ('Location: login.php', true, 302);
    exit();
}
cabinet_html_head("YP GRABBER Dashboard","Yellow Pages","YP GRABBER Admin's settings","");
cabinet_header("show_YP_predict_search");
cabinet_content_top();
echo"<div id=\"changeAdminSettings\">";
if (!empty($_GET['action'])){
    if (($_GET['action'] = "change") &&( !empty($_GET['id']) && preg_match("/^[0-9]{1,}$/", $_GET['id'])) ){
        if ( $settings_data_ID_arr = $YpMysqli->GetAdminYPsettingsArr_byID($db,$_GET['id'])){
            echo "<dev id=\"subchange\">";
            echo "<form method=\"post\" action=\"admin_settings.php?action=update\">";
            echo "<table>";
            foreach($settings_data_ID_arr as $row){
                echo "<tr><td colspan=\"2\"><p><i>Change this only if you really know what you are doing</i></p></td>";
                echo "</tr><tr>";
                echo "<th>Setting name: </th><td align=\"right\"><h4>".$row['settings']."</h4></td>";
                echo "</tr><tr>";
                echo "<th>Short Description:</th>
                    <td align=\"right\"> <input type=\'text\" name=\"short_description\" value=\"".$row['short_description']."\"></td></tr>";
                echo "<tr>";
                echo "<th>VALUE: </th>
                    <td align=\"right\"><input type=\'text\" name=\"value\" value=\"".$row['value']."\"></td></tr>";
                echo "<tr><td colspan=\"2\">";
                echo "Long Description:</td></tr><tr><td colspan=\"2\" align=\"right\"> 
                    <textarea name=\"long_description\">".$row['long_description']."</textarea></td></tr>";
                echo "<input type=\"hidden\" name=\"set_id\" value=\"".$row['set_id']."\">";
            }
            echo "</table>";
            echo "<input type=\"submit\" value=\"Save Changes\"></form>";
            echo "<br /><a href=\"admin_settings.php\">Go back to settings page</a>";
        }
        echo "</dev>";
        local_exit();
    }
    elseif(($_GET['action'] = "update") &&( !empty($_POST['set_id']) 
            && preg_match("/^[0-9]{1,}$/", $_POST['set_id'])) ){
        $settings = array();
        if (empty($_POST['value'])){
            exit("Value can't be empty!");
        }
        else{
            $settings['value'] = trim($_POST['value']);
        }
        $settings['set_id'] = $_POST['set_id'];
        if (!empty($_POST['short_description'])){
              $settings['short_description'] = trim(htmlspecialchars($_POST['short_description']));
        }  
        else{
            $settings['short_description'] = '';
        }
        if (!empty($_POST['long_description'])){
              $settings['long_description'] = trim(htmlspecialchars($_POST['long_description']));
        }  
        else{
            $settings['long_description'] = '';
        }
        if($YpMysqli->setAdminYPsettingsArr($db,$settings)){
            echo "Updated!<br />";
            echo "<a href=\"admin_settings.php\">Go back to settings page</a>";
            local_exit();
        }
        else{
            exit("<font color=\"red\">there some error has been faced. Data not updated</font>");
        }
    }
}
$admin_settings_array = $YpMysqli->GetAdminYPsettingsArr($db,$_SESSION['user']);
//print_r($admin_settings_array);
if (empty($admin_settings_array) || !is_array($admin_settings_array)){
    exit("No any settings found.");
}
echo "<table id=\"changeAdminSettingsT\">";
echo "<tr><th>Settings name</th><th>Value</th><th>Description</th><th>Change</th></tr>";
foreach($admin_settings_array as $row){
    echo "<tr>";
    echo "<td>".$row['short_description']."</td>
        <td>".$row['value']."</td>";
    echo "<td>".$row['long_description']."</td>";
    echo "<td><a href=\"admin_settings.php?action=change&id=".$row['set_id']."\">Change</a></td>";
    echo "</tr>";
}
echo "</table>";
local_exit();
?>
