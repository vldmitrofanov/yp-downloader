<?php
session_start();
require_once('main.php');
$db = DB1::getInstance();
$UserAccess = new UserAccess();
if ( empty($_SESSION['level']) || $_SESSION['level'] < 4 ){
    header ('Location: login.php', true, 302);
    exit();
}
if ( $_SESSION['level'] >= 4 ){
    cabinet_html_head("YP GRABBER user profile","Yellow Pages","YP GRABBER User profile","");
    cabinet_header("");
    cabinet_content_top();
    if ( !empty($_POST['oldpasswd']) || !empty($_POST['newpasswd1']) || !empty($_POST['newpasswd2'])){
        
        if ($_POST['newpasswd1']!=$_POST['newpasswd2']){
            echo "New passwords are mismatch";
            cabinet_content_bottom();
            cabinet_sidebar();
            cabinet_footer();
            exit();
        }
        else{
            $newPasswd = $UserAccess->UserChangePasswd($db, $_SESSION['user'], $_POST['oldpasswd'], $_POST['newpasswd1']);
            if ($newPasswd){
                echo"<h2>Password has been changed</h2>";
//                if (!empty($_SESSION['user'])){
//                    session_destroy(); 
//                    }
//                if((!empty($_COOKIE['ypusername'])) || (!empty($_COOKIE['ypusername'])) ){
//                    $past = time() - 3600*24*30*12;
//                    setcookie('ypusername', "", $past);
//                    setcookie('ypid', "", $past);
//                    setcookie('yphash', "", $past);                    
//                    exit;
//                }
                echo "<script language = 'javascript'>
                     var delay = 800;
                     setTimeout(\"document.location.href='logout.php'\", delay);
                     </script>";
                cabinet_content_bottom();
                cabinet_sidebar();
                cabinet_footer();
                exit();
                }
             else {
                echo"<h2>Password not changed</h2>";
                echo "probably, your Old password was wrong";
                cabinet_content_bottom();
                cabinet_sidebar();
                cabinet_footer();
                exit(); 
             }
            }
        }
    else{
?>
<div id="reset_passwd">
    <b>Reset password for <?php echo $_SESSION['user'];?></b>
<form id="resetPasswdForm" action="<?php echo $_SERVER['PHP_SELF'];?>" method="post">
    <div class="pasreset">
<label>Old Password</label>
<input id="oldpasswd" class="pasreset" type="text"  name="oldpasswd" onfocus="if(this.value=this.defaultValue){this.value='';this.type='password'}; return false;" value="*** Old Password ***" />
  <br />
<label>New Password</label>
<input id="newpasswd1" class="pasreset" type="text" name="newpasswd1" onfocus="if(this.value=this.defaultValue){this.value='';this.type='password'}; return false;" value="* New Password here *" disabled="disabled"/>
  <br />
<label class="pasreset">New Password again</label>
<input id="newpasswd2" class="pasreset" type="text" name="newpasswd2" onfocus="if(this.value=this.defaultValue){this.value='';this.type='password'}; return false;" value="* New Password repeat *" disabled="disabled"/>
  <br />
    </div>
<input id="passubmit" class="pasubmt" type="submit" value="Reset Password" disabled="disabled"/>
</form>
</div>
<script type="text/javascript">
    $(document).ready(function(){
       $("#oldpasswd").keypress(function(){
            $("#newpasswd1").removeAttr('disabled');
       }); 
       $("#newpasswd1").keypress(function(){
            $("#newpasswd2").removeAttr('disabled');
       });
       $("#newpasswd2").keypress(function(){
            $("#passubmit").removeAttr('disabled');
       }); 
       $("#resetPasswdForm").submit(function(){
           if ($("#newpasswd1").val() != $("#newpasswd2").val()){
               alert('Both new passwords are mismatch');
               $("#resetPasswdForm").each (function() { this.reset(); });
               window.location = 'profile.php?action=change_passwd';
               return false;
        }
       });
    });
</script>
<?php
    cabinet_content_bottom();
    cabinet_sidebar();
    cabinet_footer();
        }
}
else{
    header('Location: login.php', true, 302);
}
?>