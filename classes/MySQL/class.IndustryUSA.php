<?php

class IndustryUSA extends YpMysqli
{
     public function getIndustry_codeByID($db,$industry){
         //Returns Industry by ID
        if ( empty($db) || empty($industry) ){
            exit("not enough data provided");
        }
        if ( preg_match("/^[1-9]{1,}$/",$industry)){
            //$db = DB1::getInstance();
            $sql ="SELECT `industry_code` FROM `YP_US_industry` WHERE `ind_id`='{$industry}' LIMIT 0,1";
            $result = $db->query($sql);
                if (!$result)  { 
                    printf("[%d] %s\n", $db->errno, $db->error); 
                    exit();
                }
                if ( $result->num_rows==0 ) {
                    echo "there no any results received";
                    exit();
                }                
                    $result = $result->fetch_row();
                    $Industry = $result[0];
                    return $Industry;
                    $result->free();
        }
    }
    public function CheckHistory($db,$IndustryID,$area){
        // Returns 0, yes (if area is finished, or number pages were done of pages total
        // ex 8-46
        if ( empty($db) || empty($IndustryID) || empty($area) ){
            exit("not enough data provided");
        }
        if ( !preg_match("/^[0-9]{1,}$/",$IndustryID)){
            exit("Industry ID must contain digits only");
        }
        if ( preg_match("/^[0-9]{1,}-[0-9]{1,2}$/", $area) ){
            $sql ="SELECT `pages_done`,`is_finished`,`date_done` FROM `YP_US_industry_done` WHERE `ind_id_done`='{$IndustryID}' AND `area_done`='{$area}' LIMIT 0,1";
            $result = $db->query($sql);
                if (!$result)  { 
                    printf("[%d] %s\n", $db->errno, $db->error); 
                    exit();
                }
                if ( $result->num_rows==0 ) {
                    return false;
                } 
                else {
                    $result = $result->fetch_assoc();
                    if ($result['is_finished'] == "yes"){
                        return "yes__".$result['date_done'];
                        $result->free();
                    }
                    else{
                        return $result['pages_done'];
                        $result->free();
                    }
                }
            
        }
    }
    
     public function YP_add_updateDownloadingHistory ($db,$industry_id,$region,$pages,$www_url){
        // Stores history of requests, that were done to YP_US_industry_done table
        // page by page. After all pages are finished, will set flag "is_finished" to "yes"
        // So it could be easily traced in future, if there some requests were interrupted ot the middle
        
        if ( !empty($industry_id) || !empty($region) || !empty($pages) || !empty($db)){
            if ( preg_match("/^[0-9]{1,}-[0-9]{1,2}$/", $region) 
                    && preg_match("/[0-9]{1,}/", $industry_id )
                    && preg_match("/[0-9]{1,2}-[0-9]{1,}/", $pages) ){
                $curent_page = strstr($pages, "-",true);
                $total_pages = trim(strstr($pages, "-"), "-");
                //echo $curunt_page."  ".$total_pages."<br />";
                if ( $curent_page == $total_pages ){
                    $is_finished = 'yes';
                }
                else { $is_finished = 'no'; }
                //$db = DB1::getInstance();
                $sql = "SELECT * FROM `YP_US_industry_done` 
                        WHERE `area_done`='{$region}' AND `ind_id_done`='{$industry_id}'";
                $result = $db->query($sql);
                    if (!$result) {
                        printf("[%d] %s\n", $db->errno, $db->error);
                        exit;
                    }
                if ( $result->num_rows > 0 ) {
                    $result->free();
                    if ( $is_finished =='yes' ){
                        $sql = "UPDATE `YP_US_industry_done` 
                               SET `area_done`='{$region}', 
                                   `pages_done`='{$pages}', 
                                   `is_finished`='yes',
                                   `enqueued`='no'
                               WHERE `ind_id_done`='{$industry_id}' AND `area_done`='{$region}'"; 
                    }
                    else{
                        $sql = "UPDATE `YP_US_industry_done` 
                                SET `area_done`='{$region}',
                                    `pages_done`='{$pages}',
                                    `is_finished`='no',
                                    `enqueued`='no'
                                WHERE `ind_id_done`='{$industry_id}' AND `area_done`='{$region}'";
                    }
                    $result = $db->query($sql);
                    if (!$result) {
                        printf("[%d] %s\n", $db->errno, $db->error);
                        exit;
                    }
                    else { return true; }
                } 
                else {
                    if ( $is_finished =='yes' ){
                        $sql = sprintf("INSERT INTO `YP_US_industry_done` 
                                        (`ind_id_done`,`area_done`,`pages_done`,`is_finished`,`url`) 
                                        VALUES ('%s','%s','%s','yes','%s')",
                                       $db->real_escape_string($industry_id),
                                       $db->real_escape_string($region),
                                       $db->real_escape_string($pages),
                                       $db->real_escape_string($www_url));
                    }
                    else{
                        $sql = sprintf("INSERT INTO `YP_US_industry_done` 
                                        (`ind_id_done`,`area_done`,`pages_done`,`is_finished`,`url`) 
                                        VALUES ('%s','%s','%s','no','%s')",
                                       $db->real_escape_string($industry_id),
                                       $db->real_escape_string($region),
                                       $db->real_escape_string($pages),
                                       $db->real_escape_string($www_url));
                    }
                        $result = $db->query($sql);
                        if (!$result) {
                            printf("[%d] %s\n", $db->errno, $db->error);
                            exit;
                        }
                    else { return true; }
                }
            }            
        }
        // If there no args were provided:
        else {
            echo "can't process without required arguments";
            return false;
        }
    }
    public function GetStatusIndustry2Arr_orAdd($db,$Industry){
        if ( !preg_match("/^[A-z]+[0-9a-zA-Z&-\s]{2,}$/i", $Industry)){
            exit("wrong characters detected");
        }
        $sql = "SELECT * FROM `YP_US_industry` WHERE `Industry_title`='{$db->real_escape_string($Industry)}' LIMIT 1";
        $result = $db->query($sql);
                    if (!$result) {
                        printf("[%d] %s\n", $db->errno, $db->error);
                        exit;
                    }
         while( $result->num_rows < 1 ) { 
             $this->AddIndustry($db,$Industry);
             $result->free();
             $result = $db->query($sql);
                    if (!$result) {
                        printf("[%d] %s\n", $db->errno, $db->error);
                        exit;
                    }
         }
         $IndustryArray = $result->fetch_array();
         return $IndustryArray;
         $result->free();
    }
    public function AddIndustry($db,$Industry){
         if ( empty($db) || empty($Industry) ){
            exit("not enough data provided");
        }
        if ( !preg_match("/^[A-z]+[0-9a-zA-Z&-\s]{2,}$/i", $Industry)){
            exit("wrong characters detected");
        }
        $sql = "SELECT * FROM `YP_US_industry` WHERE `Industry_title`='{$db->real_escape_string($Industry)}' LIMIT 1";
        $result = $db->query($sql);
                    if (!$result) {
                        printf("[%d] %s\n", $db->errno, $db->error);
                        exit;
                    }
         if ( $result->num_rows == 0 ) {                    
                    $IndustryCode = strtolower( preg_replace( "/\s|\&/","-",$Industry));
                    $IndustryCode = preg_replace("/\-+/i", "-", $IndustryCode);
                    $sql = sprintf("INSERT INTO `YP_US_industry` 
                            (`industry_code`,`Industry_title`)
                            VALUES ('%s','%s')",
                            $db->real_escape_string($IndustryCode),
                            $db->real_escape_string($Industry));
                    $result = $db->query($sql);
                    if (!$result)  { 
                            printf("[%d] %s\n", $db->errno, $db->error); 
                            exit();
                    }
                    else { 
                        echo " <b><font color=\"green\">".$Industry." Added successfully!</font></b><br />"; 
                        return true;
                        }
           }
           else {
                    echo " <b><font color=\"red\">".$Industry." Already exist! Not Added</font></b><br />"; 
                    return false;
                    $result->free();
           }
    }
    public function GetQueueUSbiz($db,$interval){
        if (empty($interval)) $interval = 20;
        $sql = "SELECT * FROM `YP_US_industry_done` WHERE `is_finished`='no' AND `enqueued`='yes' AND `date_done`> SUBDATE(now(),".$interval.") LIMIT 0,1";
        $result = $db->query($sql);
                    if (!$result)  { 
                            printf("[%d] %s\n", $db->errno, $db->error); 
                            exit();
                    }
        if ( $result->num_rows==0 ) {
                    return false;
                } 
        else {
            // We will update the status of taken queue
            // to enqueued = no
            // so none will take it again at this time
            $result = $result->fetch_assoc();
            $RecordID = $result['record_id'];
            $sql = "UPDATE `YP_US_industry_done` SET `enqueued`='no' WHERE `record_id`='{$RecordID}'";
            $update = $db->query($sql);
                if (!$update)  { 
                    printf("[%d] %s\n", $db->errno, $db->error); 
                    exit();
                    }
            // returns array
            return $result;
            $result->free();
        }
    }
    
    public function GetQueuesNumber_USbiz($db){
        $sql = "SELECT COUNT(*) FROM `YP_US_industry_done` WHERE `is_finished`='no' AND `enqueued`='yes' AND `date_done`> SUBDATE(now(),20)";
        $result = $db->query($sql);
                    if (!$result)  { 
                            printf("[%d] %s\n", $db->errno, $db->error); 
                            exit();
                    }
        if ( $result->num_rows==0 ) {
                    return false;
                } 
        else {
            $result = $result->fetch_row();
            $result = $result[0];
            return $result;
            $result->free();
            }
    }

    public function UpdateDownloadingHistoryByID($db,$recordID,$pages){
        $curent_page = strstr($pages, "-",true);
        $total_pages = trim(strstr($pages, "-"), "-");
                if ( $curent_page == $total_pages ){
                    $sql = "UPDATE `YP_US_industry_done` 
                        SET `pages_done`='{$pages}',
                            `is_finished`='yes',
                            `enqueued`='no'
                            WHERE `record_id`='{$recordID}'";
                }
                else {
                    $sql = "UPDATE `YP_US_industry_done` 
                        SET `pages_done`='{$pages}',
                            `is_finished`='no',
                            `enqueued`='no'
                            WHERE `record_id`='{$recordID}'";
                }
                $result = $db->query($sql);
                    if (!$result)  { 
                            printf("[%d] %s\n", $db->errno, $db->error); 
                            exit();
                    }
                    else return true;        
    }
    public function GetIndustryListFromAreaArray_notInQueue($db,$array){
        if ( empty($db) || empty($array) || !is_array($array) ){
            exit("not enough data provided");
            } 
        $notInList = "";
        foreach($array as $row){
            if (preg_match("/^[0-9]{1,}-[0-9]{1,2}$/", $row)){
                $notInList .= "'".$row."',";
            }
            else{
                exit("wrong format for area");
            }
        }
        $notInList = trim($notInList,",");
        //echo $notInList."<br/>";
        $sql = "SELECT DISTINCT `ind_id_done` FROM `YP_US_industry_done` WHERE `area_done` IN ({$notInList})ORDER BY ind_id_done ASC";
        $result = $db->query($sql);
            if (!$result)  { 
                printf("[%d] %s\n", $db->errno, $db->error); 
                exit();
            }
            //echo $sql;
        $Ind_notInList = "";
        if ( $result->num_rows > 0 ) {
            //$Not_inListArr = array();
            while($row2 = $result->fetch_assoc()){
                    $rows2[] = $row2;
            }
          //echo "<br />".print_r($rows2);
            foreach($rows2 as $row3){
                $Ind_notInList .= "'".$row3['ind_id_done']."',";
            }
            $Ind_notInList = trim($Ind_notInList,",");
            $result->free();
        }  
        //echo '$Ind_notInList '. $Ind_notInList ."<br/>";
        if ( $Ind_notInList == "" ){
            $sql2 = "SELECT * FROM `YP_US_industry`";
        }
        else{
            $sql2 = "SELECT * FROM `YP_US_industry` WHERE `ind_id` NOT IN ({$Ind_notInList}) ORDER BY  `YP_US_industry`.`Industry_title` ASC";
        }  
        //echo $sql2;
        $result2 = $db->query($sql2);
                    if (!$result2)  { 
                            printf("[%d] %s\n", $db->errno, $db->error); 
                            exit();
                    }
        if ( $result2->num_rows==0 ) {
                    return false;
                }
        else{
           while($row = $result2->fetch_array())
                    $rows[] = $row;
            return $rows;
            $result2->free();
        }
    }
    
        public function GetData_from_indIDs_array($db,$IDs_array){
        if ( empty($db) || empty($IDs_array) || !is_array($IDs_array)){
            exit('$DB and $IDs_array needs to be provided');
        }
        $string ="";
        for($i=0; $i < count($IDs_array); $i++){
            if (preg_match("/^[0-9]{1,}$/", $IDs_array[$i])){
                $string .= "'".$IDs_array[$i]."',";
                }
            else {
                exit("wrong array! SQL attack detected");
            }
        }
        $sql ="SELECT * FROM `YP_US_industry` WHERE `ind_id` IN (".trim($string, ",").") ORDER BY  `YP_US_industry`.`Industry_title` ASC";
        $result = $db->query($sql);
        if (!$result)  { 
                    printf("[%d] %s\n", $db->errno, $db->error); 
                    exit();
        }
        if ( $result->num_rows==0 ) {
                 echo "there no any results received";
                 return false;
        }       
        while($row = $result->fetch_array())
               $rows[] = $row;
        return $rows;
        $result->free();
    }
    
    public function getAllIndustries($db){
        // returns all elements FROM `YP_US_industry`
        $sql = "SELECT * FROM `YP_US_industry` ORDER BY `Industry_title` ASC";
        $result = $db->query($sql);
        if (!$result)  { printf("[%d] %s\n", $db->errno, $db->error); exit();}
        if ( $result->num_rows==0 ) {
                 echo "there no any results received";
                 return false;
        }       
        while($row = $result->fetch_assoc())
               $rows[] = $row;
        return $rows;
        $result->free();
    }
    
// END of IndustryUSA
}

?>
