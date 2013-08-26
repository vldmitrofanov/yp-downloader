<?php

class LeadsLoaderUSA extends YpMysqli{
    protected $US_Leads_table = _LEADS_DB_USA_BUZ_TABLE_;
    public function LoadBusLeads_2DB($leads_db,$array){
        if (is_array($array)){
            $last = count($array) - 1;
        if ( !isset($array[$last]['Industry'])){
                unset($array[$last]);
                reset($array);
            }
        $i=0;  
        while ($i < count($array)) {
            $sql = sprintf("INSERT INTO `".$this->US_Leads_table."`
                   (`company_name`,`address`,`city`,`state`,`zip`,`phone`,`industry`)
                    VALUES ('%s','%s','%s','%s','%s','%s','%s')",
                    $leads_db->real_escape_string($array[$i]['Company']),
                    $leads_db->real_escape_string($array[$i]['Address']),
                    $leads_db->real_escape_string($array[$i]['City']),
                    $leads_db->real_escape_string($array[$i]['State']),
                    $leads_db->real_escape_string($array[$i]['Zip']),
                    $leads_db->real_escape_string($array[$i]['Phone']),
                    $leads_db->real_escape_string($array[$i]['Industry']));
                     
            $result = $leads_db->query($sql);
            if (!$result) {
                        printf("[%d] %s\n", $leads_db->errno, $leads_db->error);
                        exit;
                }
            else { 
                $i++;
                }
            }
            if ( ($i > 0) && ($i == count($array)) ){
                return true;
            }
        }
    }
    public function DeleteDuplicatesNEW($leads_db){
        $sql = "SELECT COUNT( * ) 
                FROM  `".$this->US_Leads_table."` AS t,  `".$this->US_Leads_table."` AS t2
                WHERE t.id != t2.id
                AND t.company_name = t2.company_name
                AND t.address = t2.address
                AND t.phone = t2.phone
                LIMIT 100";
        if( $result = $leads_db->query($sql) ){
            if( $result->num_rows == 0){
                return false;
                //echo "no duplicates found";
            }
            else{                          
            $count = $result->fetch_row();
            return $count[0];
            //echo $count[0];
            }
        }
    }

     public function DeleteDuplicates($leads_db){
         
         $sql ="SELECT phone,address,company_name, count( * ) cnt 
                FROM  `".$this->US_Leads_table."`
                GROUP BY phone
                HAVING cnt >1
                ORDER BY cnt DESC
                LIMIT 100";
         for(;;){
        // (re)prepare the query
                if( $result = $leads_db->query($sql) ){
                    if( $result->num_rows == 0){
                        return false;
                        exit("no duplicates found");
                     }
                    $rows = array();
        // fetch the rows
                    while ($row = $result->fetch_assoc()){
                        $rows[] = $row;
                    }
        // free the result.
                    $result->free();
                }
                else{
                        printf("[%d] %s\n", $leads_db->errno, $leads_db->error);
                        exit;
                }
        // delete all the values
                foreach( $rows as $row ){
        // delete one row less than the total number of rows.
                    $sql2 = "DELETE FROM `".$this->US_Leads_table."` 
                               WHERE `phone`='{$leads_db->real_escape_string($row['phone'])}' AND
                               `address` = '{$leads_db->real_escape_string($row['address'])}' AND
                               `company_name` = '{$leads_db->real_escape_string($row['company_name'])}'
                               LIMIT " .((int)($row['cnt'] - 1));
                    if( $result2 = $leads_db->query($sql2)){
                        echo "Removed :: ".$row['phone']." :: ".$row['address']." :: ".$row['company_name']." ::<b>
                             " .((int)($row['cnt'] - 1))."</b>  times out of ". $row['cnt'] ."<br />";
                        
                    }
                    else {
                        printf("[%d] %s\n", $leads_db->errno, $leads_db->error);
                        exit;
                    }
                }  
        }
     }
     
// END OF THIS CLASS
}
?>
