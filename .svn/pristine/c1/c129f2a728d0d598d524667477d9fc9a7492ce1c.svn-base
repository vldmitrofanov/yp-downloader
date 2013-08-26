<?php


class YpMysqli {
    public function GetAdminYPsettings_mysqli($db,$settings,$user){
        if (empty($db) || empty($settings) || empty($user)){
            exit("settings data not provided \n");
        }
        $sql = sprintf("SELECT value FROM YP_admin_settings where `settings`='%s' AND `user`='%s' LIMIT 0,1",
                $db->real_escape_string($settings),
                $db->real_escape_string($user));
        $result = $db->query($sql);
        if (!$result) { printf("[%d] %s\n", $db->errno, $db->error); exit();}
        $result = $result->fetch_row();
        return $result[0];
        $result->free();
    }
    public function GetAdminYPsettingsArr($db,$user){
         if (empty($db) || empty($user)){
            exit("settings data not provided \n");
        } 
        $sql = sprintf("SELECT * FROM YP_admin_settings where `user`='%s'",
                $db->real_escape_string($user));
        $result = $db->query($sql);
        if (!$result) { printf("[%d] %s\n", $db->errno, $db->error); exit();}
        //$rows = $result->fetch_all(MYSQLI_ASSOC);
        //$result = $result->fetch_arr();
        while($row = $result->fetch_assoc()){
              $rows[] = $row;
        }
        return $rows;
        $result->free();
    }
    
    public function setAdminYPsettingsArr($db,$settings_array){
        if (empty($db) || empty($settings_array) || !is_array($settings_array) 
                || !preg_match("/^[0-9]{1,}$/",$settings_array['set_id'])){
            exit("settings data not provided \n");
        }
        $sql = sprintf("UPDATE YP_admin_settings SET
            `short_description`='%s',
            `value`='%s',
            `long_description`='%s'
            WHERE `set_id`='".$settings_array['set_id']."'",
                $db->real_escape_string($settings_array['short_description']),
                $db->real_escape_string($settings_array['value']),
                $db->real_escape_string($settings_array['long_description']));
            $result = $db->query($sql);
        if (!$result) { printf("[%d] %s\n", $db->errno, $db->error); exit();}
        else return true;
    }
    
    public function GetAdminYPsettingsArr_byID($db,$id){
         if (empty($db) || !preg_match("/^[0-9]{1,}$/",$id)){
            exit("settings data not provided \n");
        } 
        $sql = "SELECT * FROM YP_admin_settings where `set_id`='{$id}'";
        $result = $db->query($sql);
        if (!$result) { printf("[%d] %s\n", $db->errno, $db->error); exit();}
        //$result = $result->fetch_arr();
        while($row = $result->fetch_assoc()){
              $rows[] = $row;
        }
        return $rows;
        $result->free();
    }
    public function CheckString($string){
        if (!empty($string)){
             $string = trim($string);
             if ( preg_match("/^[0-9a-zA-Z-]{1,}$/",$string)){ 
                 return $string;
             }
             else { return false;}
        } 
        else { return false;}
    }
    
    public function insert_proxy($db,$file) {
        if ( empty($db) || empty($file)){
            echo "No database or file name were provided. Exiting";
        }
        if ( preg_match("/^[0-9a-zA-Z-]{1,}\.txt$/",$file) && is_file($file) ){
            echo $file." is file<br />";
            $content = file_get_contents($file);
        }
        elseif ( is_string( $file )){
            $content = $file; 
        }
        $content = str_replace("\r","",$content);
        //$str = explode("\n", $content);
        $matches0 = "";
        preg_match_all("/\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}\:\d{2,4}/", $content, $matches0);
        foreach( $matches0["0"] as $key => $value){
            $ipaddress = strstr($value, ":",true);
            $port = trim(strstr($value, ":"), ":");
            $sql = "SELECT `ipaddress` FROM yp_proxy WHERE ipaddress='{$ipaddress}' 
                    AND `status`='blocked' LIMIT 0,1";
            $result = $db->query($sql);
                if (!$result)  { printf("[%d] %s\n", $db->errno, $db->error); exit();}
            if ( $result->num_rows == 0 ) {
                $content = CurlParser::get_google($ipaddress,$port);
                if ( preg_match("/id=\"fehl\">Google.com<\/a>/Us", $content, $matches0)){
                    $google_status = "ok";
                    $load_message = "<br />loaded <b><font color=\"green\">".$ipaddress."</font></b> : ".$port."<br />";
                              
                }
                else {
                    echo "Can't get the google page with ".$ipaddress."<br />";
                    $load_message = "loaded <font color=\"red\">".$ipaddress."</font> : ".$port." as inactive proxy<br />";
                    $google_status = "no";                     
                }
                $sql = "INSERT IGNORE INTO `yp_proxy` (`ipaddress`,`port`,`google`,`status`) values ('{$ipaddress}','{$port}','{$google_status}','unknown');";
                if ( !$db->query($sql) ){ printf("[%d] %s\n", $db->errno, $db->error); exit();}
                echo $load_message;
            }
            else { 
                echo "<br />".$ipaddress." is already in DB<br />";
                $result->free();
                }
        }
    }
    
    public function get_random_proxy($db){
        $proxy_max_age = $this->GetAdminYPsettings_mysqli($db,"proxy_max_age", "admin");
        if (empty($proxy_max_age) || $proxy_max_age == 0){
            $proxy_max_age = 10;
        }
        $sql = "SELECT ipaddress,port FROM yp_proxy 
                WHERE (( google='ok') AND (status in ('ok','unknown') ) AND ( added > SUBDATE( now(), ".$proxy_max_age.")))
                ORDER BY RAND() LIMIT 0,1";
        $result = $db->query($sql);
        if (!$result)  { 
                    printf("[%d] %s\n", $db->errno, $db->error); 
                    exit();
        }
        if ($result->num_rows == 0) {
            echo "No active proxy found, nothing to print so am exiting. So please do load fresh proxy list
                \n";
            return false;
            exit();
        }
        else{
            // *************************************************************
            // This is returning Array: $result['ipaddress'], $result['port']
            // not single result!!
            //************************************************************
            $result = $result->fetch_assoc(); 
            return $result;
            $result->free();
        }
    }
    public function SendAdminWarningEmail($db,$message){
        $warning_email = $this->GetAdminYPsettings_mysqli($db,"warning_email", "admin");
    }
    
    public function CheckProxy_andMail($db){
        $proxy_amount_warning = $this->GetAdminYPsettings_mysqli($db,"proxy_amount_warning", "admin");
        $proxy_amount_disaster = $this->GetAdminYPsettings_mysqli($db,"proxy_amount_disaster", "admin");
        
    }

    public function get_latest_proxy($db){
    $sql = "SELECT ipaddress,port FROM `yp_proxy` WHERE google='ok' AND status='ok' AND `added`>SUBDATE(now(), INTERVAL '10' MINUTE) ORDER BY RAND() LIMIT 0,1";
    $result = $db->query($sql);
        if (!$result)  { 
                    printf("[%d] %s\n", $db->errno, $db->error); 
                    exit();
        }
        if ($result->num_rows == 0) {
            return false;
            exit();
        }
        else{
            // *************************************************************
            // This is returning Array: $result['ipaddress'], $result['port']
            // not single result!!
            //************************************************************
            $result = $result->fetch_assoc(); 
            return $result;
        }
    }
    
    public function update_proxy_status($db,$ipaddress,$status){
        if ( empty($db) || empty($ipaddress) || empty($status) ){
            exit("not enough data provided");
        }
        $sql = "UPDATE yp_proxy SET status='$status' WHERE ipaddress='$ipaddress';";
        $result = $db->query($sql);
        if (!$result)  { 
            printf("[%d] %s\n", $db->errno, $db->error); 
            exit();
        }
        return true;
    }
       
    public function jquery_GetStateCity($db,$idcategory){
        // Selecting id and state_city_title for jquery request
        // returns results in jquery format enclosed by []. 
        // [{value:'{$row["id"]}', caption:'{$row["state_city_title"]}'}]
        // 
        //$db = DB1::getInstance();
        if ( $idcategory == "All" ){
            $sql = "SELECT `id`,`state_city_title`
                    FROM `YP_US_state_city`
                    WHERE `parent_id`='0'
                    ORDER BY `state_city_title`";
            $result = $db->query($sql);
                if (!$result)  { 
                    printf("[%d] %s\n", $db->errno, $db->error); 
                    exit();
                }
                if ( $result->num_rows==0 ) {
                    echo "there no any results received";
                    exit();
                }      
            $return="";
            while ($row = $result->fetch_array(MYSQL_BOTH)){
            $return.="{value:'{$row["id"]}', caption:'{$row["state_city_title"]}'} ,";
            }
            $return=substr($return,0,(strlen($return)-1));
            $return="[{$return}]";
            return $return;
            }
        else{
                $idcategory=intval($idcategory);
                if( $idcategory == "0" ){
                    $sql = "SELECT DISTINCT `parent_id`
                            FROM `YP_US_state_city`
                            WHERE NOT `parent_id`='0'";
                    $result = $db->query($sql);
                    if (!$result)  { 
                        printf("[%d] %s\n", $db->errno, $db->error); 
                        exit();
                    }                
                    $state_list = "";
                    while ($row = $result->fetch_array(MYSQL_BOTH)){
                        $state_list = $state_list.$row['parent_id'].",";
                    }
                    if( $state_list != ""){
                        $state_list=substr($state_list,0,(strlen($state_list)-1));
                        $sql = "SELECT DISTINCT `id`,`state_city_title`
                                FROM `YP_US_state_city`
                                WHERE `id` in ($state_list)
                                ORDER BY `state_city_title`";
                        $result = $db->query($sql);
                        $result = $db->query($sql);
                        if (!$result)  { 
                            printf("[%d] %s\n", $db->errno, $db->error); 
                            exit();
                        }    
                        $return="";
                        while ($row = $result->fetch_array(MYSQL_BOTH)){
                            $return.="{value:'{$row["id"]}', caption:'{$row["state_city_title"]}'} ,";
                        }
                        $return=substr($return,0,(strlen($return)-1));
                        $return="[{$return}]";
                        return $return;
                        }
                    else{ die("there no any results");}
                }   
                elseif( $idcategory > "0" ){
                    $sql="SELECT DISTINCT `id`,`state_city_title`
                          FROM `YP_US_state_city`
                          WHERE `parent_id`='{$idcategory}'
                          ORDER BY `state_city_title`";
                
                    $result = $db->query($sql);
                    if (!$result)  { 
                          printf("[%d] %s\n", $db->errno, $db->error); 
                          exit();
                    } 
                
                    $return="";
                    while ($row = $result->fetch_array(MYSQL_BOTH)){
                        $return.="{value:'{$row["id"]}', caption:'{$row["state_city_title"]}'} ,";
                    }
                    $return=substr($return,0,(strlen($return)-1));
                    $return="[{$return}]";
                    return $return;
                }
                else { 
                    echo "incorrect request... Exiting..";
                    exit();
                    }               
          }
          //$db->close();
    }
    
    public function jquery_GetIndustry($db,$region){
        //$region=intval($region);        
        if( isset($region) ){
            if ( preg_match("/^[0-9]{1,}-[0-9]{1,2}$/", $region)){            
                $sql = "SELECT DISTINCT `ind_id_done`
                        FROM `YP_US_industry_done` WHERE `area_done`='{$region}'";
                $YP_US_industry_done = $db->query($sql);
                if (!$YP_US_industry_done)  { 
                        printf("[%d] %s\n", $db->errno, $db->error); 
                        exit();
                }
                $industry_list_done ="";
                while ($row = $YP_US_industry_done->fetch_array(MYSQL_BOTH)){
                            $industry_list_done .= $row['ind_id_done'].",";
                        }
                        //echo $industry_list_done;
                        if( $industry_list_done != ""){
                            $industry_list_done=substr($industry_list_done,0,(strlen($industry_list_done)-1));
                            $sql = "SELECT DISTINCT `ind_id`,`Industry_title`
                                    FROM `YP_US_industry` WHERE NOT `ind_id` IN ('{$industry_list_done}')
                                    ORDER BY `Industry_title` ";
                            $YP_US_industry = $db->query($sql);
                            if (!$YP_US_industry) {
                                printf("[%d] %s\n", $db->errno, $db->error);
                                exit;
                            }    
                        }
                        else{
                            $sql = "SELECT DISTINCT `ind_id`,`Industry_title`
                                    FROM `YP_US_industry`
                                    ORDER BY `Industry_title` ";
                            $YP_US_industry = $db->query($sql);
                            if (!$YP_US_industry) {
                                printf("[%d] %s\n", $db->errno, $db->error);
                                exit;
                            }
                        }
                       $return="";
                       while ($row = $YP_US_industry->fetch_array(MYSQL_BOTH)){
                       $return.="{value:'{$row["ind_id"]}', caption:'{$row["Industry_title"]}'} ,";
                       }
                $return=substr($return,0,(strlen($return)-1));
                $return="[{$return}]";
                return $return;
            }
            elseif ( preg_match("/^all-[0-9]{1,2}$/", $region)){
                $sql = "SELECT DISTINCT `ind_id`,`Industry_title`
                                    FROM `YP_US_industry`
                                    ORDER BY `Industry_title` ";
                            $YP_US_industry = $db->query($sql);
                            if (!$YP_US_industry) {
                                printf("[%d] %s\n", $db->errno, $db->error);
                                exit;
                            }
                       $return="";
                       while ($row = $YP_US_industry->fetch_array(MYSQL_BOTH)){
                       $return.="{value:'{$row["ind_id"]}', caption:'{$row["Industry_title"]}'} ,";
                       }
                $return=substr($return,0,(strlen($return)-1));
                $return="[{$return}]";
                return $return;                
            }
        }
        else{ die("there no any results"); }
    }
    
   
    public function printSelect_allStates($db,$html_name,$html_id){   
        // this is printing in HTML format all states 
            $sql = "SELECT `id`,`state_city_title`,`state_city_code`
                    FROM `YP_US_state_city`
                    WHERE `parent_id`='0'
                    ORDER BY `state_city_title`";
            $result = $db->query($sql);
                if (!$result)  { 
                    printf("[%d] %s\n", $db->errno, $db->error); 
                    exit();
                }
                if ( $result->num_rows==0 ) {
                    echo "there no any results received";
                    exit();
                }      
            //$return="";
            echo "<select name=".$html_name." id=".$html_id.">";
            while ($row = $result->fetch_array(MYSQL_BOTH)){
                //echo "<option value=".$row['id'].">".$row['state_city_title']."</option>";
                echo "<option value=".$row['state_city_code'].">".$row['state_city_title']."</option>";
            }
            echo "</select>";
                        
    }
    
    public function select_Cities_fromUSA_zip_codes($db,$state){
        if (( !empty($state) || !empty($db) ) && ( preg_match("/^[A-Z]{2}$/i",$state) )){
            $sql = "SELECT DISTINCT `primary_city` 
                    FROM `USA_zip_codes` WHERE `state`='{$state}'
                    ORDER BY `primary_city`"; 
            $result = $db->query($sql);
            if (!$result)  { 
                    printf("[%d] %s\n", $db->errno, $db->error); 
                    exit();
           }
           if ( $result->num_rows==0 ) {
                    echo "there no any results received";
                    exit();
           }
           while ( $row = $result->fetch_assoc()){
               $rows[] = $row['primary_city'];
           }
           $result->free();
           return $rows;
        }
        
    }
    
    public $page_counter;
    public $ProxyIP = "";
    public $ProxyPort = "";
    public function CheckProxyAndGetContent_USbiz($db,$www_url,$cycle_limit){
        // The purpose of this function is to at same time get the web content
        // and check the proxy status
        // if the proxy is ok, it will be assign to global vars $ProxyIP and $ProxyPort
        //$page_counter = "";
        $this->page_counter = "";
        $y = 0;
        $proxy_cycle_limit = $cycle_limit;
            do { 
                $proxy = $this->get_latest_proxy($db);
                if (!$proxy){
                       $proxy = $this->get_random_proxy($db); 
                }
                $content = CurlParser::get_curl_data($www_url,$proxy['ipaddress'],$proxy['port'],$this->GetAdminYPsettings_mysqli($db,"curl_timeout","admin"),"");
                echo $proxy['ipaddress']."<br />\n";
                echo $proxy['port']."<br />\n";
                $this->page_counter = YpUsBiz::getYP_US_bizCounter($content);
                echo "<h1>".$this->page_counter."</h1>";
                if ($this->page_counter == "robot") {
                    echo $proxy['ipaddress']." is blocked on yellowpages<br /> \n";
                    echo "cycle ".$y." done<br /> \n";
                    $this->update_proxy_status($db,$proxy['ipaddress'],"blocked");
                }            
                elseif ( $this->page_counter == "unreachable") {
                    echo "check your <a href=\"./loadProxy.php\">proxy</a> ".$proxy['ipaddress']." or internet connection <br />\n";
                    $this->update_proxy_status($db,$proxy['ipaddress'],"unreachable");
                    echo "cycle ".$y." done<br /> \n";
                    $y++;
                }
                elseif ( is_numeric($this->page_counter) ){
                        if ( $this->page_counter == "0" ) {
                            echo "There is a empty request. Try to change something, like city or industry<br />\n";
                           $this->update_proxy_status($db,$proxy['ipaddress'],"ok");
                            $y = $proxy_cycle_limit;
                            $this->ProxyIP = $proxy['ipaddress'];
                            $this->ProxyPort = $proxy['port'];
                            return $content;
                            break;
                        }
                    elseif ( $this->page_counter >= "1" ) {
                            echo "Pages found ".$this->page_counter." We'll go with proxy ".$proxy['ipaddress']."<br />";
                            $this->update_proxy_status($db,$proxy['ipaddress'],"ok");
                            $y = $proxy_cycle_limit;
                            $this->ProxyIP = $proxy['ipaddress'];
                            $this->ProxyPort = $proxy['port'];
                            return $content;
                            break;
                        }
                }
                else { 
                    //cabinet_sidebar();
                    //cabinet_footer();
                    //die ("check your script for errors");
                    return false;
                    }        
            }
            while ($y < $proxy_cycle_limit);
    }
}
?>