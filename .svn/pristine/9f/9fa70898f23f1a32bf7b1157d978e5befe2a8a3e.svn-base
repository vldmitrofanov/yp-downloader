<?php
//loadProxy.php
//Test:
//<form action="post_form.php" method="post">
//prod:
//<form action="proxyLoader.php" method="post">
session_start();
require_once('main.php');
if ( empty($_SESSION['level']) || $_SESSION['level'] < 4 ){
    header ('Location: login.php', true, 302);
    exit();
}
if ( $_SESSION['level'] >= 4 ){
    cabinet_html_head("YP GRABBER proxy loader","Yellow Pages","YP GRABBER proxy loader","");
    cabinet_header("");
?>
<p>Go grab proxy list from <a href="http://spys.ru/proxylist/" target="_blank">spys.ru</a></p>
<form action="proxyLoader.php" method="post">
  <textarea name="proxy" cols=120 rows=30 wrap="off" onfocus="if(this.value=this.defaultValue){this.value=''}; return false;" value="Put here content from spys.ru">
  Put here content from spys.ru
  </textarea>
  <P><input type="submit" value="Load Proxy"><input type="reset">
</form>
<?php
    cabinet_footer();
}
else{
    echo "You have no rights to add city";
}
?>

