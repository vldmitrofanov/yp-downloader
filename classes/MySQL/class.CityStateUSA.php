<?php
// class.CityStateUSA
// This class is designed to work with 
// City and States of US. Loadind to MySQL DB, 
// unloading, remove, add and etc.
class CityStateUSA extends YpMysqli{
    
        public function GetAllStates2list($db){
            // returns array with all states
            // where parent_id = 0
            $sql ="SELECT * 
                FROM `YP_US_state_city` 
                WHERE `parent_id`='0'";
            $result = $db->query($sql);
            if (!$result)  { 
                    printf("[%d] %s\n", $db->errno, $db->error); 
                    exit();
            }
            while($row = $result->fetch_array())
                    $rows[] = $row;
            return $rows;
            $result->free();
        }

        public function GetStateID($db,$StateCode){
        // Pulling the ID of state by State code provided
        // Ex Send CA receive 5
         if ( empty($db) ||  empty($StateCode) ){
            exit("not enough data provided");
        }
        if ( preg_match("/^[A-Z]{2}$/i", $StateCode)){
            $sql ="SELECT `id` 
                FROM `YP_US_state_city` 
                WHERE `state_city_code`='{$StateCode}'
                AND `parent_id`='0' LIMIT 0,1";
            $result = $db->query($sql);
                if (!$result)  { 
                    printf("[%d] %s\n", $db->errno, $db->error); 
                    exit();
                }
            $result = $result->fetch_row();
            return $result[0];
            $result->free();
        }
    }
    public function GetStateCode_byID($db,$StateID){
        // Pulling the ID of state by State code provided
        // Ex Send CA receive 5
         if ( empty($db) ||  empty($StateID) ){
            exit("not enough data provided");
        }
        if ( preg_match("/^[0-9]{1,2}$/i", $StateID)){
            $sql ="SELECT `state_city_code` 
                FROM `YP_US_state_city` 
                WHERE `id`='{$StateID}'
                AND `parent_id`='0' LIMIT 0,1";
            $result = $db->query($sql);
                if (!$result)  { 
                    printf("[%d] %s\n", $db->errno, $db->error); 
                    exit();
                }
            $result = $result->fetch_row();
            return $result[0];
            $result->free();
        }
    }
    
    public function GetCityCode_byTitle_andStateCode($db,$CityTitle,$StateCode){
        // returns ARRAY from YP_US_state_city by Ex:
        // City title = New York, State code = NY
        // ret. array with all data
        // if the city not in database, will add it first, then returns
        // the same array[]
        
         if ( empty($db) || empty($CityTitle) || empty($StateCode) ){
            exit("not enough data provided");
        }
        if ( !preg_match("/^[A-z]+[0-9a-zA-Z,&-\s]{1,}$/i", $CityTitle)){
            exit("wrong characters detected");
        }
        if ( preg_match("/^[A-Z]{2}$/i", $StateCode)){
            $state_id = $this->GetStateID($db,$StateCode);
        }
        if ( !preg_match("/^[0-9]{1,2}$/i", $state_id) || $state_id == "0" ){
            exit("wrong characters detected");
        }
        // before only `state_city_code`
        $sql ="SELECT * 
                FROM `YP_US_state_city` 
                WHERE `state_city_title`='{$CityTitle}'
                AND `parent_id`='{$state_id}' LIMIT 0,1";
        $result = $db->query($sql);
         if (!$result)  { 
                    printf("[%d] %s\n", $db->errno, $db->error); 
                    exit();
                }
         while ( $result->num_rows < 1){
             if ( $this->AddCity($db, $CityTitle, $state_id, 0) ){
                  $result->free();
                  $result = $db->query($sql);
             }
             else{
                 exit("Can't add city ".$CityTitle." to ".$StateCode." <br />");
             }             
             //$CityCode = $result['state_city_code'];
         }
         $CityArray = $result->fetch_array();
             //$CityCode = strtolower( preg_replace( "/\s|\&|,/","-",$CityTitle));
             //$CityCode = preg_replace("/\-+/i", "-", $CityCode);
         if (is_array($CityArray)){
             //$CityArray['state_id'] = $state_id;
             return $CityArray;
             $result->free();
         }
         else return false;
    }
    
     public function AddCity($db,$city,$state_id,$need_result){
        //*****************************************************
        // USE AddCity($db,"San Francisco","ca")
        //            OR
        // USE AddCity($db,"San Francisco","5")
        // This will add the city to db with parent id=state_id          
        //*****************************************************
        if ( empty($db) || empty($city) || empty($state_id) ){
            exit("not enough data provided");
        }
        if ( empty($need_result)){
            $need_result = 0;
        }
        if ( !preg_match("/^[A-z]+[0-9a-zA-Z,&-\s]{1,}$/i", $city)){
            exit("wrong characters detected");
        }
        if ( preg_match("/^[A-Z]{2}$/i", $state_id)){
            $state_id = $this->GetStateID($db,$StateCode);
        }
        if ( !preg_match("/^[0-9]{1,2}$/i", $state_id) || $state_id == "0" ){
            exit("wrong characters detected");
        }
        $sql ="SELECT * 
                FROM `YP_US_state_city` 
                WHERE `state_city_title`='{$city}'
                AND `parent_id`='{$state_id}'";
        $result = $db->query($sql);
        if (!$result)  { printf("[%d] %s\n", $db->errno, $db->error); exit();}
        if ( $result->num_rows==0 ) {
            $result->free();
            $city_code = strtolower( preg_replace( "/\s|\&|,/","-",$city));
            $city_code = preg_replace("/\-+/i", "-", $city_code);
            //echo $city_code;
            $sql = "INSERT INTO `YP_US_state_city` 
                    (`parent_id`,`state_city_code`,`state_city_title`)
                    VALUES ('{$db->real_escape_string($state_id)}','{$db->real_escape_string($city_code)}','{$db->real_escape_string($city)}')";
            $result = $db->query($sql);
                if (!$result)  { printf("[%d] %s\n", $db->errno, $db->error); exit();}
                else { 
                    if ($need_result == 0 ){
                        echo " <b><font color=\"green\"> Added successfully!</font></b><br />"; 
                        return true;                        
                        }
                    else{
                        $sql ="SELECT * 
                              FROM `YP_US_state_city` 
                              WHERE `state_city_title`='{$city}'
                              AND `parent_id`='{$state_id}'";
                         $result = $db->query($sql);
                         if (!$result)  { printf("[%d] %s\n", $db->errno, $db->error); exit();}
                         while($row = $result->fetch_assoc())
                            $rows[] = $row;
                            return $rows;
                    }
                    $result->free();
                    }
       } 
       else {
           if ($need_result == 0 ){
                echo "The city ".$city." already in the list";
                return false;
           }
           else{
               while($row = $result->fetch_assoc())
                    $rows[] = $row;
                return $rows;
           }
       $result->free();
       }
    }
    
     public function getStateCity_codeByID($db,$state_code_id){
        if ( preg_match("/^[0-9]{1,}-[0-9]{1,2}$/", $state_code_id) ){
            $city_id = strstr($state_code_id, "-",true);
            $state_id = trim(strstr($state_code_id, "-"), "-");
            $sql ="SELECT `state_city_code` FROM `YP_US_state_city` WHERE `id`='{$state_id}' LIMIT 0,1";
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
                $state_code = strtolower($result[0]);
                $sql ="SELECT `state_city_code` FROM `YP_US_state_city` WHERE `id`='{$city_id}' LIMIT 0,1";
                $result = $db->query($sql);
                if (!$result)  { 
                    printf("[%d] %s\n", $db->errno, $db->error); 
                    exit();
                }
                if ( $result->num_rows==0 ) {
                    echo "there no any results received";
                    exit();
                }    
                $city_result = $result->fetch_row();
                $CityState = strtolower($city_result[0])."-".$state_code;
                return $CityState;
                $result->free();
        }
        else{ return false; }
    }
    
    
    

        public function getStateCity_ALL_codeByID($db,$state_code_id){
        if ( preg_match("/^all-[0-9]{1,2}$/", $state_code_id) ){
            //$db = DB1::getInstance();
            $city_id = strstr($state_code_id, "-",true);
            //echo "<br />".$state_id."<br />";
            $state_id = trim(strstr($state_code_id, "-"), "-");
            //echo "<br />".$city_id."<br />";
            $sql ="SELECT `state_city_code` FROM `YP_US_state_city` WHERE `id`='{$state_id}' LIMIT 0,1";
            $result = $db->query($sql);
                if (!$result)  { 
                    printf("[%d] %s\n", $db->errno, $db->error); 
                    exit();
                }
                if ( $result->num_rows==0 ) {
                    echo "there no any results received";
                    return false;
                }        
                $result = $result->fetch_row();
                $state_code = strtolower($result[0]);
                $result->free();
                $sql ="SELECT `state_city_code` FROM `YP_US_state_city` WHERE `parent_id`='{$state_id}' ORDER BY  `YP_US_state_city`.`state_city_title` ASC";
                $result_city = $db->query($sql);
                if (!$result_city)  { 
                    printf("[%d] %s\n", $db->errno, $db->error); 
                    exit();
                }
                if ( $result_city->num_rows==0 ) {
                    echo "there no any results received";
                    return false;
                }    
                $i = "0";
                $StateCity = "";
                while ($row = $result_city->fetch_array(MYSQL_BOTH)){
                    $StateCity[$i] = strtolower($row["state_city_code"])."-".$state_code;
                    //echo $StateCity[$i]."<br />";
                    $i++;
            }
                return $StateCity;
                $result->free();
        }
        else{ return false; }
    }
    public function GetAllCities_byStateID($db,$StateID){
        if (empty($db) || empty($StateID)){
            exit("DB and stateCode needs to be provided");
        }
        if (preg_match("/^[0-9]{1,2}$/", $StateID)){
            $sql ="SELECT * FROM `YP_US_state_city` WHERE `parent_id`='{$StateID}' ORDER BY `state_city_title`";
            $result = $db->query($sql);
                if (!$result)  { 
                    printf("[%d] %s\n", $db->errno, $db->error); 
                    exit();
                }
            if ( $result->num_rows==0 ) {
                    //echo "there no any results received";
                    return false;
                }       
            while($row = $result->fetch_array())
                    $rows[] = $row;
            return $rows;
            $result->free();
        }
    }
    public function GetData_fromIDs_array($db,$IDs_array){
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
        $sql ="SELECT * FROM `YP_US_state_city` WHERE `id` IN (".trim($string, ",").") ORDER BY `state_city_title` ASC";
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
    
}

?>
