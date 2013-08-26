<?php
class CurlParser {
    //public $data = "";
    public $curl_error_msg = "<h3>FATAL ERROR:</h3>
        <p><font color=\"red\"><b>Seems like you don't have PHP-CURL in your system!!
        You must have it installed to run this script!
        <br /><i>For example: you can install it on Ubuntu by running:</i><br />
        <code>sudo apt-get install php5-curl</code></b></font></p>";
    public function __construct() {
        if (!function_exists('curl_version')){
            exit($this->curl_error_msg);
        }
    }
    public static function get_curl_data($url,$proxy_ip,$proxy_port,$connect_timeout,$proxy_loginpassw){
        if (!function_exists('curl_version')){
            exit("<h3>FATAL ERROR:</h3>
        <p><font color=\"red\"><b>Seems like you don't have PHP-CURL in your system!!
        You must have it installed to run this script!
        <br /><i>For example: you can install it on Ubuntu by running:</i><br />
        <code>sudo apt-get install php5-curl</code></b></font></p>");
        }
        if ( empty($url) ){ die( "URL is not SET!");}
        if ( !isset($proxy_ip) ){ $proxy_ip = "" ;}
        if ( !isset($proxy_port) ){ $proxy_port = "80" ;}
        if ( !isset($proxy_loginpassw) ){ $proxy_loginpassw = "" ;}
        if ( !isset($connect_timeout)){ $connect_timeout = 5; }
        //$loginpassw = 'login:password';  //your proxy login and password here
        //$proxy_ip = '87.221.76.177'; //proxy IP here
        //$proxy_port = 80; //proxy port from your proxy list
        $agent= 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; .NET CLR 1.0.3705; .NET CLR 1.1.4322)';
        //$url = 'http://www.yellowpages.com/san-francisco-ca/automotive-repair?page=1'; //URL to get
 
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, 1); // no headers in the output
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // output to variable
        if (defined(_CURL_COOKIES_FILE_)){
            $cookie_file = _CURL_COOKIES_FILE_;
            if ( !file_exists($cookie_file) ) { 
                    $f = fopen($cookie_file, "a+");
                    fclose($f);
                    chmod($cookie_file, 0777); 
                } 
             curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_file );
             curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_file );
        }
        curl_setopt($ch, CURLOPT_USERAGENT, $agent);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT,$connect_timeout);
        if ( $proxy_ip != ""){
            curl_setopt($ch, CURLOPT_PROXYPORT, $proxy_port);
            curl_setopt($ch, CURLOPT_PROXYTYPE, 'HTTP');
            curl_setopt($ch, CURLOPT_PROXY, $proxy_ip);        
            if ( $proxy_loginpassw != ""){
                curl_setopt($ch, CURLOPT_PROXYUSERPWD, $proxy_loginpassw);
            }
        }
        $data = curl_exec($ch);
        curl_close($ch);
        return $data;
    }
    
    public static function get_google($proxy_ip,$proxy_port){
        if (!function_exists('curl_version')){
            exit("<h3>FATAL ERROR:</h3>
        <p><font color=\"red\"><b>Seems like you don't have PHP-CURL in your system!!
        You must have it installed to run this script!
        <br /><i>For example: you can install it on Ubuntu by running:</i><br />
        <code>sudo apt-get install php5-curl</code></b></font></p>");
        }
        $agent= 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; .NET CLR 1.0.3705; .NET CLR 1.1.4322)';
        $url = "http://www.google.com";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_HEADER, 1); // no headers in the output
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // output to variable
        curl_setopt($ch, CURLOPT_USERAGENT, $agent);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT,5);
        curl_setopt($ch, CURLOPT_PROXYPORT, $proxy_port);
        curl_setopt($ch, CURLOPT_PROXYTYPE, 'HTTP');
        curl_setopt($ch, CURLOPT_PROXY, $proxy_ip);        
        $data = curl_exec($ch);
        curl_close($ch);
        return $data;
        //echo $data;
    }
    
    // Function to check response time
    public static function pingDomain($domain){
        $starttime = microtime(true);
        $file      = fsockopen ($domain, 80, $errno, $errstr, 10);
        $stoptime  = microtime(true);
        $status    = 0;

        if (!$file) $status = -1;  // Site is down
        else {
            fclose($file);
            $status = ($stoptime - $starttime) * 1000;
            $status = floor($status);
        }
    return $status;
    }
}
?>