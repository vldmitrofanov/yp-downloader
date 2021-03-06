<?php
require_once( dirname(__FILE__) . '/main.php' );
set_time_limit(0);
$db = DB1::getInstance();
$YpMysqli = new YpMysqli();
//$UserAccess = new UserAccess();
$IndustryUSA = new IndustryUSA();
$YpUsBiz = new YpUsBiz();
$LeadsLoaderUSA = new LeadsLoaderUSA();

// First will get a active queue from `YP_US_industry_done` 
if ( !$GetQueue = $IndustryUSA->GetQueueUSbiz($db,60)){
    exit ("[".date('Y-m-d H:i:s')."] No active queues \n");
}
print_r($GetQueue);
$www_url = $GetQueue['url'];
//$industry = $IndustryUSA->getIndustry_codeByID($db,$GetQueue['ind_id_done']);
$queue_RecordID = $GetQueue['record_id'];
if ( empty($www_url) || empty($queue_RecordID) ){
    exit("[".date('Y-m-d H:i:s')."] No data received from active queue. Exiting...\n");
}
if ( !empty($GetQueue['pages_done']) && preg_match("/^[0-9]{1,}-[0-9]{1,}$/", $GetQueue['pages_done'])){
     //echo $GetQueue['pages_done'];
     $start_page = strstr($GetQueue['pages_done'], "-",true);
     $total_pages = trim(strstr($GetQueue['pages_done'], "-"), "-");
}
elseif ( empty($GetQueue['pages_done']) ){
     $start_page = 0;
}
else {
    exit("[".date('Y-m-d H:i:s')."] Wrong ".'$GetQueue[\'pages_done\']'." received \n");
}
if ( $start_page > 0 ){
// Add +1 to $start_page, because current $start_page is done. Example: we have loaded 60 from 70pages. 
// So nest time we have to start from 61
         $www_url_page_number = $www_url."?page=".$start_page;
         echo $www_url_page_number." \n";
     }
else {
         $www_url_page_number = $www_url;
     }
     echo "[".date('Y-m-d H:i:s')."] start page is ".$start_page." \n URL is ".$www_url."\n";
     $start_page++;
     $content = $YpMysqli->CheckProxyAndGetContent_USbiz($db,$www_url_page_number,$YpMysqli->GetAdminYPsettings_mysqli($db,"check_proxy_cycles","admin"));
     $leads = YpUsBiz::getYP_US_bizLeads($content);
     if (!empty ($leads)){
        $leads_db = DB2::getInstance();
        echo $YpUsBiz->PrintLeads($leads);
        if ( $LeadsLoaderUSA->LoadBusLeads_2DB($leads_db,$leads)){
            if (empty($total_pages)){
                $total_pages = YpUsBiz::getYP_US_bizCounter($content);
                if (!is_numeric($total_pages)){
                    exit("[".date('Y-m-d H:i:s')."]  Cant get Pages counted \n exiting...\n");
                }
            }
             $IndustryUSA->UpdateDownloadingHistoryByID($db,$queue_RecordID,$start_page."-".$total_pages);;
             echo "[".date('Y-m-d H:i:s')."] Page ".$start_page." loaded \n";
             $start_page++;
                  while ( $start_page <= $total_pages ){
                        $www_url_page_number = $www_url."?page=".$start_page;
                        $content = CurlParser::get_curl_data($www_url_page_number,$YpMysqli->ProxyIP,$YpMysqli->ProxyPort,$YpMysqli->GetAdminYPsettings_mysqli($db,"curl_timeout","admin"),"");
                        $current_page_counter = YpUsBiz::getYP_US_bizCounter($content);
                        if ( !is_numeric($current_page_counter)){
                              $content = $YpMysqli->CheckProxyAndGetContent_USbiz($db,$www_url_page_number,$YpMysqli->GetAdminYPsettings_mysqli($db,"check_proxy_cycles","admin"));
                        }
                        $leads = YpUsBiz::getYP_US_bizLeads($content,"0");
                        //echo $YpUsBiz->PrintLeads($leads);
                        if ( $LeadsLoaderUSA->LoadBusLeads_2DB($leads_db,$leads)) {
                              $IndustryUSA->UpdateDownloadingHistoryByID($db,$queue_RecordID,$start_page."-".$total_pages);
                              echo "[".date('Y-m-d H:i:s')."] Page ".$start_page." loaded<br /> \n";
                              $start_page++;
                        }
                        else{
                               exit ("[".date('Y-m-d H:i:s')."] Cant load Leads \n");
                               }
             }
         }
         $leads_db->close();
     }


$db->close();
?>
