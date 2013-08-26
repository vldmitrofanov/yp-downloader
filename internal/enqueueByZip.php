<?php
//enqueueByZip.php
session_start();
require_once('../main.php');
if ( $_SESSION['level'] >= 10 ){
    $db = DB1::getInstance();
    $IndustryUSA = new IndustryUSA();
    $CityStateUSA = new CityStateUSA();
    if( !empty($_POST['state']) && preg_match("/^[A-Z]{1,2}$/", $_POST['state'])){
        $STATE = $_POST['state'];
        if ( !empty($_POST['industry']) && (!empty($_POST['city_'.$STATE]) && is_array($_POST['city_'.$STATE])) ){
            //echo "city array: ".print_r($_POST['city_'.$STATE]);
            $data = preg_replace('!s:(\d+):"(.*?)";!e', "'s:'.strlen('$2').':\"$2\";'", base64_decode($_POST['industry']));
            $industry_ids = (unserialize($data));
            $industry_array = $IndustryUSA->GetData_from_indIDs_array($db,$industry_ids);
            $state_id = $CityStateUSA->GetStateID($db,$STATE);
            $state_code = strtolower($STATE);
            $cities_array = array();
            $r = 0;
            foreach ($_POST['city_'.$STATE] as $new_city){
                $cities_array[$r] = $CityStateUSA->AddCity($db,$new_city,$state_id,1); 
                $r++;
            }
            //print_r($cities_array);
            //echo "<br />-----------<br />";
//            foreach( $cities_array as $key => $city){
//                echo "CitiID= ".$city['id']." CityName= ".$city['state_city_title']."<br />\n";
//            }
            $tmp = "tmp_".date('m_d_H_i')."_";
            $table = "YP_US_industry_done";
            $yp_url = _YP_WWW_URL_;
            if ( empty($cities_array)|| !is_array($industry_array) ){
                exit("No data received from DataBase");
            }
            $sql = "CREATE TEMPORARY TABLE `".$tmp.$table."` AS SELECT * FROM `".$table."` WHERE 1=2;\n";
            //$sql_counter = 0;
            $i = 0;
            while ($i < count($cities_array)) {
                if ( !isset($cities_array[$i][0]['id'])){
                    unset($cities_array[$i]);
                }
                foreach ($industry_array as $ind_row){
                    $sql .= sprintf( "INSERT INTO `".$tmp.$table."`
                                    (`ind_id_done`,`area_done`,`entry_full_code`,`is_finished`,`enqueued`,`url`)
                                    VALUES
                                    ('%s','%s','%s','no','yes','%s');\n",
                            $db->real_escape_string($ind_row['ind_id']),
                            $db->real_escape_string($cities_array[$i][0]['id']."-".$state_id),
                            $db->real_escape_string($cities_array[$i][0]['id']."-".$state_id."-".$ind_row['ind_id']),
                            $db->real_escape_string($yp_url."/".$cities_array[$i][0]['state_city_code']."-".$state_code."/".$ind_row['industry_code']));
                    //$sql_counter++;
                }
                //echo "CitiID= ".$cities_array[$i][0]['id']." ";
                //echo "CityName= ".$cities_array[$i][0]['state_city_title']."<br />";
                $i++; 
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
                    echo "All DONE!";
                }
        }
    } 
    else exit("No data provided. Empty request.");           
}
else{
    echo "You have no rights";
    exit();
}
$db->close();
?>
