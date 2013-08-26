<?php
// getYP_US_leads.php

session_start();
require_once('main.php');
$db = DB1::getInstance();
$YpMysqli = new YpMysqli();
$CityStateUSA = new CityStateUSA();
$IndustryUSA = new IndustryUSA();
if ( empty($_SESSION['level']) || $_SESSION['level'] < 4 ){
    header ('Location: login.php', true, 302);
    exit();
}
$www_website = "http://www.yellowpages.com";
// $_POST data:
// ex: ( [state] => 5 [city] => 52 [industry] => 1 )
// $_POST = array_map('strip_tags', $_POST);
// $_GET = array_map('strip_tags', $_GET);

$state = htmlspecialchars($_POST['state']);
$city = htmlspecialchars($_POST['city']);
$industry = htmlspecialchars($_POST['industry']);
if ( !preg_match("/^[0-9]{1,2}$/", $state) ){
    echo "Wrong State ID";
    exit();
} 
if ( !preg_match("/^([0-9]{1,}|all)$/", $city)){
    echo "Wrong City ID received";
    exit();
}
if ( !preg_match("/[0-9]{1,}/", $industry)){
    echo "Wrong Industry ID";
    exit();
} 
if ($city == "all"){
    echo "not yet implemented";
    exit();
}
cabinet_html_head("YP GRABBER","Yellow Pages","YP GRABBER Downloading LEADS","");
cabinet_header("");
$city_state = $city."-".$state;
$www_area = $CityStateUSA->getStateCity_codeByID($db,$city_state);
$www_industry = $IndustryUSA->getIndustry_codeByID($db,$industry);
$www_url = $www_website."/".$www_area."/".$www_industry;

$checkHistory = $IndustryUSA->CheckHistory($db,$industry,$city_state);
echo $checkHistory."<br />";

if ( preg_match("/^yes/", $checkHistory)){
    $date_loaded = trim(strstr($checkHistory, "__"),"__");
    echo "this industry \"".$www_industry."\" in area ".$www_area." has been already loaded completely by ".$date_loaded."<br />";
    cabinet_footer();
    exit;
}


//$www_url = $www_website."/".$www_area."/".$www_industry;
echo $www_url."<br />";

//$page_counter = "";
//$i = 0;
//$proxy_cycle_limit = 5;
//    while ($i < $proxy_cycle_limit) {        
//        $proxy = $YpMysqli->get_random_proxy($db);
//        $content = CurlParser::get_curl_data($www_url,$proxy['ipaddress'],$proxy['port'],"");
//        echo $proxy['ipaddress']."<br />";
//        echo $proxy['port']."<br />";
//        $page_counter = YpUsBiz::getYP_US_bizCounter($content);
//        echo "<h1>".$page_counter."</h1>";
//        if ($page_counter == "robot") {
//            echo $proxy['ipaddress']." is blocked on yellowpages<br />";
//            $YpMysqli->update_proxy_status($db,$proxy['ipaddress'],"blocked");
//        }            
//        if ( $page_counter == "unreachable") {
//            echo "check your <a href=\"./loadProxy.php\">proxy</a> ".$proxy['ipaddress']." or internet connection <br />";
//            $YpMysqli->update_proxy_status($db,$proxy['ipaddress'],"unreachable");
//            $i++;
//        }
//        if ( is_numeric($page_counter) ){
//            if ( $page_counter == "0" ) {
//                echo "There is a empty request. Try to change something, like city or industry<br />";
//               $YpMysqli->update_proxy_status($db,$proxy['ipaddress'],"ok");
//                $i = $proxy_cycle_limit;
//            }
//            if ( $page_counter >= "1" ) {
//                echo "Pages found ".$page_counter." We'll go with proxy ".$proxy['ipaddress']."<br />";
//                $YpMysqli->update_proxy_status($db,$proxy['ipaddress'],"ok");
//                $i = $proxy_cycle_limit;
//            }
//        }
//        else { 
//            cabinet_sidebar();
//            cabinet_footer();
//            die ("check your script for errors");
//            }        
//    }
if ( (!empty($checkHistory)) && (preg_match("/^[0-9]{1,}-[0-9]{1,}$/", $checkHistory))){
                     $start_page = strstr($checkHistory, "-",true);
                     //$start_page = trim(strstr($start_page, "-"), "-");
                     $www_url = $www_url."?page=".$start_page;
                 }
else {
    $start_page = "1";
}
echo "Start_page is ".$start_page."<br />";                 
$content = $YpMysqli->CheckProxyAndGetContent_USbiz($db,$www_url,"5");    

//$content = $yp_curl->get_curl_data($www_url,"87.221.76.177","80","");
//$page_counter = $yp_us_biz->get_yp_us_biz_counter($content);
//echo $page_counter;

    $leads = YpUsBiz::getYP_US_bizLeads($content);
    if (!empty ($leads)){
        $leads_db = DB2::getInstance();
        $YpUsBiz = new YpUsBiz();
        $LeadsLoaderUSA = new LeadsLoaderUSA();
        echo $YpUsBiz->PrintLeads($leads);
        if ( $LeadsLoaderUSA->LoadBusLeads_2DB($leads_db,$leads)){
             $IndustryUSA->YP_add_updateDownloadingHistory ($db,$industry,$city_state,"1-".$YpMysqli->page_counter,$www_url);
             echo "Page $start_page loaded<br />";
             $start_page++;
             if ( $YpMysqli->page_counter > "1"){
                 if ( $start_page > 2){
                     $i = $start_page;
                 }
                 else{
                    $i=2;
                 }
                    while ( $i <= $YpMysqli->page_counter ){
                        $www_url_page_number = $www_url."?page=".$i;
                        $content = CurlParser::get_curl_data($www_url_page_number,$YpMysqli->ProxyIP,$YpMysqli->ProxyPort,5,"");
                        $current_page_counter = YpUsBiz::getYP_US_bizCounter($content);
                        if ( !is_numeric($current_page_counter)){
                            $content = $YpMysqli->CheckProxyAndGetContent_USbiz($db,$www_url_page_number,"5");
                        }
                        $leads = YpUsBiz::getYP_US_bizLeads($content,"0");
                        echo $YpUsBiz->PrintLeads($leads);
                        if ( $LeadsLoaderUSA->LoadBusLeads_2DB($leads_db,$leads)) {
                            $IndustryUSA->YP_add_updateDownloadingHistory ($db,$industry,$city_state,$i."-".$YpMysqli->page_counter,$www_url);
                            echo "Page ".$i." loaded<br />";
                            $i++;
                            }
                         else{
                             exit ("Cant load Leads");
                         }
                        
                        }
                    }       
                }
                else{
                             exit ("Cant load Leads");
                         }
        $leads_db->close();
        }

$db->close();
//
?>
 