<?php
session_start();
require_once 'main.php';
$db = DB1::getInstance();
$db2 = DB2::getInstance();
if ( empty($_SESSION['level']) || $_SESSION['level'] < 4 ){
    header ('Location: login.php', true, 302);
    exit();
}
$cabinet_content ="";
$cabinet_content .= "<h3><i>Notice:</i></h3>
    Don't forget to add these entries to your crontab:<br />
    If you don't need logs from executing background scripts, add this:<br /><br />".
    '<code>*/5 * * * * /usr/local/bin/php -f '.dirname(__FILE__)."/bg_run_controller.php > /dev/null</code><br />".
    '<code>0 */12 * * * /usr/local/bin/php -f '.dirname(__FILE__)."/proxy_curl_loader.php > /dev/null</code><br />
    <br />But if you want to debug the software, replace: <br /><code>> /dev/null</code><br /> 
    to <br /><code>>> " .dirname(__FILE__)."/bg_run_controller.log</code><br /> and <br />
        <code>>> ".dirname(__FILE__)."/proxy_curl_loader.log</code><br /> respectively.
             To manage your cron schedules, type <code>crontab -e</code> in command shell. Then insert these stings and save.
             After you close the editor, cron will install changes automatically<br />
            <br />";
$cabinet_content .= "<i>Debugging information:</i><br />";
$cabinet_content .= "<textarea rows=\"10\" style=\"width:100%;\" disabled=\"disabled\">";
foreach ($_SESSION as $key=>$value){
      $cabinet_content .= '$_SESSION '.$key." = ".$value."\n";
      } 
foreach ($_POST as $key=>$value){
      $cabinet_content .= '$_POST '.$key." = ".$value."\n";
      }   
foreach ($_COOKIE as $key=>$value){
      $cabinet_content .= '$_COOKIE '.$key." = ".$value."\n";
      }   
$cabinet_content .= "username is ".$_SESSION['user'];
$cabinet_content .= "</textarea>";
if ( empty($_POST['hide_interface']) && empty($_GET['hide_interface'])){
    cabinet_html_head("YP GRABBER Dashboard","Yellow Pages","YP GRABBER Dashboard","");
    cabinet_header("show_YP_predict_search");
}
if ( !empty($_GET['action'])){
    if ( empty($_POST['hide_interface'])&& empty($_GET['hide_interface'])){
        cabinet_content_top();
    }
    switch ($_GET['action']){
        case "prepare_request":
            define('INCLUDING',true);
            echo "<h3>Selecting area and directory</h3>";
            include 'prepare_request.php';
            break;
        case "usaBuz":
            //show_YP_predict_search();
            $USAbus_cont = "<h2>USA Business Directory</h2>";
            $USAbus_cont .= "<a href=\"dashboard.php?action=prepare_request\">Load Business LEADS from YP</a><br /><br />";
            $USAbus_cont .= "<a href=\"multiselect_request.php\">Bulk Load Business LEADS from YP 
                </a> (Select multiple city and industries and enqueue your request).<br /><br />";
            $USAbus_cont .= "<a href=\"request_byZipUSAbiz.php\">Bulk load Business LEADS by zipcode list from YP</a><br /><br />";
            $USAbus_cont .= "<h4>Database</h4>";
            $USAbus_cont .= "<a href=\"dashboard.php?action=delete_duplicates\">Delete duplicate entries from Leads Database</a><br /><br />";
            echo $USAbus_cont;
            echo "<a href=\"dashboard.php?action=show_unfinished_queues&hide_sidebar=1\">Show inactive queues</a> <br /><br />";
            echo "<a href=\"dashboard.php?action=show_all_queues&hide_sidebar=1\">Show active queues</a> <br />";
            break;
        case "show_unfinished_queues":
            $sql = "SELECT * FROM `YP_US_industry_done` 
                WHERE `is_finished`='no' AND `enqueued`='no' 
                AND `date_done`> SUBDATE(now(),30) AND `date_done`< SUBDATE(now(), INTERVAL '360' MINUTE)";
            $result = $db->query($sql);
                    if (!$result)  { 
                            printf("[%d] %s\n", $db->errno, $db->error); 
                            exit();
                    }
            if ( $result->num_rows==0 ) {
                    echo "There no any inactive queues <br /><br />";
                    echo "<input type=\"button\" value=\"Back\" onclick=\"goBack()\">";
                    exit();
                } 
            else {
                while($row = $result->fetch_array())
                    $rows[] = $row;
                foreach($rows as $row){
                    $short_url = substr($row['url'],27,strlen($row['url']));
                    echo "<script type=\"text/javascript\">
                    $(document).ready(function(){
                    $('#Activate".$row['record_id']."').click(function(){
                                       $.ajax({  
                                        type: \"GET\",  
                                        url: \"dashboard.php\",  
                                        data: \"action=requeue&id=".$row['record_id']."&hide_interface=1\",  
                                        success: function(html){  
                                            $('#qinfo".$row['record_id']."').html(html);
                                            $('#q".$row['record_id']."').hide();
                                        }  
                                        });  
                                    });
                    $('#Delete".$row['record_id']."').click(function(){
                                       $.ajax({  
                                        type: \"GET\",  
                                        url: \"dashboard.php\",  
                                        data: \"action=delqueue&id=".$row['record_id']."&hide_interface=1\",  
                                        success: function(html){  
                                            $('#qinfo".$row['record_id']."').html(html);
                                            $('#q".$row['record_id']."').hide();
                                        }  
                                    });  
                              });
                    });
                </script>";
                echo "<div id =\"qinfo".$row['record_id']."\"></div>
                        <div id=\"q".$row['record_id']."\">
                        <table class=\"queuemanage0\"><tr>
                        <td class=\"queueid\"><i>Queue ID:</i> ".$row['record_id'].",</td>
                            <td class=\"pagesloaded\"><i>Pages loaded: </i>".$row['pages_done'].",</td>
                                <td><i>URL: <i>..".$short_url."</td>
                        <td class=\"linksq\"><a href=\"#\" id=\"Activate".$row['record_id']."\">Activate</a></td>
                        <td class=\"linksq\"><a href=\"#\" id=\"Delete".$row['record_id']."\">Delete</a></td>
                        </tr></table></div>";
                    }
            }
            break;
            case "show_all_queues":
                $sql = "SELECT * FROM `YP_US_industry_done` 
                    WHERE `is_finished`='no' AND `enqueued`='yes' ORDER BY `record_id` DESC";
                $result = $db->query($sql);
                if (!$result)  { printf("[%d] %s\n", $db->errno, $db->error); exit();}
                if ( $result->num_rows==0 ) {
                        echo "There no any active queues <br /><br />";
                        echo "<input type=\"button\" value=\"Back\" onclick=\"goBack()\">";
                        exit();
                    } 
                else {
                    while($row = $result->fetch_array())
                        $rows[] = $row;
                    foreach($rows as $row){
                        $short_url = substr($row['url'],27,strlen($row['url']));
                        echo "<script type=\"text/javascript\">
                                $(document).ready(function(){
                                $('#Deactivate".$row['record_id']."').click(function(){
                                                   $.ajax({  
                                                    type: \"GET\",  
                                                    url: \"dashboard.php\",  
                                                    data: \"action=deactivate&id=".$row['record_id']."&hide_interface=1\",  
                                                    success: function(html){  
                                                        $('#qinfo".$row['record_id']."').html(html);
                                                        $('#q".$row['record_id']."').hide();
                                                    }  
                                                    });  
                                                });
                                $('#Delete".$row['record_id']."').click(function(){
                                                   $.ajax({  
                                                    type: \"GET\",  
                                                    url: \"dashboard.php\",  
                                                    data: \"action=delqueue&id=".$row['record_id']."&hide_interface=1\",  
                                                    success: function(html){  
                                                        $('#qinfo".$row['record_id']."').html(html);
                                                        $('#q".$row['record_id']."').hide();
                                                    }  
                                                });  
                                          });
                                });
                            </script>";
                    echo "<div id =\"qinfo".$row['record_id']."\"></div>
                            <div id=\"q".$row['record_id']."\">
                            <table class=\"queuemanage0\"><tr>
                            <td class=\"queueid\"><i>Queue ID:</i> <b>".$row['record_id']."</b>,</td>
                                <td class=\"pagesloaded\"><i>Pages loaded: </i><b>".$row['pages_done']."</b>,</td>
                                <td><i>URL:</i> ..".$short_url."</td>
                            <td class=\"linksq\"><a href=\"#\" id=\"Deactivate".$row['record_id']."\">Deactivate</a></td>
                            <td class=\"linksq\"><a href=\"#\" id=\"Delete".$row['record_id']."\">Delete</a></td>
                            </tr></table></div>";
                        }
                }
            break;
            case "requeue":
                if (!empty($_GET['id']) && preg_match("/^[0-9]{1,}$/", $_GET['id'])){
                    $sql = "UPDATE `YP_US_industry_done` SET `enqueued`='yes' WHERE `record_id`='{$_GET['id']}'";
                    $result = $db->query($sql);
                    if (!$result)  { 
                            printf("[%d] %s\n", $db->errno, $db->error); 
                            exit();
                    }
                    else{
                    echo "<table class=\"queuemanage0\"><tr>
                            <td class=\"queueid\"><i>Queue ID: </i>".$_GET['id']."</td><td>Activated sucessfuly!!</td>
                                </tr></table>";
                        //echo "<input type=\"button\" value=\"Back\" onclick=\"goBack()\">";
                    }              
                }
                else{
                     exit("ID is not valid");
                 }
            break;
            case "deactivate":
                if (!empty($_GET['id']) && preg_match("/^[0-9]{1,}$/", $_GET['id'])){
                    $sql = "UPDATE `YP_US_industry_done` SET `enqueued`='no' WHERE `record_id`='{$_GET['id']}'";
                    $result = $db->query($sql);
                    if (!$result)  { printf("[%d] %s\n", $db->errno, $db->error); exit();}
                    else{
                        echo "<table class=\"queuemanage0\"><tr>
                            <td class=\"queueid\"><i>Queue ID: </i>".$_GET['id']."</td><td> set as inactive!</td>
                                </tr></table>";
                        //echo "<input type=\"button\" value=\"Back\" onclick=\"goBack()\">";
                    }              
                }
                else{
                     exit("ID is not valid");
                 }
            break;
            case "delqueue":
                if (!empty($_GET['id']) && preg_match("/^[0-9]{1,}$/", $_GET['id'])){
                    $sql = "DELETE FROM `YP_US_industry_done`  WHERE `record_id`='{$_GET['id']}'";
                    $result = $db->query($sql);
                    if (!$result)  { printf("[%d] %s\n", $db->errno, $db->error); exit();}
                    else{
                        echo "<table class=\"queuemanage0\"><tr>
                            <td class=\"queueid\"><i>Queue ID: </i>".$_GET['id']."</td><td><font color=\"red\">has been removed!</font></td>
                                </tr></table>";
                        //echo "<input type=\"button\" value=\"Back\" onclick=\"goBack()\">";
                    }              
                }
                else{
                     exit("ID is not valid");
                 }
            break;
            case "delete_duplicates":
                $LeadsLoaderUSA = new LeadsLoaderUSA();
                $DeleteDuplicates = $LeadsLoaderUSA->DeleteDuplicates($db2);
                echo "Found ".$DeleteDuplicates." duplicates";
            break;
        default:
            echo "Hello ";
    }
    if ( empty($_POST['hide_interface']) && empty($_GET['hide_interface'])){
        cabinet_content_bottom();
    }
}
else{
    if ( empty($_POST['hide_interface']) && empty($_GET['hide_interface'])){
        cabinet_content($cabinet_content);
    }
}
if ( empty($_POST['hide_interface']) && empty($_GET['hide_interface'])){
    if (empty($_GET['hide_sidebar'])){
        cabinet_sidebar();
    }
    cabinet_footer();
}
$db->close();
$db2->close();
?>