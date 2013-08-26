<?php
session_start();
require_once('main.php');
$db = DB1::getInstance();
$UserAccess = new UserAccess();
$err_warn = "";
if (!empty( $_POST['login']) && !empty( $_POST['passwd'])){
    $post_login = trim($_POST['login']);
    $passwd_md5 = md5(md5(trim($_POST['passwd'])));
    $user_arr = $UserAccess->GetUserData($db,$post_login,$passwd_md5);
    if ( !empty($user_arr) ){
            if (is_array($user_arr)){
                $yp_secret_hash = md5($secret_hash.$user_arr['username'].$user_arr['email'].$user_arr['passwd']);
                if(!empty($_POST['remember']) && ($_POST['remember']) == "on") {
                    setcookie('ypusername', $user_arr['username'], time()+60*60*24*5);
                    setcookie('ypid', $user_arr['id'], time()+60*60*24*5);
                    setcookie('yphash', $yp_secret_hash, time()+60*60*24*5);
                }
                else{
                    if(!empty($_COOKIE['ypusername'])) {
                        $past = time() - 3600*24*30*12;
                        setcookie('ypusername', "", $past);
                        setcookie('ypid', "", $past);
                        setcookie('yphash', "", $past);                        
                        exit;
                        }
                }
                $_SESSION['user'] = $user_arr['username'];
                $_SESSION['email'] = $user_arr['email'];
                $_SESSION['level'] = $user_arr['level'];
                $_SESSION['changed'] = $user_arr['changed'];
                // send user to targeted page
                if ( $_SESSION['level'] >= 10 ){
                    header('Location: dashboard.php', true, 302);
                    exit();
                }
                else{
                    header('Location: user.php', true, 302);
                    exit();
                }
                //$err_warn .= "U have logged in as ".$_SESSION['user']."<br />";
//                foreach ($_SESSION as $key=>$value){
//                    $err_warn .= $key." = ".$value."<br />";
//                }
//                foreach ($_COOKIE as $key=>$value){
//                    $err_warn .= $key." = ".$value."<br />";
//                }   
          }
    }
    else {
        $err_warn .= "Wrong username or passwd";
    }
}
elseif ( (!empty($_SESSION['user'])) && (!empty($_SESSION['email'])) && (!empty($_SESSION['level'])) ){ 
    if ( $_SESSION['level'] >= 5 ){
          header('Location: dashboard.php', true, 302);
          exit();
    }
    else{
          header('Location: user.php', true, 302);
          exit();
    }
    //$err_warn = "U have logged in as ".$_SESSION['user']."<br />";
}
elseif ( !empty($_COOKIE['ypid']) && !empty($_COOKIE['yphash'])){
    $user_data = $UserAccess->GetUserData_by_id($db,$_COOKIE['ypid']);
    if ( is_array($user_data) ){
        $cookie_hash = md5($secret_hash.$user_data['username'].$user_data['email'].$user_data['passwd']);
        if ( $_COOKIE['yphash'] == $cookie_hash ){
            $_SESSION['user'] = $user_data['username'];
            $_SESSION['email'] = $user_data['email'];
            $_SESSION['level'] = $user_data['level'];
            $_SESSION['changed'] = $user_data['changed'];
                if ( $_SESSION['level'] >= 10 ){
                    header('Location: dashboard.php', true, 302);
                    exit();
                }
                else{
                    header('Location: user.php', true, 302);
                    exit();
                }
            // send user to targeted page
            //$err_warn .= "U have logged in as ".$_SESSION['user']."<br />";
            //foreach ($_SESSION as $key=>$value){
            //            $err_warn .= $key." = ".$value."<br />";
            //        }
        }
    }
    //foreach ($_COOKIE as $key=>$value){
       // $err_warn .= $key." = ".$value."<br />";
    //}
//    }
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Login to YP Grabber</title>
<meta name="keywords" content="nokeywords" />
<meta name="description" content="nodescription" />
<link href="css/login.css" rel="stylesheet" type="text/css" />
<script src="./js/jquery_v1.7.2.js" type="text/javascript"></script>
<script type="text/javascript">
$(document).ready(function(){
 if($('#remember').is(':checked')) {
        //alert('checked');
        document.getElementById("rememberdiv").className = "rememberdivchecked";
    }
    else{
        //alert('not checked');
        document.getElementById("rememberdiv").className = "rememberdiv";
    }   

$('#remember').change(function(){
        if(this.checked) {
		document.getElementById("rememberdiv").className = "rememberdivchecked";
            }else{
        document.getElementById("rememberdiv").className = "rememberdiv";
    }   
      })					
});
</script>
</head>
    <html>
<body>
<div id="loginCont">
<div id="loginSpacer">
    <div id="messagediv"><?php echo $err_warn; ?></div>
         </div>
<div id="loginHere">
  <div id="loginFormDiv">
    <form id="loginForm" action="<?php echo $_SERVER['PHP_SELF'];?>" method="post">
    <input id="login" class="Linput" type="text"  name="login" onfocus="if(this.value=this.defaultValue){this.value=''}; return false;" value="username@YourDomain.com" />
    <input id="passwd" class="Linput" type="text" name="passwd" onfocus="if(this.value=this.defaultValue){this.value='';this.type='password'}; return false;" value="Your Password here"/>
    <div id="rememberdiv" class="rememberdiv">
<ul><li>
    <input type="checkbox" name="remember" id="remember" <?php if(!empty($_COOKIE['ypusername'])) {
		echo 'checked="checked"';
	}
	else {
		echo '';
	}
	?> > <label for="remember">Remember Me</label></li>
</ul></div>
    <input id="Lsubmit"  type="submit" value="LOGIN" />
    
    </form>
  </div>
</div>
</div>
</body>
    </html>
<?php
$db->close();
?>