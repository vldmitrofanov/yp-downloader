<?php
// quick_load.php

session_start();
require_once('main.php');

if ( $_SESSION['level'] >= 10 ){
            $db = DB1::getInstance();
            $CityStateUSA = new CityStateUSA();
            $IndustryUSA = new IndustryUSA();

            //$YpMysqli->GetCityCode_byTitle_andStateCode($db,$CityTitle,$StateCode);
            if ((empty($_POST['address']))||( !preg_match("/^[A-z]+[0-9a-zA-Z&-,\s]{1,}$/i", $_POST['address']))){
                        exit("empty OR wrong characters detected");
                    }
            //if ( !preg_match("/^[A-z]+[0-9a-zA-Z&-,\s]{1,}$/i", $_POST['address'])){
            //    exit("empty OR wrong characters detected");
            //}
            $address = trim($_POST['address']);
            cabinet_html_head("YP GRABBER Dashboard","Yellow Pages","YP GRABBER Quick leads load","");
            cabinet_header("");
            cabinet_content_top();
            if ( substr_count( $address, "," ) == 1 ){
                $CityTitle = trim(strstr($address, ", ",true));
                $StateCode = trim(trim(strrchr($address, ", "), ","));
            }
            elseif( substr_count( $address, "," ) == 1 ){
                $StateCode = trim(trim(strrchr($address, ", "), ","));
                $CityTitle_lenght = strrpos($address,",");
                $CityTitle = trim(substr($address, 0, $CityTitle_lenght));
                //$CityCode = strtolower( preg_replace( "/\s|\&|,/","-",$CityTitle));
                //$CityCode = preg_replace("/\-+/i", "-", $CityCode);
                //echo '$StateCode = '.$StateCode.'<br />
                //$CityTitle = '.$CityTitle.'<br />$CityCode = '.$CityCode.'
                //    <br />from '.$address;
                //exit;
            }
            else{
                exit("the city name contains more than two commas<br /><input type=\"button\" value=\"Back\" onclick=\"goBack()\">");
            }
            
            // this block is temp
            //8888888888888888888888888888888888888888888888888888888888888            
            $city = $CityStateUSA->GetCityCode_byTitle_andStateCode($db,$CityTitle,$StateCode);
            
            echo "City:  <b>".$city['state_city_title'] ."</b> ( ID: ".$city['id']." | code: ". $city['state_city_code'].")<br />";
            echo "State: <b>".$StateCode." </b>( ID: ".$city['parent_id']." )<br />";
            //0000000000000000000000000000000000000000000000000000000000000000

            if ( !preg_match("/^[A-z]+[0-9a-zA-Z&-\s]{1,}$/i", $_POST['industry'])){
                exit("empty OR wrong characters detected");
            }
            
            $industry = trim($_POST['industry']);

            //8888888888888888888888888888888888888888888888888888888888888
            $industry_result = $IndustryUSA->GetStatusIndustry2Arr_orAdd($db,$industry);
            echo "Industry: <b>".$industry_result['Industry_title']."</b> ( ID: ".$industry_result['ind_id']." | code: ".$industry_result['industry_code']." )<br />";
            // [0] => 11 [ind_id] => 11 [1] => furniture-stores [industry_code] => furniture-stores [2] => Furniture Stores 
            // [Industry_title] => Furniture Stores
            //00000000000000000000000000000000000000000000000000000000000000
            
            // preparing to add new queue:
            $www_url = "http://www.yellowpages.com/".$city['state_city_code']."-".strtolower($StateCode)."/".$industry_result['industry_code'];
            $area_done = $city['id']."-".$city['parent_id'];
            $sql = sprintf("SELECT * FROM `YP_US_industry_done` 
                           WHERE `ind_id_done` = '{$industry_result['ind_id']}' AND
                           `url` = '%s' AND
                           `area_done` = '{$area_done}' LIMIT 0,1",
                           $db->real_escape_string($www_url));
            $result = $db->query($sql);
                if (!$result)  { 
                    printf("[%d] %s\n", $db->errno, $db->error); 
                    exit();
                }
            if ($result->num_rows == 0){
               //Add a new queue
               $sql = sprintf("INSERT INTO `YP_US_industry_done` 
                                ( `ind_id_done`,`url`,`area_done`,`is_finished`,`enqueued`)
                                VALUES
                                ('{$industry_result['ind_id']}','%s','{$area_done}','no','yes')",
                                $db->real_escape_string($www_url)); 
               $result = $db->query($sql);
                if (!$result)  { 
                    printf("[%d] %s\n", $db->errno, $db->error); 
                    exit();
                }
                else{
                    echo "<font color=\"green\">queue has been added for ".$www_url."</font>";
                }
            }
            else{
                echo "<font color=\"red\">there is a queue already for ".$www_url."</font>";
            }
            cabinet_content_bottom();
            cabinet_sidebar();
            cabinet_footer();
}
else{
            echo "You have no rights to add city";
            echo "<input type=\"button\" value=\"Back\" onclick=\"goBack()\">";
}
$db->close();
?>
