<?php
//header('Content-Type: text/html; charset=utf-8');
session_start();
require_once 'main.php';
$db = DB1::getInstance();
$CityStateUSA = new CityStateUSA();
$IndustryUSA = new IndustryUSA();

if ( empty($_SESSION['level']) || $_SESSION['level'] < 4 ){
    header ('Location: login.php', true, 302);
    exit();
}

// if no post data received, send user to multiselect_request.php
if( empty($_POST['state'])|| empty($_POST['industry'])|| empty($_POST['city']) ){
    header ('Location: multiselect_request.php', true, 302);
    exit();
}
if (!preg_match("/^[0-9]{1,2}$/", $_POST['state'])){
    exit("State is not provided or incorrect");
}
cabinet_html_head("YP GRABBER Dashboard","Yellow Pages","YP GRABBER Dashboard","");
cabinet_header("");
cabinet_content_top();
//echo date('Y-m-d H:i:s');

$state_code = strtolower($CityStateUSA->GetStateCode_byID($db,$_POST['state']));
$industry_arr = $IndustryUSA->GetData_from_indIDs_array($db,$_POST['industry']);


// this is quick fix to prevent the error "Notice: unserialize() [function.unserialize]: Error at offset.."
// got from http://stackoverflow.com/questions/10152904/unserialize-function-unserialize-error-at-offset
$data = preg_replace('!s:(\d+):"(.*?)";!e', "'s:'.strlen('$2').':\"$2\";'", base64_decode($_POST['city']));
$cities_ids = (unserialize($data));

$city_arr = $CityStateUSA->GetData_fromIDs_array($db,$cities_ids);

$tmp = "tmp_".date('m_d_H_i')."_";
$table = "YP_US_industry_done";
$yp_url = _YP_WWW_URL_;
if (!is_array($city_arr)|| !is_array($industry_arr)){
    exit("No data received from DataBase");
}
$sql = "CREATE TEMPORARY TABLE `".$tmp.$table."` AS SELECT * FROM `".$table."` WHERE 1=2;\n";
$sql_counter = 0;
foreach ($city_arr as $city_row){
    foreach ($industry_arr as $ind_row){
        $sql .= sprintf("INSERT INTO `".$tmp.$table."`
            (`ind_id_done`,`area_done`,`entry_full_code`,`is_finished`,`enqueued`,`url`)
            VALUES
            ('%s','%s','%s','no','yes','%s');",
                $db->real_escape_string($ind_row['ind_id']),
                $db->real_escape_string($city_row['id']."-".$_POST['state']),
                $db->real_escape_string($city_row['id']."-".$_POST['state']."-".$ind_row['ind_id']),
                $db->real_escape_string($yp_url."/".$city_row['state_city_code']."-".$state_code."/".$ind_row['industry_code']));
        $sql_counter++;
    }
}
if ($sql_counter == 0){
    exit('ERROR: SQL ROWS counter equal 0');
}
$sql .= "INSERT INTO `".$table."` (ind_id_done,area_done,is_finished,enqueued,url)
(SELECT `".$tmp.$table."`.`ind_id_done`,
`".$tmp.$table."`.`area_done`,
`".$tmp.$table."`.`is_finished`,
`".$tmp.$table."`.`enqueued`,
`".$tmp.$table."`.`url`  
FROM `".$tmp.$table."` 
LEFT  JOIN `".$table."` ON `".$tmp.$table."`.entry_full_code = `".$table."`.entry_full_code
WHERE `".$table."`.url IS NULL);";
//echo $sql;
if ($db->multi_query($sql)) {
    $i = 1;
    do {
        /* store first result set */
        if ($result = $db->store_result()) {
            while ($row = $result->fetch_row()) {
                printf("%s\n", $row[0]);
            }
            $result->free();
        }
        /* print divider */
        if ($db->more_results()) {
            printf("Query ".$i." executing...<br />");
        }
        $i++;
    } while ($db->more_results() && $db->next_result());
    echo "All DONE! <a href=\"dashboard.php\">Go to Dashboard page</a><br />";
}

cabinet_content_bottom();
cabinet_sidebar();
cabinet_footer();
$db->close();
?>
