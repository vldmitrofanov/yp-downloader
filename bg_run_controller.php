<?php
// bg_run_controller.php
require_once( dirname(__FILE__) . '/main.php' );
$db = DB1::getInstance();
$IndustryUSA = new IndustryUSA();
$num_of_self_threads = exec("ps aux | grep bg_run_controller.php | grep -v grep | wc -l");
//$logfile = dirname(__FILE__) . "/background_run.log";
$logfile = _BG_RUN_LOG_FILE_;
if ( !file_exists($logfile) ) { 
        $msg = "[".date('Y-m-d H:i:s')."] Log file created \n\n ===============================\n\n";
        $f = fopen($logfile, "a+");
        fwrite($f, $msg);
        fclose($f);
        chmod($logfile, 0777); 
    } 
if ($num_of_self_threads > 2){
    echo "[".date('Y-m-d H:i:s')."]".' $num_of_self_threads ='."[".$num_of_self_threads."]\n";
    exit("[".date('Y-m-d H:i:s')."] there is another copy of this script already running \n");
}
$num_of_processes = exec("ps aux| grep background_run.php |grep -v grep | wc -l");
$num_of_queues = $IndustryUSA->GetQueuesNumber_USbiz($db);
$threads_allowed_settings = $IndustryUSA->GetAdminYPsettings_mysqli($db,"threads_allowed","admin");
if ( $num_of_processes >= $threads_allowed_settings ){
    echo "[".date('Y-m-d H:i:s')."] There are ".$num_of_processes." background_run.php processes running. Max allowed only ".$threads_allowed_settings."\n";
    exit();
}
//echo "/usr/local/bin/php -f ".dirname(__FILE__)."/background_run.php >> ".$logfile." & \n";
if ($threads_allowed_settings > 1){
        echo "PROCESSES: ".$num_of_processes."\n";
        echo "QUEUES: ".$num_of_queues."\n";
        echo "ALLOWED THREADS NO :".$threads_allowed_settings."\n";
        if ($num_of_queues == 0 ){
            exit ( "[".date('Y-m-d H:i:s')."] There no any active queues \n");
        }
        elseif ( $num_of_queues == 1){
            shell_exec("/usr/local/bin/php -f ".dirname(__FILE__)."/background_run.php >> ".$logfile." &");
            exit();
        }
        else{
            if (($num_of_queues > $threads_allowed_settings) && ( $num_of_processes < $threads_allowed_settings)){
                    $i=1;
                    while ($i <= $threads_allowed_settings){
                        shell_exec("/usr/local/bin/php -f ".dirname(__FILE__)."/background_run.php >> ".$logfile." &");
                        $i++;
                    }
                }
            elseif(($num_of_queues <= $threads_allowed_settings) && ( $num_of_processes < $threads_allowed_settings)) {
                $i=1;
                while ($i <= $num_of_queues){
                    shell_exec("/usr/local/bin/php -f ".dirname(__FILE__)."/background_run.php >> ".$logfile." &");
                    $i++;
                }
            }
        }    
}
else{
    echo "[".date('Y-m-d H:i:s')."] ERROR: ALLOWED THREADS NO : 0 \n";
}

$db->close();
?>
