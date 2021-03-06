<?php
// multiselect_request.php
session_start();
require_once 'main.php';
$db = DB1::getInstance();
$CityStateUSA = new CityStateUSA();
$IndustryUSA = new IndustryUSA();

if ( empty($_SESSION['level']) || $_SESSION['level'] < 4 ){
    header ('Location: login.php', true, 302);
    exit();
}
cabinet_html_head("YP GRABBER Dashboard","Yellow Pages","YP GRABBER Dashboard","");
cabinet_header("");
cabinet_content_top();
//print_r($_POST);
if(empty($_POST['state'])&&empty($_GET['state'])){
?>
STEP 1: <h3>Select State</h3>
<form method="post">
    <div class="styled-select">
   <select id="state" name="state" onchange="this.form.submit()">
            <option>Select State</option>
<?php
$statelist = $CityStateUSA->GetAllStates2list($db);
    if ($statelist){
        foreach($statelist as $row){
            echo "<option value='".$row['id']."'>".$row['state_city_title']."</option>";
        }
     } 
     
?>
    </select>
    </div>
</form>
<?php
}

elseif (@is_numeric($_POST['state'])||@is_numeric($_GET['state'])){
    if ( empty($_POST['state'])&& !empty($_GET['state'])){
        $_POST['state'] = $_GET['state'];
    }
    if ( empty($_POST['city'])&& empty($_GET['city'])){
        $cityList = $CityStateUSA->GetAllCities_byStateID($db,$_POST['state']);
        if ($cityList){
            echo "STEP 2: <h3>Select Cities</h3>";
            echo "<script language=\"JavaScript\">
                function toggle(source) {
                checkboxes = document.getElementsByName('city[]');
                for(var i=0, n=checkboxes.length;i<n;i++) {
                checkboxes[i].checked = source.checked;
                 }
               }
               </script>";
            echo "<form method=\"post\">";
            foreach($cityList as $row){
                echo "<input type=\"checkbox\" name=\"city[]\" value=\"".$row['id']."\"/> ".$row['state_city_title']." ";  
                //echo "<option value='".$row['id']."'>".$row['state_city_title']."</option>";
                }            
            echo "<br/>-------------<br/>";
            echo "<input type=\"checkbox\" onClick=\"toggle(this)\" /> <b>Toggle All</b> <br/>";
            echo "<input type=\"hidden\" name=\"state\" value=\"".$_POST['state']."\">";
            echo "<input type=\"submit\" id=\"passubmit\" value=\"Submit\">";
            echo "</form>";
        }
         else{
            exit ("there no any city yet<br /><br /><input type=\"button\" value=\"Back\" onclick=\"goBack()\">");
            }
        }
     else{
           // print_r($_POST['state']);
            //echo "<br />";
            //print_r($_POST['city']);
            $checked_city = $_POST['city'];
            for($i=0; $i < count($checked_city); $i++){
                $checked_city[$i] = $checked_city[$i] . "-" . $_POST['state'];
            }
            //print_r($checked_city);
            $IndList = $IndustryUSA->GetIndustryListFromAreaArray_notInQueue($db,$checked_city);
            if ($IndList){
                $serialize_post_city = serialize($_POST['city']);
                //echo $serialize_post_city;
            echo "STEP 3:<h3>Select Industry</h3>";
            echo "<script language=\"JavaScript\">
                function toggle(source) {
                checkboxes = document.getElementsByName('industry[]');
                for(var i=0, n=checkboxes.length;i<n;i++) {
                checkboxes[i].checked = source.checked;
                 }
               }
               </script>";
            echo "<form method=\"post\" action=\"multiselect_query.php\">";
            foreach($IndList as $row){
                echo "<input type=\"checkbox\" name=\"industry[]\" value=\"".$row['ind_id']."\"/> ".$row['Industry_title']."  ";  
                }
            echo "<br/>-------------<br/>";
            echo "<input type=\"checkbox\" onClick=\"toggle(this)\" /> <b>Toggle All</b> <br/>";
            echo "<input type=\"hidden\" name=\"state\" value=\"".$_POST['state']."\">";
            echo "<input type=\"hidden\" name=\"city\" value=\'".base64_encode($serialize_post_city)."\'>";
            echo "<input type=\"submit\" id=\"passubmit\" value=\"Submit\">";
            echo "</form>";
            }
            else{
                exit ("<br /> There no any industry yet. <a href=\"dashboard.php\">Go to Dashboard page</a>");
            }
      }
    }
cabinet_content_bottom();
cabinet_sidebar();
cabinet_footer();
$db->close();
?>
