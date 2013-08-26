<?php
session_start();
if (!empty($_SESSION['user'])){
   session_destroy(); 
}
if((!empty($_COOKIE['ypusername'])) || (!empty($_COOKIE['ypusername'])) ){
       $past = time() - 3600*24*30*12;
       setcookie('ypusername', "", $past);
       setcookie('ypid', "", $past);
       setcookie('yphash', "", $past);
       header('Location: login.php', true, 302);
       exit;
}
header ('Location: login.php', true, 302);
?>
