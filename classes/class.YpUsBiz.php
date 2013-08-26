<?php
// file class.YpUsBiz.php
// this class is converting received HTML content 
// from YP to structured LEADS
class YpUsBiz{
    protected $leadcounter = 1;
    public static function getYP_US_bizCounter($content){
        // Returns pages amount of current listing by catching results amount 
        // and dividing it by 30. So script is looking for string some like:
        // Results 1-30 of 100500. so total pages amount will be 100500/30
        $matches ="";
        if ( preg_match("/<p class=\"result-count\">Results[\s][0-9-]{1,15}[\s]of[\s][0-9]{1,}<\/p>/Uis",$content, $matches)){
                $results = strip_tags($matches["0"]);
                $results = trim(strstr($results, "of "), "of ");
                $results = intval($results);
                if ( is_numeric($results) ){
                        if ( $results > "30"){
                                $pages = $results / 30;
                                $pages_count = $pages;
                                settype($pages_count,"int");
                                        if ( $pages > $pages_count ){
                                                $pages_count++;
                                        }
                        }
                        else { $pages_count = "1";}
                }
        }
        // If there no pages (0) we'll return 0
        elseif ( preg_match("/<p class=\"result-count\">Results \(0\)<\/p>/Uis", $content, $matches)){
                if ( isset($matches["0"])){
                        $pages_count = 0;   
                }
        }
        // This might ba caused, if our current proxy has been banned by YP
        elseif ( preg_match("/A robot may not injure a human being or/Uis", $content, $matches)){
            $pages_count = "robot";
        }
        // If we lost internet connection/proxy went off
        else { $pages_count = "unreachable";}
        return $pages_count;
    }
    
    public static function getYP_US_bizLeads($content){
        // Main function, parsing leads from given withdrawn html file
        // Notice - returns array
        $content = str_replace("\n","",$content);
        $content = str_replace("\r","",$content);
        $content = str_replace("\t","",$content);
        $content = str_replace("/span>","/span>\n",$content);
        $content = str_replace("<span","\n<span",$content);
        $content = str_replace("/h3>","/h3>\n",$content);
        $content = str_replace("/h1>","/h1>\n",$content);
        $content = str_replace("</div>","</div>\n",$content);
        $content = str_replace("<h3","\n<h3",$content);
        
        $str = explode("\n", $content);
        
        $i = "0";
        $complete = "0";
            foreach($str as $d){
                if (( preg_match("/<h3 class=\"business-name fn org\" data-lid=(.*)<\/h3>/Uis", $d))
                    || ( preg_match("/<h3 class=\"business-title fn org\">(.*)<\/h3>/Uis", $d))
                    || ( preg_match("/<h3 class=\"business-name fn has-menu org\" data-lid=(.*)<\/a>/Uis", $d))){
                        $data[$i]['Company'] = strip_tags($d);
                        $complete = "1";
                }           
                elseif ( preg_match("/<span class=\"street-address\">(.*)<\/span>/Uis", $d)){
                        $data[$i]['Address'] = trim(strip_tags($d),",");
                        if ( isset ($complete)){
                            $complete++;
                        }
                        else {
                            $complete = "0";  
                        }
                }
                elseif ( preg_match("/<span class=\"locality\">(.*)<\/span>/Uis", $d)){
                        $data[$i]['City'] = strip_tags($d);
                        if ( isset ($complete)){
                            $complete++;
                        }
                        else {
                            $complete = "0";  
                        }
                }
                elseif ( preg_match("/<span class=\"region\">[A-Z]{2}<\/span>/Uis", $d)){
                        $data[$i]['State'] = strip_tags($d);
                        if ( isset ($complete)){
                            $complete++;
                        }
                        else {
                            $complete = "0";  
                        }
                }
                elseif ( preg_match("/<span class=\"postal-code\">(\d{5})<\/span>/Uis", $d)) {
                        $data[$i]['Zip'] = strip_tags($d);
                        if ( isset ($complete)){
                            $complete++;
                        }
                        else {
                            $complete = "0";  
                        }
                }
                elseif (( preg_match("/<span class=\"business-phone phone\">.*[0-9]{3}-[0-9]{4}<\/span>/Uis", $d))
                    || ( preg_match("/<span class=\"phone\">.*[0-9]{3}-[0-9]{4}<\/span>/Uis", $d))) {
                        $data[$i]['Phone'] = strip_tags($d);
                        if ( isset ($complete)){
                            $complete++;
                        }
                        else {
                            $complete = "0";  
                        }
                }
                elseif ( preg_match("/<div class=\"categories\">What:(.*)<\/div>/Uis", $d)){
                        $d = str_replace("What:","",strip_tags($d));
                        $d = str_replace("Add Photos","",($d));
                        $data[$i]['Industry'] = str_replace("More Info","",($d));
                        if ( isset ($complete)){
                            $complete++;
                        if( $complete == "7") {
                            $i++;
                        }
                        else {
                            $complete = "0";
                            unset($data[$i]);
                            }
                        }        
                }
           }
        if ($data){
            return $data;
        }
        else{
            echo "couldn't get anythig, sorry";
            return false;
        }
    }
    
    public function PrintLeads($array){
        if (empty($array)){
            exit ("none LEADS to print. exiting");
        }
        $last = count($array) - 1;
        if ( !isset($array[$last]['Industry'])){
                unset($array[$last]);
                reset ($array);
            }
        $i=0;  
        while ($i < count($array)) {
            echo $this->leadcounter.").. ".$array[$i]['Company']."<br /> \n";
            echo "..... ".$array[$i]['Address']."<br /> \n";
            echo "..... ".$array[$i]['City']."<br /> \n";
            echo "..... ".$array[$i]['State']."<br /> \n";
            echo "..... ".$array[$i]['Zip']."<br /> \n";
            echo "..... ".$array[$i]['Phone']."<br /> \n";
            echo "..... ".$array[$i]['Industry']."<br /><br /><br /> \n \n \n";
            $i++;
            $this->leadcounter++;
            }
    }
    
    public static function getYP_US_bizCurrentPage($content){
        // returns current page number. Example: we are serfing pages one by one, 
        // and each pages shows on the top something like "Results 30-60 of 100500"
        // so we need to get that 60 and divide it by 30. this is how we can get current page number
        $matches = "";
        if ( preg_match("/<p class=\"result-count\">Results[\s][0-9-]{1,}[0-9]{1,}[\s]of[\s][0-9]{1,}<\/p>/Uis",$content, $matches)){
                $results = strip_tags($matches["0"]);
                $results = strstr(strstr($results," of",TRUE), "-");
                $results = intval(trim($results,"-"));
                if ( is_numeric($results) ){
                        if ( $results > "30"){
                                $pages = $results / 30;
                                $cur_page = $pages;
                                settype($cur_page,"int");
                                        if ( $pages > $cur_page ){
                                                $cur_page++;
                                        }
                        }
                        else { $cur_page = "1";}
                }
        }        
        return $cur_page;
    }

    ///////////////////////////////////////////
//    public function get_yp_us_biz_leads2($content){
//        //$content = file_get_contents($file_us_biz);
//        $content = str_replace("\n","",$content);
//        $content = str_replace("\r","",$content);
//        $content = str_replace("\t","",$content);
//        $content = str_replace("/span>","/span>\n",$content);
//        $content = str_replace("<span","\n<span",$content);
//        $content = str_replace("/h3>","/h3>\n",$content);
//        $content = str_replace("/h1>","/h1>\n",$content);
//        $content = str_replace("</div>","</div>\n",$content);
//        $content = str_replace("<h3","\n<h3",$content);
//        
//        $str = explode("\n", $content);
//        
//        $i = "0";
//        $complete = "0";
//            foreach($str as $d){
//                if (( preg_match("/<h3 class=\"business-name fn org\" data-lid=(.*)<\/h3>/Uis", $d))
//                    || ( preg_match("/<h3 class=\"business-title fn org\">(.*)<\/h3>/Uis", $d))
//                    || ( preg_match("/<h3 class=\"business-name fn has-menu org\" data-lid=(.*)<\/a>/Uis", $d))){
//                        $this->data[$i]['Company'] = strip_tags($d);
//                        $complete = "1";
//                }           
//                elseif ( preg_match("/<span class=\"street-address\">(.*)<\/span>/Uis", $d)){
//                        $this->data[$i]['Address'] = strip_tags($d);
//                        if ( isset ($complete)){
//                            $complete++;
//                        }
//                        else {
//                            $complete = "0";  
//                        }
//                }
//                elseif ( preg_match("/<span class=\"locality\">(.*)<\/span>/Uis", $d)){
//                        $this->data[$i]['City'] = strip_tags($d);
//                        if ( isset ($complete)){
//                            $complete++;
//                        }
//                        else {
//                            $complete = "0";  
//                        }
//                }
//                elseif ( preg_match("/<span class=\"region\">[A-Z]{2}<\/span>/Uis", $d)){
//                        $this->data[$i]['State'] = strip_tags($d);
//                        if ( isset ($complete)){
//                            $complete++;
//                        }
//                        else {
//                            $complete = "0";  
//                        }
//                }
//                elseif ( preg_match("/<span class=\"postal-code\">(\d{5})<\/span>/Uis", $d)) {
//                        $this->data[$i]['Zip'] = strip_tags($d);
//                        if ( isset ($complete)){
//                            $complete++;
//                        }
//                        else {
//                            $complete = "0";  
//                        }
//                }
//                elseif (( preg_match("/<span class=\"business-phone phone\">.*[0-9]{3}-[0-9]{4}<\/span>/Uis", $d))
//                    || ( preg_match("/<span class=\"phone\">.*[0-9]{3}-[0-9]{4}<\/span>/Uis", $d))) {
//                        $this->data[$i]['Phone'] = strip_tags($d);
//                        if ( isset ($complete)){
//                            $complete++;
//                        }
//                        else {
//                            $complete = "0";  
//                        }
//                }
//                elseif ( preg_match("/<div class=\"categories\">What:(.*)<\/div>/Uis", $d)){
//                        $d = str_replace("What:","",strip_tags($d));
//                        $d = str_replace("Add Photos","",($d));
//                        $this->data[$i]['Industry'] = str_replace("More Info","",($d));
//                        if ( isset ($complete)){
//                            $complete++;
//                        if( $complete == "7") {
//                            $i++;
//                        }
//                        else {
//                            $complete = "0";
//                            unset($this->data[$i]);
//                            }
//                        }        
//                }
//           }
//        return $this->data;
//    }
    
//    public function print_leads2($array){
//        $i=0;  
//        while ($i < count($array)) 
//            {
//            if ( !isset($array[$i]['Industry'])){
//                unset($array[$i]);
//            }
//            echo $i.")..".$array[$i]['Company']."<br />";
//            echo ".....".$array[$i]['Address']."<br />";
//            echo ".....".$array[$i]['City']."<br />";
//            echo ".....".$array[$i]['State']."<br />";
//            echo ".....".$array[$i]['Zip']."<br />";
//            echo ".....".$array[$i]['Phone']."<br />";
//            echo ".....".$array[$i]['Industry']."<br /><br /><br />";
//            $i++; 
//        
//            //    if ($data[$i] == count($data)) 
//            //    { 
//            //      echo $data[$i]; 
//            //      reset ($data); 
//            //      echo '<br />'."..."; 
//            //      exit(); 
//            //    } 
//            }
//    }

}
?>
