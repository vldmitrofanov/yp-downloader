<?php
// main.php
set_time_limit(0);
error_reporting(E_ALL);
//$site_dir = "YP_downloader_Beta_0_1";
//$files_root = $_SERVER["DOCUMENT_ROOT"]."/".$site_dir;
//require_once ($files_root.'/classes/class.YpUsBiz.php');

// Salt for loginpage
$secret_hash = "TRUng5dl9HDRnsor7vb4rc";

//Settings:
require_once (dirname(__FILE__) . '/YP_config.php');
// CLASSES:
require_once (dirname(__FILE__) . '/classes/class.YpUsBiz.php');
require_once (dirname(__FILE__) . '/classes/class.CurlParser.php');
require_once (dirname(__FILE__) . '/classes/class.YpMysqli.php');
require_once (dirname(__FILE__) . '/classes/class.UserAccess.php');
// Classes to work with USA leads
require_once (dirname(__FILE__) . '/classes/MySQL/class.CityStateUSA.php');
require_once (dirname(__FILE__) . '/classes/MySQL/class.IndustryUSA.php');
require_once (dirname(__FILE__) . '/classes/MySQL/class.LeadsLoaderUSA.php');

// TEMPLATE
$template_p = "templatemo";
require_once (dirname(__FILE__) . '/templates/'.$template_p.'/cabinet_header.php');
require_once (dirname(__FILE__) . '/templates/'.$template_p.'/cabinet_content.php');
require_once (dirname(__FILE__) . '/templates/'.$template_p.'/cabinet_sidebar.php');
require_once (dirname(__FILE__) . '/templates/'.$template_p.'/cabinet_footer.php');

// Constants:
// These constants are using in HTML header:
define('_TOP_LOGO_HTML_',$_SERVER['PHP_SELF']);
define('_YP_WWW_URL_', "http://www.yellowpages.com");
define('_TOOLS_', "Tools");
define('_TOOLS_HTML_', "tools.php");
define('_TOOLS_SUBMEN1_', "Load proxy list");
define('_TOOLS_SUBMEN1_HTML_', "loadProxy.php");
define('_TOOLS_SUBMEN2_', "PHP info");
define('_TOOLS_SUBMEN2_HTML_', "info.php");
define('_TOOLS_SUBMEN3_', "phpMyAdmin");
define('_TOOLS_SUBMEN3_HTML_', "MyAdmin.php");
define('_TOOLS_SUBMEN4_', "Admin settings");
define('_TOOLS_SUBMEN4_HTML_', "admin_settings.php");
define('_TOOLS_SUBMEN5_', "Load proxy list");
define('_TOOLS_SUBMEN5_HTML_', "loadProxy.php");
define('_TOOLS_SUBMEN6_', "Load proxy list");
define('_TOOLS_SUBMEN6_HTML_', "loadProxy.php");
?>