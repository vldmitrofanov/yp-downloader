<?php

class UserAccess extends YpMysqli{
     public function UserChangePasswd($db,$user,$oldpasswd,$newpasswd){
        // changes passwd for username or email
        //Checking for requirements
        if ( empty($db) ){
            exit("no database privided");
        }
        if ( empty($user) ){
            exit("no username was given");
        }
        if ( empty($oldpasswd) ){
            exit("You need to specify the Old Password");
        }
        if ( empty($newpasswd) ){
            exit("You need to specify the Old Password");
        }
        if ( $oldpasswd == $newpasswd ){
            exit( "New and Old passwords MUST be different");
        }
        
        // Check, if email was provided instead of user:
        if (filter_var($user,FILTER_VALIDATE_EMAIL) === true){
            $MySQL_usrename = "email";
        }
        else {
           $MySQL_usrename = "username"; 
           $user = $this->CheckString($user);
           if ( !$user){
               exit ( "wrong characters detected in username");
           }
        }        
            $oldpasswd_hash = md5(md5(trim($oldpasswd)));
            //echo $MySQL_usrename;
            $sql = "SELECT * FROM `yp_users` WHERE {$MySQL_usrename}='{$user}' AND `passwd`='{$oldpasswd_hash}' LIMIT 0,1";
            $result = $db->query($sql);
                if (!$result)  { 
                    printf("[%d] %s\n", $db->errno, $db->error); 
                    exit();
                }
            if ($result->num_rows > 0) {
                $newpasswd_hash = md5(md5(trim($newpasswd)));
                $sql = "UPDATE `yp_users` SET `passwd`='{$newpasswd_hash}' WHERE {$MySQL_usrename}='{$user}' AND `passwd`='{$oldpasswd_hash}'";
                $result = $db->query($sql);
                if (!$result)  { 
                    printf("[%d] %s\n", $db->errno, $db->error); 
                    exit();
                }
                else return true;
            }            
        }
        
    public function GetUserData($db,$username,$passwd){
        if ( ( preg_match("/^[A-Za-z]+[A-Za-z0-1-_]{1,}$/",$username)) || (filter_var($username,FILTER_VALIDATE_EMAIL) === true) ){
            $username = mysql_real_escape_string($username);
            $passwd = mysql_real_escape_string($passwd);
            $sql = "SELECT * FROM `yp_users` 
                    WHERE ( `username`='{$username}' OR `email`='{$username}' )
                    AND `passwd`='{$passwd}';";  
            $result = $db->query($sql);
            if (!$result)  { 
                printf("[%d] %s\n", $db->errno, $db->error); 
                exit();
            }
            if ( $result->num_rows == 0) {
                return false;
            }
            elseif ( $result->num_rows > 1 ){
                return "multiemail";
            }
            else {
                $row = $result->fetch_assoc();
                return $row;
            }
        }
    }
    public function GetUserData_by_id($db,$id){
        if ( preg_match("/^[0-9]{1,}$/", $id)){
            $sql = "SELECT * FROM `yp_users` 
                    WHERE `id`='{$id}' LIMIT 0,1;";  
            $result = $db->query($sql);
            if (!$result)  { 
                printf("[%d] %s\n", $db->errno, $db->error); 
                exit();
            }
            if ( $result->num_rows == 1 ){
                $row = $result->fetch_assoc();
                return $row;
            }
            else {
                return false;
            }
        }
    }
}
?>
